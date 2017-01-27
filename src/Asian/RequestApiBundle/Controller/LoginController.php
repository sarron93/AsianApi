<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 25.01.2017
 * Time: 8:11
 */

namespace Asian\RequestApiBundle\Controller;

use Asian\RequestApiBundle\Event\ApiLoginEvent;
use Asian\RequestApiBundle\Event\ApiLoginSubscriber;
use Asian\UserBundle\Helper\Data;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Unirest;

class LoginController extends Controller
{
	/**
	 * @Route("login/")
	 * @Method("GET")
	 */
	public function loginAction(Request $request)
	{
		try {
			$user = $this->get('fos_user.user_manager')
				->findUserByUsername($request->query->get('username'));

			$factory = $this->get('security.encoder_factory');

			if (!$request->headers->get('timestamp')) {
				throw new Exception();
			}

			if (is_null($user)) {
            	throw new Exception();
            }

            if (!$user->isIdUser()) {
				throw  new Exception();
            }

            if (!$user->isPasswordValid($request->query->get('password'), $factory)) {
				throw new Exception();
            }

            $helper = new Data();

			$token = $helper->generateToken($user, $request->query->get('password'),
				$request->headers->get('timestamp'));

			$user->setToken($token);

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();


			if ($request->headers->get('token') != $token) {
				throw new Exception();
			}

			return $this->json(array('id' => $user->getId(), 'token' => $token));

		} catch (Exception $e) {
			throw  new HttpException(400, "Invalid user data");
		}
	}

	/**
	 * @Route("loginAPI/")
	 * @Method("GET")
	 */
	public function loginApiAction(Request $request)
	{
		try {

			$apiHelper = new \Asian\RequestApiBundle\Helper\Data();

			$helper = new Data();

			$user = $this->get('fos_user.user_manager')
				->findUserByUsername($request->query->get('username'));

			if (is_null($user)) {
				throw  new Exception();
			}

			if (!$user->isIdUser() && !$user->isApiUser()) {
				throw new Exception();
			}

			if (!($user->getToken() == $request->headers->get('token'))) {
				throw new Exception();
			}


			if ($apiHelper->isLoggedIn($user->getApiUser(), $request->headers->get('accept'))) {
				return $this->json(
					['Code' => 0,
					'Result' => ['Token' => $user->getApiUser()->getAOToken()]
				]);
			}

			$apiLoginUrl = $helper->getApiLoginUrl();
			$headers = ['accept' => $request->headers->get('accept')];
			$query = ['username' => $user->getApiUser()->getUsername(),
					  'password' => $user->getApiUser()->getPassword()
			];

			$response = Unirest\Request::get($apiLoginUrl, $headers, $query);

			if ($response->code != 200) {
				throw new Exception();
			}

			if ($response->body->Code == 0) {
				$apiUser = $user->getApiUser();

				$apiUser->setAOKey($response->body->Result->Key);
				$apiUser->setAOToken($response->body->Result->Token);

				$em = $this->getDoctrine()->getManager();
				$em->persist($apiUser);
				$em->flush();
			}

			$dispatcher = new EventDispatcher();
			$subscriber = new ApiLoginSubscriber();

			$dispatcher->addSubscriber($subscriber);

			$event = new ApiLoginEvent($apiUser, $response, $request);
			$dispatcher->dispatch(ApiLoginEvent::API_LOGIN_SUCCESS, $event);

			return $this->json($response->body);

		} catch (Exception $e) {
			throw new HttpException(400, "Invalid request");
		}
	}
}
