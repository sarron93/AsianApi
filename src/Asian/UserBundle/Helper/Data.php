<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 25.01.2017
 * Time: 8:47
 */

namespace Asian\UserBundle\Helper;

use Asian\UserBundle\Entity\User;

class Data
{
	const API_URL = "https://webapi.asianodds88.com/";
	/**
	 * generation Token
	 *
	 * @param User $user
	 * @param $password
	 * @param $timestamp
	 * @return string
	 */
	public function generateToken(User $user, $password,$timestamp)
	{
		return sha1($timestamp . $user->getUsername()
					. $timestamp . $password);
	}

	public function getApiLoginUrl()
	{
		return self::API_URL."AsianOddsService/Login";
	}

	public function getApiRegisterUrl()
	{
		return self::API_URL."AsianOddsService/Register";
	}

	public function getApiLeaguesUrl()
	{
		return self::API_URL."AsianOddsService/GetLeagues";
	}

	public function getApiFeedsUrl()
	{
		return self::API_URL."AsianOddsService/GetFeeds";
	}
	public function getApiPlacementInfo()
	{
		return self::API_URL."/AsianOddsService/GetPlacementInfo";
	}

	public function getPlaceBet()
	{
		return self::API_URL."AsianOddsService/PlaceBet";
	}

	public function getAccountSummary()
	{
		return self::API_URL."/AsianOddsService/GetAccountSummary";
	}
}
