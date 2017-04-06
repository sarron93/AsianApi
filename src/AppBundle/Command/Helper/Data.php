<?php

namespace AppBundle\Command\Helper;
use Asian\RequestApiBundle\Model\ApiConsole;
use Asian\UserBundle\Entity\ApiUser;
use Asian\RequestApiBundle\Helper\Data as RequestHelper;
use Symfony\Component\Config\Definition\Exception\Exception;

class Data extends RequestHelper
{
	const ACCEPT = 'application/json';

	public function isLoggedInCommand(ApiUser $apiUser)
	{
		try {
			$headers = [
				'AOToken' => $apiUser->getAOToken(),
				'accept' => self::ACCEPT,
			];

			ApiConsole::sendGetRequest(parent::URL_LOGGED, $headers);
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}