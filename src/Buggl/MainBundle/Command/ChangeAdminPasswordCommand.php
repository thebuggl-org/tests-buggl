<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Buggl\MainBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class ChangeAdminPasswordCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:user:change')
            ->setDescription('Changes Admin Password')
            // ->addArgument('emailType', InputArgument::REQUIRED, 'What email you want to send?')
			// ->addArgument('recipients', InputArgument::REQUIRED, 'Who you want to send email to?')
			->addArgument('username', InputArgument::REQUIRED, 'admin username')
			->addArgument('password', InputArgument::REQUIRED, 'admin password')
			->addArgument('newPassword', InputArgument::REQUIRED, 'old password')
        ;


    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    	$output->writeln('checking');
    	$encoder = new MessageDigestPasswordEncoder();

    	$entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    	$result = $entityManager->getRepository('BugglMainBundle:AdminUsers')->findOneBy(
    					array(
    						'username'=>$input->getArgument('username'),
    						'password'=> $encoder->encodePassword($input->getArgument('password'),'')
    			   		));

    	if (!is_null($result)) {
    		$password = $encoder->encodePassword($input->getArgument('newPassword'),'');
    		$result->setPassword($password);
    		$entityManager->flush();
    		$output->writeln('password updated');
    	} else {
    		$output->writeln('failed!');
    	}

    }
}