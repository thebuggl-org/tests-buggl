<?php

namespace Buggl\MainBundle\EventListener;

use Buggl\MainBundle\Event\EguideEvent;
use Buggl\MainBundle\Entity\EGuideActivity;
use Buggl\MainBundle\Entity\EGuideToEGuideActivity;

class EguideEventsListener
{
	private $entityManager;
	
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}
	
	public function handleActivity(EguideEvent $event)
	{
		$eguide = $event->getEguide();
		
		$eguideActivity = new EGuideActivity();
		$eguideActivity->setDescription($this->getAppropriateDescription($event->getEventType()));
		$eguideActivity->setType($event->getEventType());
		$eguideActivity->setDateAdded(new \DateTime(date('Y-m-d H:i:s')));
		$this->entityManager->persist($eguideActivity);
		
		$eguideToEguideActivity = new EGuideToEGuideActivity();
		$eguideToEguideActivity->setEGuide($eguide);
		$eguideToEguideActivity->setEGuideActivity($eguideActivity);
		$this->entityManager->persist($eguideToEguideActivity);
		
		$this->entityManager->flush();
	}
	
	private function getAppropriateDescription($eventType)
	{
		// implement logic to accomodate variations on descriptions based on event type
		return "{title}";
	}
}