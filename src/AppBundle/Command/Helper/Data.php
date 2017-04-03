<?php

namespace AppBundle\Command\Helper;
use Asian\RequestApiBundle\Model\ApiWeb;
use Asian\UserBundle\Entity\ApiUser;
use Asian\RequestApiBundle\Helper\Data as RequestHelper;
class Data extends RequestHelper
{
	const ACCEPT = 'application/json';

	public function isLoggedInCommand(ApiUser $apiUser)
	{
		$headers = [
			'AOToken' => $apiUser->getAOToken(),
			'accept' => self::ACCEPT,
		];

		$response = ApiWeb::sendGetRequest(parent::URL_LOGGED, $headers);

		if ($response->Code < 0) {
			return false;
		}

		return true;
	}
}