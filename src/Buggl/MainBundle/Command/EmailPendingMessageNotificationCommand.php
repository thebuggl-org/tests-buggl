<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EmailPendingMessageNotificationCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:email_message_notification')
            ->setDescription('Send Message Notifications')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
		$constants = $this->getContainer()->get('buggl_main.constants');

		$messageService = $this->getContainer()->get('buggl_main.message_service');
		$messages = $this->getContainer()->get('doctrine.orm.entity_manager')
										   ->getRepository('BugglMainBundle:MessageToUser')
										   ->findByNotificationStatus($constants->get('MSG_NOTIF_PENDING'));

		foreach($messages as $message){
			$messageService->sendNotification($message);
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