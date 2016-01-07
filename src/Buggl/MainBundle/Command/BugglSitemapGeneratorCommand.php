<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BugglSitemapGeneratorCommand extends ContainerAwareCommand
{
	public function configure()
    {
        $this
            ->setName('buggl:generate:sitemap')
            ->setDescription('A script that would generate the sitemap.')
            ->addArgument('domain', InputArgument::OPTIONAL, 'The domain name.', 'www.buggl.com')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
    	ini_set('max_execution_time', 0);

        $domain = $input->getArgument('domain');
        $output->writeln("Generating sitemap for : $domain");
    	$builder = $this->getContainer()->get('buggl_main.sitemap_builder');
        $builder->execute($domain);
        $output->writeln("");
        $output->writeln("Sitemap complete.");
        $output->writeln("Written by: Nash Lesigon <nash.lesigon@goabroad.com>");
        $output->writeln("");
    }
}