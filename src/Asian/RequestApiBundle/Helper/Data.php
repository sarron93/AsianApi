<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 27.01.2017
 * Time: 8:08
 */

namespace Asian\RequestApiBundle\Helper;

use Asian\UserBundle\Entity\ApiUser;
use Symfony\Component\Config\Definition\Exception\Exception;
use Unirest;


class Data
{
	const URL_LOGGED = "https://webapi.asianodds88.com//AsianOddsService/IsLoggedIn";

	public function isLoggedIn(ApiUser $apiUser, $accept)
	{
		$headers = ['AOToken' => $apiUser->getAOToken(),
					'accept' => $accept,
		];

		$response = Unirest\Request::get(self::URL_LOGGED, $headers);

		if ($response->code != 200) {
			throw new Exception();
		}

		if ($response->body->Code == -1) {
			return false;
		}

		return true;
	}
}