<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EmailPendingLocalReferenceRequestCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:email_local_reference')
            ->setDescription('Send Local Reference Requests')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
		$constants = $this->getContainer()->get('buggl_main.constants');

		$localReferenceService = $this->getContainer()->get('buggl_main.local_reference_service');
		$references = $this->getContainer()->get('doctrine.orm.entity_manager')
										   ->getRepository('BugglMainBundle:LocalAuthorToLocalReference')
										   ->retrieveRequestsByStatus($constants->get('LOCAL_REF_PENDING'),null,true);

		foreach($references as $reference){
			$localReferenceService->sendLocalReferenceRequest($reference);
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