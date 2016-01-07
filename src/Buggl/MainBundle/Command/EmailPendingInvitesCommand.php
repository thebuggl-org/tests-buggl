<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EmailPendingInvitesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:email_invites')
            ->setDescription('Send Invites')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
		$constants = $this->getContainer()->get('buggl_main.constants');

		$betaInviteService = $this->getContainer()->get('buggl_main.beta_invite_service');
		$invites = $this->getContainer()->get('doctrine.orm.entity_manager')
										   ->getRepository('BugglMainBundle:BetaInvite')
										   ->retrieveInvitesByStatus($constants->get('BETA_INVITE_PENDING'));

		foreach($invites as $invite){
			$output->writeln('sending '.$invite->getEmail());
			$betaInviteService->sendInviteEmail($invite);
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