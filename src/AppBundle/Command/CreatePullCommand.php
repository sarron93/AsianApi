<?php

namespace AppBundle\Command;

use Asian\RequestApiBundle\Model\ApiConsole;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Command\Helper\Data;
use Asian\UserBundle\Helper\Data as HelperUser;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class CreatePullCommand extends ContainerAwareCommand
{
	const BOOKIES = 'ALL';
	const SPORTS = 1;
	const LEAGUES = 'ALL';
	const ODDS_FORMAT = '00';
	const MARKET_LIVE = 0;
	const MARKET_TODAY = 1;
	const MARKET_EARLY = 2;
	const ACCEPT = 'application/json';

	private $_em;
	private $_apiUser;
	private $_kernel;

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$helper = new Data();
		$this->_em = $this->getContainer()->get('doctrine.orm.entity_manager');
		$this->_kernel = $this->getContainer()->get('kernel');
		$this->_apiUser = $this->_em->getRepository('AsianUserBundle:ApiUser')->getFirstElement();
		$memcache = $this->getContainer()->get('asian_request.cache');

		try {
			if (!$helper->isLoggedInCommand($this->_apiUser)) {
				$this->_loginApi();
			}

			$leaguesLive = $this->_getLeagues(self::MARKET_LIVE);
			$leaguesToday = $this->_getLeagues(self::MARKET_TODAY);
			$leaguesEarly = $this->_getLeagues(self::MARKET_EARLY);

			$feedsLive = $this->_getFeeds(self::MARKET_LIVE);
			$feedsToday = $this->_getFeeds(self::MARKET_TODAY);
			$feedsEarly = $this->_getFeeds(self::MARKET_EARLY);

			$memcache->setParam('feeds_live', $feedsLive);
			$memcache->setParam('feeds_today', $feedsToday);
			$memcache->setParam('feeds_early', $feedsEarly);

			$memcache->setParam('leagues_live', $leaguesLive);
			$memcache->setParam('leagues_today', $leaguesToday);
			$memcache->setParam('leagues_early', $leaguesEarly);

		} catch (Exception $e) {
			$log = new Logger('isLoggedIn');
			$log->pushHandler(new StreamHandler(
				$this->_kernel->getLogDir() . DIRECTORY_SEPARATOR . 'console_feeds.log',
				Logger::WARNING
			));
			$log->addWarning($e->getMessage());
		}
	}
	protected function configure()
	{
		$this->setName('app:create-pull')
			->setDescription('Create json file witch getFeeds')
			->setHelp('This command create json file into folder /var/pool/');
	}

	/**
	 * login Api Action
	 *
	 * @return void
	 */
	private function _loginApi()
	{
		$helper = new HelperUser();
		$apiLoginUrl = $helper->getApiLoginUrl();
		$headers = ['accept' => self::ACCEPT];
		$query = ['username' => $this->_apiUser->getUsername(),
			'password' => $this->_apiUser->getPassword()
		];

		$response = ApiConsole::sendGetRequest($apiLoginUrl, $headers, $query);

		if ($response->Code < 0) {
			throw new Exception(json_decode($response));
		}

		$this->_apiUser->setAOKey($response->Result->Key);
		$this->_apiUser->setAOToken($response->Result->Token);

		$this->_em->persist($this->_apiUser);
		$this->_em->flush();

		$this->_registerApi();
	}

	/**
	 * register account api
	 *
	 * @return void
	 */
	private function _registerApi()
	{
		$helper = new HelperUser();
		$sendHeaders = [
			'AOKey' => $this->_apiUser->getAOKey(),
			'AOToken' => $this->_apiUser->getAOToken(),
			'accept' => self::ACCEPT,
		];

		$query = [
			'username' => $this->_apiUser->getUsername(),
		];

		$response = ApiConsole::sendGetRequest($helper->getApiRegisterUrl(), $sendHeaders, $query);

		if ($response->Code < 0) {
			throw new Exception(json_decode($response));
		}
	}

	/**
	 * get leagues
	 *
	 * @return mixed
	 */
	private function _getLeagues($marketTypeId)
	{
		$helper = new HelperUser();
		$headers = [
			'AOToken' => $this->_apiUser->getAOToken(),
			'Accept' => self::ACCEPT
		];

		$query = [
			'bookies' => self::BOOKIES,
			'sportsType' => self::SPORTS,
			'marketTypeId' => $marketTypeId,
		];

		$response = ApiConsole::sendGetRequest($helper->getApiLeaguesUrl(), $headers, $query);

		if ($response->Code < 0) {
			throw new Exception('get leagues method error');
		}

		return $response;
	}

	/**
	 * get feeds
	 *
	 * @param integer $marketTypeId market type Live, Today, Early
	 * @return mixed
	 */
	private function _getFeeds($marketTypeId)
	{
		$helper = new HelperUser();
		$headers = [
			'AOToken' => $this->_apiUser->getAOToken(),
			'Accept' => self::ACCEPT
		];

		$query = [
			'bookies' => self::BOOKIES,
			'sportsType' => self::SPORTS,
			'leagues' => self::LEAGUES,
			'oddsFormat' => self::ODDS_FORMAT,
			'marketTypeId' => $marketTypeId,
		];

		$response = ApiConsole::sendGetRequest($helper->getApiFeedsUrl(), $headers, $query);

		if ($response->Code < 0) {
			throw new Exception('Get feeds method error');
		}

		return $response;
	}
}