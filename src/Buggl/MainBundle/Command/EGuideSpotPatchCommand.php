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

class EGuideSpotPatchCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('buggl:eguide_spot_patch')
            ->setDescription('A patch for the new implementation of eguide spots.')
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
            // $guide = $this->_em->getRepository('BugglMainBundle:EGuide')->findOneById(1);
            // fetch guide local spots
            $localSecrets = $this->_em->getRepository('BugglMainBundle:EGuideToSpot')->fetchLocalSecrets($guide->getID());
            $this->_output->writeln($guide->getID() . " : " .count($localSecrets));
            if(count($localSecrets))
            {
                foreach($localSecrets as $ls)
                {
                    $sd = $this->_em->getRepository('BugglMainBundle:SpotDetail')->findOneBy( array('spot' => $ls->getSpot(), 'localAuthor' => $guide->getLocalAuthor()) );
                    if($sd)
                    {
                        $this->addToLocalSecret($guide, $sd);
                    }
                }
            }

            // fetch guide itinerary spots
            $itinerarySpots = $this->_em->getRepository('BugglMainBundle:EGuideToSpot')->fetchItinerarySpots($guide->getID());
            $output->writeln($guide->getID() . " : " .count($itinerarySpots));
            if(count($itinerarySpots))
            {
                foreach($itinerarySpots as $iSpot)
                {
                    $sd = $this->_em->getRepository('BugglMainBundle:SpotDetail')->findOneBy( array('spot' => $iSpot->getSpot(), 'localAuthor' => $guide->getLocalAuthor()) );
                    if($sd)
                    {
                        // just double check and try to add to local secret in
                        $this->addToLocalSecret($guide, $sd);
                        $this->addToItinerary($guide, $sd, $iSpot);
                    }
                }
            }
            
        }
    }

    private function addToLocalSecret($eguide, $spotDetail)
    {
        
        // add to EGuideToSpotDetail
        $obj = $this->_em->getRepository('BugglMainBundle:EGuideToSpotDetail')->findOneBy(array('eGuide' => $eguide, 'spotDetail' => $spotDetail));
        if(!$obj)
        {
            $lastOrder = $this->_em->getRepository('BugglMainBundle:EGuideToSpotDetail')->getLastOrderByGuide($eguide);
            $order = ($lastOrder) ? (int)$lastOrder[0]->getOrder() + 1 : 1;

            $obj = new EGuideToSpotDetail();
            $obj->setEGuide($eguide);
            $obj->setSpotDetail($spotDetail);
            $obj->setOrder($order);
            $obj->setDateAdded($spotDetail->getDateAdded());

            $this->_em->persist($obj);
            $this->_em->flush();
        }
    }

    private function addToItinerary($eguide, $spotDetail, $egts)
    {
        $itinerary = $this->_em->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $egts->getDayNum()));
        if(!$itinerary)
        {
            $itinerary = new Itinerary();
            $itinerary->setEGuide($eguide);
            $itinerary->setDayNum($egts->getDayNum());

            $this->_em->persist($itinerary);
            $this->_em->flush();
        }

        $obj = $this->_em->getRepository('BugglMainBundle:ItineraryToSpotDetail')->findOneBy(array('itinerary' => $itinerary, 'spotDetail' => $spotDetail));
        if(!$obj)
        {
            $lastOrder = $this->_em->getRepository('BugglMainBundle:ItineraryToSpotDetail')->getLastOrderByItineraryAndPeriodOfDay($itinerary, $egts->getPeriodOfDay());
            $order = ($lastOrder) ? (int)$lastOrder[0]->getOrder() + 1 : 1;

            $obj = new ItineraryToSpotDetail();
            $obj->setItinerary($itinerary);
            $obj->setSpotDetail($spotDetail);
            $obj->setPeriodOfDay($egts->getPeriodOfDay());
            $obj->setOrder($order);
            $obj->setDateAdded($spotDetail->getDateAdded());

            $this->_em->persist($obj);
            $this->_em->flush();
        }
    }
}