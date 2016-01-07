<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Buggl\MainBundle\Entity\Country;

class UpdateStreetCreditsCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:update:street_credits')
            ->setDescription('Update Street Credits')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    	$output->writeln('updating street credits...');
    	$entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    	$repository = $entityManager->getRepository('BugglMainBundle:LocalAuthor');
		$constants = $this->getContainer()->get('buggl_main.constants');
		$localAuthors = $repository->findAllLocalAuthor($constants->get('allowed_user'));
		$streetCreditService = $this->getContainer()->get('buggl_main.street_credit');
		
		foreach($localAuthors as $localAuthor){
			$profile = $localAuthor->getProfile();
			$output->writeln('updating author : '.$localAuthor->getName());
			
			$streetCreditService->updateGuideStatus($localAuthor);
			$streetCreditService->updateProfileStatus($localAuthor);
			$streetCreditService->updateVouchStatus($localAuthor);
			$streetCreditService->updateInviteAuthorStatus($localAuthor);
			$streetCreditService->updateConnectStatus($localAuthor);
			
			$entityManager->flush();
		}
    	

		$output->writeln('done!');
    }
}