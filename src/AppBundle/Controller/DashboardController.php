<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 25.04.2017
 * Time: 13:51
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\BrowserKit\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{

	/**
	 * @Route("/dashboard")
	 * @Method("GET")
	 */
	public function indexAction(Request $request = null)
	{
		if(!$this->_isActive()) {
			return $this->redirectToRoute('fos_user_security_login');
		}
		return $this->render('AppBundle:Default:index.html.twig');
	}

	/**
	 * is active user
	 *
	 * @return boolean
	 */
	protected function _isActive()
	{
		return $this->get('asian_user.session')->checkActiveToken();
	}
}