<?php 

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeImagesHostCommand extends ContainerAwareCommand 
{
	public function configure()
    {
        $this
            ->setName('buggl:images:hostupdate')
            ->setDescription('A script that would update the host in the url of photos.')
        ;
    }
	
    public function execute(InputInterface $input, OutputInterface $output)
    {
		$output->writeln("STARTING SCRIPT . . . ");

    	$em = $this->getContainer()->get('doctrine.orm.entity_manager');
		
		$output->writeln("Retrieving guide photos . . . ");
		$eguidePhotos = $em->getRepository('BugglMainBundle:EGuidePhoto')->findAll();
		$iObj = null;
		$photoUrl = null;
		foreach($eguidePhotos as $iObj)
		{	
			$photoUrl = str_replace("www.buggl.com", "buggl.s3.amazonaws.com", $iObj->getPhoto() );
			$output->write( " . " );
			
			$iObj->setPhoto( $photoUrl );
			$em->persist($iObj);
			$em->flush();
		}
		$output->writeln("DONE updating guide photos . . . ");
		
		$output->writeln("Retrieving spot detail photos . . . ");
		$spotDetails = $em->getRepository('BugglMainBundle:SpotDetail')->findAll();
		$iObj = null;
		$photoUrl = null;
		foreach($spotDetails as $iObj)
		{	
			$photoUrl = str_replace("www.buggl.com", "buggl.s3.amazonaws.com", $iObj->getPhoto() );
			$output->write( " . " );
			
			$iObj->setPhoto( $photoUrl );
			$em->persist($iObj);
			$em->flush();
		}
		$output->writeln("DONE updating spot detail photos . . . ");
		
    	$output->writeln("DONE RUNNING SCRIPT!");
    	$output->writeln("Written by: Nash Lesigon <nash.lesigon@goabroad.com>");
		
		
	}
}
