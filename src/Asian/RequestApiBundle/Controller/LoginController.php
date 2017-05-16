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
				throw new Exception('Invalid parameter timestamp');
			}
            if (!$user->getId()) {
				throw  new Exception('User can\'t find');
            }

            if (!$user->isPasswordValid($request->query->get('password'), $factory)) {
				throw new Exception('Login or password incorrect');
            }

			$token = Data::generateToken($user, $request->query->get('password'),
				$request->headers->get('timestamp'));
			$user->setToken($token);

			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			if ($request->headers->get('token') != $token) {
				throw new Exception('Invalid User data');
			}
			return $this->json(array('id' => $user->getId(), 'token' => $token));

		} catch (Exception $e) {
			return $this->json(['Code' => '-1', 'Message' => $e->getMessage()]);
		}
	}

	/**
	 * @Route("loginAPI/")
	 * @Method("GET")
	 */
	public function loginApiAction(Request $request)
	{

		try {
			$adapter = $this->get('asian_request.adapter.factory');

			if (!$adapter->checkUser()) {
				throw  new Exception('Invalid User data');
			}

			$user = $adapter->getUser();

			if (!$user->isApiUser()) {
				throw new Exception('Invalid User data');
			}

			if (!is_null($user->getApiUser()->getUrl())
				&& $user->getApiUser()->isLoggedIn($request->headers->get('accept'))) {
				return $this->json(
					['Code' => 0,
					'Result' => ['Token' => $user->getApiUser()->getAOToken()]
				]);
			}

			$apiLoginUrl = Data::getApiLoginUrl();
			$headers = ['accept' => $request->headers->get('accept')];
			$query = ['username' => $user->getApiUser()->getUsername(),
					  'password' => $user->getApiUser()->getPassword()
			];

			$response = ApiWeb::sendGetRequest($apiLoginUrl, $headers, $query);

			if ($response->Code == 0) {
				$apiUser = $user->getApiUser();

				$em = $this->getDoctrine()->getManager();
				$em->persist($apiUser->setUserData($response));
				$em->flush();
			}

			$dispatcher = new EventDispatcher();
			$subscriber = new ApiLoginSubscriber();

			$dispatcher->addSubscriber($subscriber);

			$event = new ApiLoginEvent($apiUser, $response, $request);
			$dispatcher->dispatch(ApiLoginEvent::API_LOGIN_SUCCESS, $event);

			return $this->json($response);

		} catch (Exception $e) {
			return $this->json(['Code' => '-1', 'Message' => $e->getMessage()]);
		}
	}
}
