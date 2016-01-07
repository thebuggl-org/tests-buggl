<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Buggl\MainBundle\Entity\Country;

class UpdateEguideCategoriesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:update:eguide-categorynames')
            ->setDescription('Update Category names of eguide')
            // ->addArgument('emailType', InputArgument::REQUIRED, 'What email you want to send?')
			// ->addArgument('recipients', InputArgument::REQUIRED, 'Who you want to send email to?')
			// ->addArgument('excluded', InputArgument::OPTIONAL, 'Excluded Countries')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    	$output->writeln('updating eguide category names...');

    	$entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    	$repository = $entityManager->getRepository('BugglMainBundle:EGuide');
    	$eguides = $repository->findAll();

		foreach ($eguides as $guide) {
			$categories = array();

			$results = $guide->getCategories();

			foreach ($results as $each) {
				$categories[] = $each->getName();
			}

			$guide->setCategoryNames(implode(', ', $categories));
		}
		$entityManager->flush();

		$output->writeln('done!');
    }
}