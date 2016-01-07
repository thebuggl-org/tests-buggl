<?php

namespace Buggl\MainBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Buggl\MainBundle\Entity\Itinerary;
// use Buggl\MainBundle\Entity\ItineraryToSpotDetail;
// use Buggl\MainBundle\Entity\EGuideToSpotDetail;

class EGuideRealDurationPatchCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:eguide_real_duration_patch')
            ->setDescription('A patch to update the real duration field of the guides.')
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
            // fetch and count guide itineraries
            $itineraries = $this->_em->getRepository('BugglMainBundle:Itinerary')->findBy(array('e_guide' => $guide));
            $this->_output->writeln("set is real duration : " .count($itineraries));
            $guide->setRealDuration(count($itineraries));
            $this->_em->persist($guide);
            $this->_em->flush();
            
        }
    }
}