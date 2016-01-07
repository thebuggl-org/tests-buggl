<?php

namespace Buggl\MainBundle\EventListener;

use Buggl\MainBundle\Event\ActivityEvent;
use Buggl\MainBundle\Entity\Activity;

class ActivityListener
{
	private $entityManager;
	
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}
	
	public function handleActivity(ActivityEvent $event)
	{
		$object = $event->getObject();
		$activity = new Activity();
		$activity->setObjectId(is_null($event->getObject()) ? null : $event->getObject()->getId());
		$activity->setActor($event->getActor());
		$activity->setReceiver($event->getReceiver());
		$activity->setType($this->entityManager->getRepository('BugglMainBundle:ActivityType')->findOneById($event->getEventType()));
		$activity->setDateAdded(new \DateTime(date('Y-m-d H:i:s')));
		$activity->setShowInAdmin($this->showInAdmin($event->getEventType()));
		
		$this->entityManager->persist($activity);
		$this->entityManager->flush();
	}
	
	private function showInAdmin($type)
	{
		$adminEvents = array(1,3);
		
		if(in_array($type,$adminEvents)){
			return 1;
		}
		
		return 0;
	}
}