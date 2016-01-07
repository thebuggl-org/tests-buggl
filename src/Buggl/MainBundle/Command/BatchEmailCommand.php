<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BatchEmailCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:bulk_email')
            ->setDescription('Send Bulk Email')
            ->addArgument('emailType', InputArgument::REQUIRED, 'What email you want to send?')
			->addArgument('recipients', InputArgument::REQUIRED, 'Who you want to send email to?')
			->addArgument('localAuthorId', InputArgument::OPTIONAL, '(Optional LocalAuthorId for specific implementation)')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
		$emailType = $input->getArgument('emailType');
        $recipientsString = $input->getArgument('recipients');

		$constants = $this->getContainer()->get('buggl_main.constants');
		if($constants->get('LOCAL_REF_BULK_MAIL') == $emailType){
			$localReferenceService = $this->getContainer()->get('buggl_main.local_reference_service');
			$recipients = explode(',',$recipientsString);

			$entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
			$localAuthor = $entityManager->getRepository('BugglMainBundle:LocalAuthor')->findOneBy(array('id'=>$input->getArgument('localAuthorId')));
			$localReferenceService->batchSendLocalReferenceRequest($recipients,$localAuthor);

			/*
			 * NOTE: this is temp fix refer to
			 * http://stackoverflow.com/questions/13122096/unable-to-send-e-mail-from-within-custom-symfony2-command-but-can-from-elsewhere
			 * fix or apply more elegant solution ASAP
			 */
			$transport = $this->getContainer()->get('mailer')->getTransport();
			if (!$transport instanceof \Swift_Transport_SpoolTransport) {
			    return;
			}

			$spool = $transport->getSpool();
			if (!$spool instanceof \Swift_MemorySpool) {
			    return;
			}

			$spool->flushQueue($this->getContainer()->get('swiftmailer.transport.real'));
			/*
			 * end of fix
			 */
		}
    }
}