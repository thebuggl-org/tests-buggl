<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Buggl\MainBundle\Entity\EGuide;
use Buggl\MainBundle\Event\EguideEvent;

class UpdateGuideDateUpdatedCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:update:eguide-date-updated')
            ->setDescription('Updates the new field dateUpdated of eguides.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    	$output->writeln('updating');

    	$entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    	$repository = $entityManager->getRepository('BugglMainBundle:EGuide');

		$guides = $repository->findAll();

		foreach ($guides as $guide) {
			$output->writeln('updating guide '.$guide->getId());
			
			$guide->setDateUpdated($guide->getDateCreated());
			$entityManager->persist($guide);
		}
		$entityManager->flush();

		$output->writeln('done!');
    }
}