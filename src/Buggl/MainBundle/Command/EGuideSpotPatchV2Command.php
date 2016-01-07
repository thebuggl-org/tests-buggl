<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Buggl\MainBundle\Entity\Itinerary;
use Buggl\MainBundle\Entity\ItineraryToSpotDetail;
use Buggl\MainBundle\Entity\EGuideToSpotDetail;

class EGuideSpotPatchV2Command extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:eguide_spot_patch_v2')
            ->setDescription('A patch to fix the broken featured spot on the recent update.')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $this->_input = $input;
        $this->_output = $output;

        // GET ALL GUIDES
        $guides = $this->_em->getRepository('BugglMainBundle:EGuide')->findAll();
        foreach($guides as $guide)
        {
        	// fetch featured spots
        	$featured = $this->_em->getRepository('BugglMainBundle:EGuideToSpot')->findBy(array('eGuide' => $guide, 'isFeatured' => 1));
        	$this->_output->writeln($guide->getID() . " : " .count($featured));
        	foreach($featured as $iObj)
        	{
        		$sd = $this->_em->getRepository('BugglMainBundle:SpotDetail')->findOneBy( array('spot' => $iObj->getSpot(), 'localAuthor' => $guide->getLocalAuthor()) );
        		$obj = $this->_em->getRepository('BugglMainBundle:EGuideToSpotDetail')->findOneBy(array('eGuide' => $guide, 'spotDetail' => $sd));
        		if($obj)
        		{
        			$this->_output->writeln("set is featured : " .$obj->getId());
        			$obj->setIsFeatured(1);
        			$this->_em->persist($obj);
		            $this->_em->flush();
        		}
        	}
        }
    }
}