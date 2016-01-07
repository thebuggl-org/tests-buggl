<?php

namespace Buggl\MainBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use Buggl\MainBundle\Entity\LocalReference;

class LocalReferenceEvent extends Event
{
	protected $localReference;
	
	public function __construct(LocalReference $localReference)
	{
		$this->localReference = $localReference;
	}
	
	public function getLocalReference()
	{
		return $this->localReference;
	}
}