<?php

namespace Buggl\MainBundle\EventListener;

use Buggl\MainBundle\Event\LocalReferenceEvent;

class LocalReferenceListener
{
	protected $localReferenceService;
	
	public function __construct($localReferenceService)
	{
		$this->localReferenceService = $localReferenceService;
	}
	
	public function updateLocalReferenceRequestStatus(LocalReferenceEvent $event)
	{
		$localReference = $event->getLocalReference();
		
		$this->localReferenceService->updateLocalReferenceRequestStatus($localReference);
	}	
}