<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Buggl\MainBundle\Entity\Country;

class LoadCountriesCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:load:countries')
            ->setDescription('Adds Countries to Database')
            // ->addArgument('emailType', InputArgument::REQUIRED, 'What email you want to send?')
			// ->addArgument('recipients', InputArgument::REQUIRED, 'Who you want to send email to?')
			->addArgument('excluded', InputArgument::OPTIONAL, 'Excluded Countries')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    	$output->writeln('loading countries...');
    	$entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
    	$repository = $entityManager->getRepository('BugglMainBundle:Country');
    	$status = $this->getContainer()->get('buggl_main.constants')->get('APPROVED_COUNTRY');

		$countries = file_get_contents((__DIR__.'/../../../../countries.json'));
		$countries = json_decode($countries,true);

		$excluded = explode(':',$input->getArgument('excluded'));

		foreach ($countries as $country) {
			if (!(in_array($country, $excluded) || $repository->findByCountryName($country))) {
				$object = new Country();
				$object->setName($country);
				$object->setStatus($status);

				$entityManager->persist($object);
			}
		}

		$entityManager->flush();

		$output->writeln('done!');
    }
}