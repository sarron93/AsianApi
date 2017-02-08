<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 29.01.2017
 * Time: 15:20
 */

namespace Asian\RequestApiBundle\Controller;

use Asian\UserBundle\Helper\Data;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
			$helper = new Data();

			$user = $this->get('fos_user.user_manager')
				->findUserByUsername($request->query->get('username'));

			if(!$user->checkUser($request->headers->get('token'))) {
				throw new Exception();
			}

			$bookies = $request->query->get('bookies') ? $request->query->get('bookies') : 'ALL';
			$sports = $request->query->get('sports') ? $request->query->get('sports') : 1;
			$leagues = $request->query->get('leagues') ? $request->query->get('leagues') : 'ALL';
			$oddsFormat = $request->query->get('oddsFormat') ? $request->query->get('oddsFormat') : '00';
			$marketTypeId = $request->query->get('marketTypeId') ? $request->query->get('marketTypeId') : '0';

			$apiUser = $user->getApiUser();

			$headers = [
				'AOToken' => $apiUser->getAOToken(),
				'Accept' => $request->headers->get('accept')
			];

			$query = [
				'bookies' => $bookies,
				'sportsType' => $sports,
				'leagues' => $leagues,
				'oddsFormat' => $oddsFormat,
				'marketTypeId' => $marketTypeId,
			];

			$response = Unirest\Request::get($helper->getApiFeedsUrl(), $headers, $query);

			if ($response->code !== 200) {
				throw new HttpException($response->code, 'Response Error');
			}

			return $this->json($response->body);

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
			$helper = new Data();

			$user = $this->get('fos_user.user_manager')
				->findUserByUsername($request->query->get('username'));

			if(!$user->checkUser($request->headers->get('token'))) {
				throw new Exception();
			}

			$bookies = $request->query->get('bookies') ? $request->query->get('bookies') : 'ALL';
			$sports = $request->query->get('sports') ? $request->query->get('sports') : 1;
			$marketTypeId = $request->query->get('marketTypeId') ? $request->query->get('marketTypeId') : '0';

			$apiUser = $user->getApiUser();

			$headers = [
				'AOToken' => $apiUser->getAOToken(),
				'Accept' => $request->headers->get('accept')
			];

			$query = [
				'bookies' => $bookies,
				'sportsType' => $sports,
				'marketTypeId' => $marketTypeId,
			];

			$response = Unirest\Request::get($helper->getApiLeaguesUrl(), $headers, $query);

			if ($response->code !== 200) {
				throw new HttpException($response->code, 'Response Error');
			}

			return $this->json($response->body);
		} catch (Exception $e) {
			throw new HttpException('400', 'Invalid Data');
		}
	}
}