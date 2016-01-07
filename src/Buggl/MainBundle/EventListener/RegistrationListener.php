<?php

namespace Buggl\MainBundle\EventListener;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Buggl\MainBundle\Event\RegistrationEvent;

class RegistrationListener
{
	private $registrationService;

	public function __construct($registrationService)
	{
		$this->registrationService = $registrationService;
	}

	public function mailPostRegister(RegistrationEvent $event)
	{
		$localAuthor = $event->getLocalAuthor();
		if($localAuthor->getEmailVerified() == 0){ //maybe use BugglConstant here
			$this->registrationService->sendConfirmationEmail($localAuthor,$event->getRedirect());
		}
		else{
			$this->registrationService->sendSignupViaFbConfirmationEmail($localAuthor);
		}
	}

	public function update(RegistrationEvent $event)
	{
		$localAuthor = $event->getLocalAuthor();
		$this->registrationService->createSlug($localAuthor);
	}

	public function sendMail(RegistrationEvent $event)
	{
		$localAuthor = $event->getLocalAuthor();
		$this->registrationService->notifyVerifiedEmail($localAuthor);
	}
}