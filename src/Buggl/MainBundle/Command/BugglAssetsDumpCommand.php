<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BugglAssetsDumpCommand extends ContainerAwareCommand
{
	public function configure()
    {
        $this
            ->setName('buggl:assets:awsdump')
            ->setDescription('Dumps all public assets to aws cloudfront.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    	ini_set('max_execution_time', 0);
    	
    	$output->writeln("STARTING SCRIPT . . . ");
    	$this->getContainer()->get('buggl_aws.asset_dump')->execute();
    	$output->writeln("");
		$output->writeln("DONE DUMPING ASSETS TO S3!");
		$output->writeln("");
    }
}