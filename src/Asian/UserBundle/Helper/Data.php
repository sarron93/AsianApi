<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 25.01.2017
 * Time: 8:47
 */

namespace Asian\UserBundle\Helper;

use Asian\UserBundle\Entity\ApiUser;
use Asian\UserBundle\Entity\User;

class Data
{
	const API_URL = 'https://webapi.asianodds88.com/AsianOddsService';
	/**
	 * generation Token
	 *
	 * @param User $user
	 * @param $password
	 * @param $salt
	 * @return string
	 */
	public static function generateToken(User $user, $password,$salt)
	{
		return sha1($salt . $user->getUsername()
					. $salt . $password);
	}

	/**
	 * get api login url
	 *
	 * @return string
	 */
	public static function getApiLoginUrl()
	{
		return self::API_URL . "/Login";
	}

	/**
	 * get api register url
	 *
	 * @param ApiUser $apiUser
	 * @return string
	 */
	public static function getApiRegisterUrl(ApiUser $apiUser)
	{
		return $apiUser->getUrl() . "/Register";
	}

	/**
	 * get leagues url
	 *
	 * @param ApiUser $apiUser
	 * @return string
	 */
	public static function getApiLeaguesUrl(ApiUser $apiUser)
	{
		return $apiUser->getUrl() . "/GetLeagues";
	}

	/**
	 * get feeds url
	 *
	 * @param ApiUser $apiUser
	 * @return string
	 */
	public static function getApiFeedsUrl(ApiUser $apiUser)
	{
		return $apiUser->getUrl() . "/GetFeeds";
	}

	/**
	 * get placement info url
	 *
	 * @param ApiUser $apiUser
	 * @return string
	 */
	public static function getApiPlacementInfo(ApiUser $apiUser)
	{
		return $apiUser->getUrl() . "/GetPlacementInfo";
	}

	/**
	 * place bet url
	 *
	 * @param ApiUser $apiUser
	 * @return string
	 */
	public static function getPlaceBet(ApiUser $apiUser)
	{
		return $apiUser->getUrl() . "/PlaceBet";
	}

	/**
	 * account summary url
	 *
	 * @param ApiUser $apiUser
	 * @return string
	 */
	public static function getAccountSummary(ApiUser $apiUser)
	{
		return $apiUser->getUrl() . "/GetAccountSummary";
	}

	/**
	 * is logged in url
	 *
	 * @param ApiUser $apiUser
	 * @return string
	 */
	public static function isLosggedIn(ApiUser $apiUser)
	{
		return $apiUser->getUrl() . "/IsLoggedIn";
	}
}
