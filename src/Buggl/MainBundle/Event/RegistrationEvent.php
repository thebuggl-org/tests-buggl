<?php

namespace Buggl\MainBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use Buggl\MainBundle\Entity\LocalAuthor;

class RegistrationEvent extends Event
{
	protected $localAuthor;
	protected $redirect;
	
	public function __construct(LocalAuthor $localAuthor, $redirect = null)
	{
		$this->localAuthor = $localAuthor;
		$this->redirect = $redirect;
	}
	
	public function getLocalAuthor()
	{
		return $this->localAuthor;
	}
	
	public function getRedirect()
	{
		return $this->redirect;
	}
}