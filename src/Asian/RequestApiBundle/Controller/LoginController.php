<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 25.01.2017
 * Time: 8:11
 */

namespace Asian\RequestApiBundle\Controller;

use Asian\UserBundle\Helper\Data;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginController extends Controller
{
	/**
	 * @Route("login/")
	 * @Method({"GET", "POST"})
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

            if (!$user->getId()) {
				throw  new Exception();
            }

            if (!$user->isPasswordValid($request->query->get('password'), $factory)) {
				throw new Exception();
            }

            $helper = new Data();

			$token = $helper->generateToken($user, $request->query->get('password'),
				$request->headers->get('timestamp'));

			if ($request->headers->get('token') != $token) {
				throw new Exception();
			}

			return $this->json(array('token' => $token));

		} catch (Exception $e) {
			throw  new HttpException(400, "Invalid user data");
		}
	}
}
