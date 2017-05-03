<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 03.05.2017
 * Time: 20:28
 */

namespace Asian\RequestApiBundle\Model\Adapter;


use Asian\UserBundle\Model\CustomerSession;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Factory
{
	/**
	 * get adapter
	 *
	 * @param CustomerSession $session
	 * @param ContainerInterface $container
	 * @return ApiAdapter|WebAdapter|object
	 */
	static function getAdapter(CustomerSession $session, ContainerInterface $container)
	{
		if ($session->checkSession()) {
			return  $container->get('asian_request.adapter.web');
		}
		return $container->get('asian_request.adapter.api');
	}
}