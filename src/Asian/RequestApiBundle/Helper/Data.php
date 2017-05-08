<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 27.01.2017
 * Time: 8:08
 */

namespace Asian\RequestApiBundle\Helper;

use Asian\RequestApiBundle\Model\ApiWeb;
use Asian\UserBundle\Entity\ApiUser;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Data
{
	const URL_LOGGED = "/IsLoggedIn";

	public function isLoggedIn(ApiUser $apiUser, $accept)
	{
		$headers = ['AOToken' => $apiUser->getAOToken(),
					'accept' => $accept,
		];

		$response = ApiWeb::sendGetRequest($apiUser->getUrl() . self::URL_LOGGED, $headers);

		if ($response->Code == -1) {
			return false;
		}

		return true;
	}
}
