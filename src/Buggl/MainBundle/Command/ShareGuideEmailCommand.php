<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ShareGuideEmailCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:guide_share_email')
            ->setDescription('Send Guide Share Email')
			->addArgument('recipients', InputArgument::REQUIRED, 'Who you want to send email to?')
			->addArgument('guideId', InputArgument::REQUIRED, 'Id of guide you want to share')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
		$constants = $this->getContainer()->get('buggl_main.constants');
		$router = $this->getContainer()->get('router');
		
		$recipientsString = $input->getArgument('recipients');
		$guideId = $input->getArgument('guideId');
		$guide = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('BugglMainBundle:EGuide')->findOneById($guideId);
		
		$photo = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('BugglMainBundle:EGuidePhoto')->findOneActiveByEguideAndType($guide,1);
		if(is_null($photo)){
			$photoUrl = '/bundles/bugglmain/images/custom/cover-guide.jpg';
		}
		else{
			$photoUrl = $photo->getPhoto();
		}
		$guideInfo = array(
			'guide' => $guide,
			'photoUrl' => $photoUrl,
			'guideUrl' => $router->generate('buggl_eguide_overview',array('slug'=>$guide->getSlug())),
			'authorUrl' => $router->generate('local_author_profile',array('slug'=>$guide->getLocalAuthor()->getSlug()))
		);
		
		$guideShareService = $this->getContainer()->get('buggl_main.guide_share_service');
		
		$recipients = explode(',',$recipientsString);
		foreach($recipients as $recipient){
			if(trim($recipient) == '')
				continue;
			
			$guideShareService->sendNotification($recipient,$guideInfo);
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