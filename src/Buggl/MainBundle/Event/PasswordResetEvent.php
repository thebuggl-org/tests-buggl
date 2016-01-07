<?php

namespace Buggl\MainBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Buggl\MainBundle\Entity\EGuide;

class PasswordResetEvent extends Event
{
	protected $passwordResetInfo;
	protected $email;
	
	public function __construct($passwordResetInfo, $email)
	{
		$this->passwordResetInfo = $passwordResetInfo;
		$this->email = $email;
	}
	
	public function getPasswordResetInfo()
	{
		return $this->passwordResetInfo;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
}