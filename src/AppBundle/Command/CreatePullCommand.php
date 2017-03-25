<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Command\Helper\Data;

class CreatePullCommand extends ContainerAwareCommand
{
	private $_em;
	private $_apiUser;
	private $_kernel;

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$helper = new Data();
		$this->_em =  $this->getContainer()->get('doctrine.orm.entity_manager');
		$this->_kernel = $this->getContainer()->get('kernel');

		$this->_apiUser = $this->_em->getRepository('AsianUserBundle:ApiUser')->getFirstElement();

		$helper->isLoggedIn($this->_apiUser, $this->_kernel->getLogDir());
	}
	protected function configure()
	{
		$this->setName('app:create-pull')
			->setDescription('Create json file witch getFeeds')
			->setHelp('This command create json file into folder /var/pool/');
	}

	private function isLoggedIn()
	{

	}
}