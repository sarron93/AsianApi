<?php

namespace AppBundle\Command\Helper;
use Asian\UserBundle\Entity\ApiUser;
use Asian\RequestApiBundle\Helper\Data as RequestHelper;
use Monolog\Handler\StreamHandler;
use Symfony\Component\Config\Definition\Exception\Exception;
use Monolog\Logger;
use Unirest;
class Data extends RequestHelper
{
	const ACCEPT = 'application/json';

	public function isLoggedIn(ApiUser $apiUser, $logDir)
	{
		$headers = ['AOToken' => $apiUser->getAOToken(),
			'accept' => self::ACCEPT,
		];
		try {
			$response = Unirest\Request::get(parent::URL_LOGGED, $headers);

			if ($response->code != 200) {
				throw new Exception('Response code is:' . $response->code);
			}
		} catch (Exception $e) {
			$log = new Logger('isLoggedIn');
			$log->pushHandler();
		}
	}
}