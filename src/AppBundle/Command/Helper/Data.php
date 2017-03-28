<?php

namespace AppBundle\Command\Helper;
use Asian\UserBundle\Entity\ApiUser;
use Asian\RequestApiBundle\Helper\Data as RequestHelper;
use Symfony\Component\Config\Definition\Exception\Exception;
use Unirest;
class Data extends RequestHelper
{
	const ACCEPT = 'application/json';

	public function isLoggedInCommand(ApiUser $apiUser)
	{
		$headers = ['AOToken' => $apiUser->getAOToken(),
			'accept' => self::ACCEPT,
		];
		$response = Unirest\Request::get(parent::URL_LOGGED, $headers);

		if ($response->code != 200) {
			throw new Exception('Response code is:' . $response->code);
		}

		if ($response->body->Code < 0) {
			return false;
		}

		return true;
	}
}