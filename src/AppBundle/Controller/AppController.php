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
	public function indexAction(Request $request = null)
	{
		return $this->render('AppBundle:Default:index.html.twig');
	}
}