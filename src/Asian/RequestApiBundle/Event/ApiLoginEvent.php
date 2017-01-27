<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 27.01.2017
 * Time: 9:49
 */

namespace Asian\RequestApiBundle\Event;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\Event;
use Asian\UserBundle\Entity\ApiUser;

/**
 * Event from Api Login
 *
 * Class ApiLoginEvent
 * @package Asian\RequestApiBundle\Event
 */
class ApiLoginEvent extends Event
{
	const API_LOGIN_SUCCESS = 'api_login.success';

	protected $apiUser;
	protected $response;
	protected $request;

	public function __construct(ApiUser $apiUser, $response, Request $request)
	{
		$this->apiUser = $apiUser;
		$this->response = $response;
		$this->request = $request;
	}

	public function getApiUser()
	{
		return $this->apiUser;
	}

	public function getResponse()
	{
		return $this->response;
	}

	public function getRequest()
	{
		return $this->request;
	}
}