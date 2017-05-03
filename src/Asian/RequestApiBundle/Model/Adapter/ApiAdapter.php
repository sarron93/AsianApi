<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 03.05.2017
 * Time: 20:31
 */

namespace Asian\RequestApiBundle\Model\Adapter;


use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\HttpFoundation\RequestStack;

class ApiAdapter implements Adapter
{
	protected $_userManager;
	protected $_requestStack;
	protected $_user;

	/**
	 * ApiAdapter constructor.
	 * @param UserManager $userManager
	 * @param RequestStack $requestStack
	 */
	public function __construct(UserManager $userManager, RequestStack $requestStack)
	{
		$this->_userManager = $userManager;
		$this->_requestStack = $requestStack;

		$this->_user = $this->_userManager->findUserByUsername($this->_requestStack->getCurrentRequest()->query->get('username'));
	}

	/**
	 * get user
	 */
	public function getUser()
	{
		return $this->_user;
	}

	/**
	 * check params
	 *
	 * @return bool
	 */
	public function checkUser()
	{
		return $this->_user->checkUser($this->_requestStack->getCurrentRequest()->headers->get('token'));
	}
}