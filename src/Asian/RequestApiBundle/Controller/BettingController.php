<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 29.01.2017
 * Time: 15:20
 */

namespace Asian\RequestApiBundle\Controller;

use Asian\RequestApiBundle\Model\ApiWeb;
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
			$user = $this->get('fos_user.user_manager')
				->findUserByUsername($request->query->get('username'));

			if(!$user->checkUser($request->headers->get('token'))) {
				throw new Exception();
			}

			$memcache = new Cache();
			$feeds = $memcache->getParam('feeds_live');
			if (!$feeds) {
				return $this->json(['Code' => '-1']);
			}
			return $this->json($feeds);

		} catch (Exception $e) {
			throw new HttpException(400, 'Invalid Data');
		}
	}

	/**
	 * @Route("getLeagues/")
	 * @Method("GET")
	 */
	public function getLeaguesAction(Request $request)
	{
		try{
			$user = $this->get('fos_user.user_manager')
				->findUserByUsername($request->query->get('username'));

			if(!$user->checkUser($request->headers->get('token'))) {
				throw new Exception();
			}

			$memcache = new Cache();

			$leagues = $memcache->getParam('leagues_live');

			if (!$leagues) {
				return $this->json(['Code' => '-1']);
			}

			return $this->json($leagues);
		} catch (Exception $e) {
			throw new HttpException('400', 'Invalid Data');
		}
	}

	/**
	 * @Route("getPlacement/")
	 * @Method("GET")
	 */
	public function getPlacementInfoAction(Request $request)
	{
		$helper = new Data();
		$user = $this->get('fos_user.user_manager')->findUserByUsername($request->query->get('username'));
		if (!$user->checkUser($request->headers->get('token'))) {
			throw new Exception('Invalid user data');
		}

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
		$url = $helper->getApiPlacementInfo() . '?';
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

		$response = ApiWeb::sendPostRequest($request, $headers, $body);

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

		$placeBetResponce = $this->_placeBetAction($postParams, $headers);

		return $this->json($placeBetResponce);
	}

	protected function _placeBetAction($postParams, $headers)
	{
		$helper = new Data();
		$url = $helper->getPlaceBet();
		$body = Unirest\Request\Body::Json($postParams);

		$response = ApiWeb::sendPostRequest($url, $headers, $body);
		return $response;
	}

}