<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 06.04.2017
 * Time: 20:31
 */

namespace Asian\RequestApiBundle\Controller;


use Asian\RequestApiBundle\Model\ApiWeb;
use Asian\UserBundle\Helper\Data;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
	/**
	 * @Route("getLeagues/")
	 * @Method("GET")
	 */
	public function getAccountSummaryAction(Request $request)
	{
		$helper = new Data();
		try {
			$adapter = $this->get('asian_request.adapter.factory');

			if (!$adapter->checkUser()) {
				throw new Exception('Invalid user data');
			}

			$user = $adapter->getUser();

			$apiUser = $user->getApiUser();

			$headers = [
				'AOToken' => $apiUser->getAOToken(),
				'Accept' => $request->headers->get('accept'),
			];

			$response = ApiWeb::sendGetRequest($helper->getAccountSummary(), $headers);

			return $this->json($response);
		} catch (Exception $e) {
			throw new HttpException(400, 'Invalid User data');
		}
	}
}