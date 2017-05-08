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
	protected $url;

	const API_URL = 'https://webapi.asianodds88.com/AsianOddsService';

	public function __construct(ApiUser $apiUser)
	{
		$this->url = $apiUser->getUrl();
	}
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

	public function getApiLoginUrl()
	{
		return self::API_URL."/Login";
	}

	public function getApiRegisterUrl()
	{
		return $this->url."/Register";
	}

	public function getApiLeaguesUrl()
	{
		return $this->url."/GetLeagues";
	}

	public function getApiFeedsUrl()
	{
		return $this->url."/GetFeeds";
	}
	public function getApiPlacementInfo()
	{
		return $this->url."/GetPlacementInfo";
	}

	public function getPlaceBet()
	{
		return $this->url."/PlaceBet";
	}

	public function getAccountSummary()
	{
		return $this->url."/GetAccountSummary";
	}
}
