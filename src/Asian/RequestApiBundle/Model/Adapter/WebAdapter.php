<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 03.05.2017
 * Time: 20:21
 */

namespace Asian\RequestApiBundle\Model\Adapter;

use Asian\UserBundle\Model\CustomerSession;

class WebAdapter implements Adapter
{
	protected  $_session;

	/**
	 * WebAdapter constructor.
	 *
	 * @param CustomerSession $session
	 */
	public function __construct(CustomerSession $session)
	{
		$this->_session = $session;
	}

	/**
	 * get user
	 *
	 * @return \Asian\UserBundle\Entity\User|null|object
	 */
	public function getUser()
	{
		return $this->_session->getCustomer();
	}

	/**
	 * check user
	 *
	 * @return bool
	 */
	public function checkUser()
	{
		return $this->_session->checkActiveToken();
	}
}