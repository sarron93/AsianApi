<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 25.04.2017
 * Time: 11:32
 */

namespace Asian\UserBundle\EventListener;


use Asian\UserBundle\Helper\Data;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class LoginListener implements EventSubscriberInterface
{
	protected $_em;
	protected $_session;

	/**
	 * LoginListener constructor.
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function __construct(Session $session,\Doctrine\ORM\EntityManager $em)
	{
		$this->_em = $em;
		$this->_session = $session;
	}

	/**
	 * get subscribe events
	 *
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [
			SecurityEvents::INTERACTIVE_LOGIN => 'changeSecurityToken',
		];
	}

	/**
	 * change token from user
	 *
	 * @param InteractiveLoginEvent $event
	 * @return void
	 */
	public function changeSecurityToken(InteractiveLoginEvent $event)
	{
		$user = $event->getAuthenticationToken()->getUser();
		$token = $event->getRequest()->get('_csrf_token');
		$password = $event->getRequest()->request->get('_password');

		$helper = new Data();
		$token = $helper->generateToken($user, $password, $token);
		$user->setToken($token);

		$this->_session->set('user_token', $token);
		$this->_session->save();

		$this->_em->persist($user);
		$this->_em->flush();
	}


}