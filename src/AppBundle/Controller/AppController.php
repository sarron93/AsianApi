<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 29.03.2017
 * Time: 20:19
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\BrowserKit\Request;

class AppController extends Controller
{
	/**
	 * @Route("/")
	 * @Method("GET")
	 */
	public function indexAction()
	{
		$customerSession = $this->container->get('asian_user.session');

		if (is_null($customerSession->getCustomerId())) {
			return $this->redirectToRoute('fos_user_security_login');
		}


		return $this->redirectToRoute('app_dashboard_index');
	}
}
