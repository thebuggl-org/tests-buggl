<?php

namespace Buggl\MainBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Buggl\MainBundle\Entity\EGuide;

class EguideEvent extends Event
{
	protected $eguide;
	protected $eventType;
	
	public function __construct($eguide,$eventType)
	{
		$this->eguide = $eguide;
		$this->eventType = $eventType;
	}
	
	public function getEguide()
	{
		return $this->eguide;
	}
	
	public function getEventType()
	{
		return $this->eventType;
	}
}