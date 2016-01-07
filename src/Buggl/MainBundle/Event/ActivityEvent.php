<?php

namespace Buggl\MainBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Buggl\MainBundle\Entity\EGuide;

class ActivityEvent extends Event
{
	protected $object;
	protected $actor;
	protected $receiver;
	protected $eventType;
	
	public function __construct($object,$actor,$receiver,$eventType)
	{
		$this->object = $object;
		$this->actor = $actor;
		$this->receiver = $receiver;
		$this->eventType = $eventType;
	}
	
	public function getObject()
	{
		return $this->object;
	}
	
	public function getActor()
	{
		return $this->actor;
	}
	
	public function getReceiver()
	{
		return $this->receiver;
	}
	
	public function getEventType()
	{
		return $this->eventType;
	}
}