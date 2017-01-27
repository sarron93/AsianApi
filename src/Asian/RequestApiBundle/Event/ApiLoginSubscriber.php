<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 27.01.2017
 * Time: 9:58
 */

namespace Asian\RequestApiBundle\Event;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Asian\UserBundle\Helper\Data;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Asian\RequestApiBundle\Event\ApiLoginEvent;
use Unirest;


class ApiLoginSubscriber implements EventSubscriberInterface
{

	public static function getSubscribedEvents()
	{
		return [
			ApiLoginEvent::API_LOGIN_SUCCESS => 'successApiLogin',
		];
	}

	public function successApiLogin(ApiLoginEvent $event)
	{
		$helper = new Data();
		$apiUser = $event->getApiUser();
		$response = $event->getResponse();
		$request = $event->getRequest();

		$sendHeaders = [
			'AOKey' => $apiUser->getAOKey(),
			'AOToken' => $apiUser->getAOToken(),
			'accept' => $request->headers->get('accept'),
		];

		$query = [
			'username' => $apiUser->getUsername(),
		];

		$response = Unirest\Request::get($helper->getApiRegisterUrl(), $sendHeaders, $query);

		if ($response->code != 200) {
			throw new Exception();
		}

		if ($response->body->Code == -1) {
			throw new Exception();
		}

	}

}