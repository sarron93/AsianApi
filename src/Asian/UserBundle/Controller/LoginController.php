<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 17.04.2017
 * Time: 20:30
 */

namespace Asian\UserBundle\Controller;

use FOS\UserBundle\Controller\SecurityController;

class LoginController extends SecurityController
{
	protected function renderLogin(array $data)
	{
		$this->render('AsianUserBundle:Security:login.html.twig', $data);
	}
}