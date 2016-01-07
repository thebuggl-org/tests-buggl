<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Buggl\MainBundle\Entity\Country;

class UpdateGuidePlainTitlesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:update:guidePlainTitles')
            ->setDescription('Updates all plain titles of guides with proper formatting.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    	$output->writeln('updating plain titles');
    	$entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
		$slugifier = $this->getContainer()->get('buggl_main.slugifier');
		
    	$repository = $entityManager->getRepository('BugglMainBundle:EGuide');

		$guides = $repository->findAll();
		
		foreach ($guides as $guide) {
			$output->writeln('updating guide '.$guide->getId());
			
			// update plain title
			$title = $guide->getTitle();
			$title = str_replace('&nbsp;'," ",$title);
			$plainTitle = trim(html_entity_decode(strip_tags($title), ENT_QUOTES, 'UTF-8'));
			$output->writeln($plainTitle);
			$guide->setPlainTitle($plainTitle);
			
			// update slug
			$slug = $slugifier->format( $plainTitle )->getSlug()."-".$guide->getId();
			$guide->setSlug($slug);
			
			$entityManager->persist($guide);
		}

		$entityManager->flush();

		$output->writeln('done!');
    }
}