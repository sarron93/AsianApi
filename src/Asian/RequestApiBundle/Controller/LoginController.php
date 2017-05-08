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
use Asian\RequestApiBundle\Model\ApiWeb;
use Asian\UserBundle\Helper\Data;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
            if (!$user->getId()) {
				throw  new Exception();
            }

            if (!$user->isPasswordValid($request->query->get('password'), $factory)) {
				throw new Exception();
            }

			$token = Data::generateToken($user, $request->query->get('password'),
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

			$adapter = $this->get('asian_request.adapter.factory');

			if (!$adapter->checkUser()) {
				throw  new Exception();
			}

			$user = $adapter->getUser();

			if (!$user->isApiUser()) {
				throw new Exception();
			}

			if (!is_null($user->getApiUser()->getUrl())
				&& $apiHelper->isLoggedIn($user->getApiUser(), $request->headers->get('accept'))) {
				return $this->json(
					['Code' => 0,
					'Result' => ['Token' => $user->getApiUser()->getAOToken()]
				]);
			}

			$helper = new Data($user->getApiUser());

			$apiLoginUrl = $helper->getApiLoginUrl();
			$headers = ['accept' => $request->headers->get('accept')];
			$query = ['username' => $user->getApiUser()->getUsername(),
					  'password' => $user->getApiUser()->getPassword()
			];

			$response = ApiWeb::sendGetRequest($apiLoginUrl, $headers, $query);

			if ($response->Code == 0) {
				$apiUser = $user->getApiUser();

				$apiUser->setAOKey($response->Result->Key);
				$apiUser->setAOToken($response->Result->Token);
				$apiUser->setUrl($response->Result->Url);

				$em = $this->getDoctrine()->getManager();
				$em->persist($apiUser);
				$em->flush();
			}

			$dispatcher = new EventDispatcher();
			$subscriber = new ApiLoginSubscriber();

			$dispatcher->addSubscriber($subscriber);

			$event = new ApiLoginEvent($apiUser, $response, $request);
			$dispatcher->dispatch(ApiLoginEvent::API_LOGIN_SUCCESS, $event);

			return $this->json($response);

		} catch (Exception $e) {
			throw new HttpException(400, "Invalid request");
		}
	}
}
