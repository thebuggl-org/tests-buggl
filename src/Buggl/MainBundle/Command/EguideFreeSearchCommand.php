<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Buggl\MainBundle\Entity\EGuide;
use Buggl\MainBundle\Event\EguideEvent;

class EguideFreeSearchCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:update:free-search')
            ->setDescription('Updates all the free search contents')
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

			$event = new EguideEvent($guide,0);
    		$this->getContainer()->get('event_dispatcher')->dispatch('buggl.update_free_search',$event);
		}

		$output->writeln('done!');
    }
}