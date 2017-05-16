<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 29.01.2017
 * Time: 15:20
 */

namespace Asian\RequestApiBundle\Controller;

use Asian\RequestApiBundle\Model\ApiWeb;
use Asian\UserBundle\Entity\ApiUser;
use Asian\UserBundle\Helper\Data;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Asian\RequestApiBundle\Model\Cache;
use Unirest;


class BettingController extends Controller
{
	/**
	 * @Route("getFeeds/")
	 * @Method("GET")
	 */
	public function getFeedsAction(Request $request)
	{
		try {
			$adapter = $this->get('asian_request.adapter.factory');

			if (!$adapter->checkUser()) {
				throw new Exception('Invalid user data');
			}

			$memcache = $this->get('asian_request.cache');
			$feeds = $memcache->getParam('feeds_live');
			if (!$feeds) {
				return $this->json(['Code' => '-1']);
			}
			return $this->json($feeds);

		} catch (Exception $e) {
			return $this->json(['Code' => '-1', 'Message' => $e->getMessage()]);
		}
	}

	/**
	 * @Route("getLeagues/")
	 * @Method("GET")
	 */
	public function getLeaguesAction(Request $request)
	{
		try{
			$adapter = $this->get('asian_request.adapter.factory');

			if (!$adapter->checkUser()) {
				throw new Exception('Invalid user data');
			}

			$memcache = $this->get('asian_request.cache');

			$leagues = $memcache->getParam('leagues_live');

			if (!$leagues) {
				return $this->json(['Code' => '-1']);
			}

			return $this->json($leagues);
		} catch (Exception $e) {
			return $this->json(['Code' => '-1', 'Message' => $e->getMessage()]);
		}
	}

	/**
	 * @Route("getPlacement/")
	 * @Method("GET")
	 */
	public function getPlacementInfoAction(Request $request)
	{
		$adapter = $this->get('asian_request.adapter.factory');

		if (!$adapter->checkUser()) {
			throw new Exception('Invalid user data');
		}

		$user = $adapter->getUser();

		$apiUser = $user->getApiUser();

		$headers = [
			'AOToken' => $apiUser->getAOToken(),
			'Accept' => $request->headers->get('accept'),
			'Content-Type' => 'application/json',
		];

		$matchId = $request->query->get('match_id');
		$isFulltime = $request->query->get('is_full_time');
		$gameId = $request->query->get('game_id');
		$gameType = $request->query->get('game_type');
		$oddsName = $request->query->get('odds_name');
		$oddsFormat = $request->query->get('odds_format') ?: '00';
		$bookies = $request->query->get('bookies');
		$marketTypeId = $request->query->get('market_type_id');
		$sportsType = $request->query->get('sports_type') ?: 0;
		$getParams = [
			'matchId' => $matchId,
			'isFullTime' => $isFulltime,
			'gameId' => $gameId,
			'gameType' => $gameType,
			'oddsName' => $oddsName,
			'oddsFormat' => $oddsFormat,
		];
		$url = Data::getApiPlacementInfo($apiUser) . '?';
		$params = http_build_query($getParams);

		$url .= $params;

		$data = [
			'GameId' => $gameId,
			'GameType' => $gameType,
			'IsFullTime' => $isFulltime,
			'Bookies' => $bookies,
			'MarketTypeId' => $marketTypeId,
			'OddsFormat' => $oddsFormat,
			'OddsName' => $oddsName,
			"SportsType" => $sportsType
		];
		$body = Unirest\Request\Body::Json($data);

		$response = ApiWeb::sendPostRequest($url, $headers, $body);

		if ($response->Code < 0) {
			return $this->json($response);
		}

		$result = $response->Result->OddsPlacementData[0];

		if ($result->Odds < $request->query->get('odds')) {
			return $this->json([
				'code' => 0,
				'message' => 'Коэффициент упал ниже ожидаемого']);
		}

		$amount = $result->MinimumAmount;
		$oddPlacementId = $result->OddPlacementId;
		$bookieOdds = $bookies . ':' . $result->Odds;

		$postParams = [
			'IsFullTime' => $isFulltime,
			'MarketTypeId' => $marketTypeId,
			'PlaceBetId' => $oddPlacementId,
			'GameId' => $gameId,
			'GameType' => $gameType,
			'OddsName' => $oddsName,
			'OddsFormat' => $oddsFormat,
			'BookieOdds' => $bookieOdds,
			'SportsType' => $sportsType,
			'Amount' => $amount,
		];

		$placeBetResponce = $this->_placeBetAction($postParams, $headers, $apiUser);

		return $this->json($placeBetResponce);
	}

	/**
	 * place bet action
	 *
	 * @param $postParams
	 * @param $headers
	 * @param ApiUser $apiUser
	 * @return mixed
	 */
	protected function _placeBetAction($postParams, $headers, ApiUser $apiUser)
	{
		$url = Data::getPlaceBet($apiUser);
		$body = Unirest\Request\Body::Json($postParams);

		$response = ApiWeb::sendPostRequest($url, $headers, $body);
		return $response;
	}

}