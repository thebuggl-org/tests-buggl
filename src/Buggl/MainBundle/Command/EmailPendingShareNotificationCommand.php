<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EmailPendingShareNotificationCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:email_shares')
            ->setDescription('Send Share Emails')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
		$constants = $this->getContainer()->get('buggl_main.constants');

		$shareService = $this->getContainer()->get('buggl_main.share');
		$shares = $this->getContainer()->get('doctrine.orm.entity_manager')
									   ->getRepository('BugglMainBundle:BugglShare')
									   ->findByStatus($constants->get('SHARE_PENDING'));

		foreach($shares as $share){
			$output->writeln('sending '.$share->getEmail());
			$shareService->sendShareEmail($share);
		}

		$transport = $this->getContainer()->get('mailer')->getTransport();
		if (!$transport instanceof \Swift_Transport_SpoolTransport) {
		    return;
		}

		$spool = $transport->getSpool();
		if (!$spool instanceof \Swift_MemorySpool) {
		    return;
		}

		$spool->flushQueue($this->getContainer()->get('swiftmailer.transport.real'));
    }
}