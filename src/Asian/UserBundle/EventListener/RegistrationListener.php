<?php
/**
 * Created by PhpStorm.
 * User: marmelad
 * Date: 26.01.2017
 * Time: 10:44
 */

namespace Asian\UserBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class RegistrationListener implements EventSubscriberInterface
{
	protected $em;

	public function __construct(\Doctrine\ORM\EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents()
	{
		return [
			FOSUserEvents::REGISTRATION_SUCCESS => 'addedApiAccount',
		];
	}

	public function addedApiAccount(FormEvent $event)
	{
		$user = $event->getForm()->getData();

		$apiUser = $this->em->getRepository('AsianUserBundle:ApiUser')->getFirstElement();

		$user->setApiUser($apiUser);
	}
}