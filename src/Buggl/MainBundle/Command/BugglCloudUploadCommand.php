<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BugglCloudUploadCommand extends ContainerAwareCommand
{

	public function configure()
    {
        $this
            ->setName('buggl:cloud:upload')
            ->setDescription('Uploads specified folder/file to amazon s3 bucket.')
            ->addArgument('pathToFolder', InputArgument::REQUIRED, 'The path to the folder/file to upload/update!')
            ->addArgument('pathToUpload', InputArgument::REQUIRED, 'The path to where the folder/file will be uploaded!')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    	ini_set('max_execution_time', 0);

        $output->writeln("STARTING SCRIPT . . . ");

        $pathToFolder = $input->getArgument('pathToFolder');
        $pathToUpload = $input->getArgument('pathToUpload');
        $this->getContainer()->get('buggl_aws.wrapper')->uploadDirectory($pathToFolder, $pathToUpload);

        $output->writeln("");
        $output->writeln("DONE!");
        $output->writeln("Written by: Nash Lesigon <nash.lesigon@goabroad.com>");
        $output->writeln("");
    }

}