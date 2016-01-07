<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class EmailInactivityNotificationCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:email_inactive_notification')
            ->setDescription('Send Account Inactivity Notifications')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
		$constants = $this->getContainer()->get('buggl_main.constants');
		$router = $this->getContainer()->get('router');
		
		$guides = $this->getContainer()->get('doctrine.orm.entity_manager')
									   ->getRepository('BugglMainBundle:EGuide')
								       ->findAllFeatured($constants->get('published'));
		$guideInfo = array();
		foreach($guides as $guide){
			$photo = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('BugglMainBundle:EGuidePhoto')->findOneActiveByEguideAndType($guide,1);
		
			if(is_null($photo)){
				$photoUrl = '/bundles/bugglmain/images/custom/cover-guide.jpg';
			}
			else{
				$photoUrl = $photo->getPhoto();
			}
			$guideInfo[] = array(
				'guide' => $guide,
				'photoUrl' => $photoUrl,
				'guideUrl' => $router->generate('buggl_eguide_overview',array('slug'=>$guide->getSlug())),
				'authorUrl' => $router->generate('local_author_profile',array('slug'=>$guide->getLocalAuthor()->getSlug()))
			);
		}

		$accountService = $this->getContainer()->get('buggl_main.account_notification_service');
		/*$users = $this->getContainer()->get('doctrine.orm.entity_manager')
											  ->getRepository('BugglMainBundle:User')
											  ->findById(32);*/
		$users = $this->getContainer()->get('doctrine.orm.entity_manager')
									  ->getRepository('BugglMainBundle:User')
									  ->findInactiveLocals();
		$output->writeln('sending '.count($users).' emails');
		foreach($users as $user){
			if(strpos(get_class($user),'AdminUsers') !== false){
				continue;
			}
			$output->writeln('sending to...'.$user->getEmail());
			$accountService->sendAccountInactivityNotification($user,$guideInfo);
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