<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 25.04.2017
 * Time: 12:56
 */

namespace Asian\UserBundle\Model;


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class CustomerSession
{
	protected $_session;
	protected $_em;
	private static $_instance = null;

	public static function getInstance(Session $session, EntityManager $em)
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self($session, $em);
		}
		return self::$_instance;
	}

	private function __construct(Session $session, EntityManager $em)
	{
		$this->_session = unserialize($session->get('_security_main'));
		$this->_em = $em;
	}

	/**
	 * get customer
	 *
	 * @return \Asian\UserBundle\Entity\User|null|object
	 */
	public function getCustomer()
	{
		if (!$this->checkSession()) {
			return null;
		}
		return $this->_em->getRepository('AsianUserBundle:User')->find($this->getCustomerId());
	}

	/**
	 * get customer id
	 *
	 * @return mixed
	 */
	public function getCustomerId()
	{
		if (!$this->checkSession()) {
			return null;
		}
		return $this->_session->getUser()->getId();
	}

	/**
	 * check session
	 *
	 * @return boolean
	 */
	public function checkSession()
	{
		return (boolean) $this->_session;
	}

}