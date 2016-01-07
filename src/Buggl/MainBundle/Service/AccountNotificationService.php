<?php

namespace Buggl\MainBundle\Service;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

use Buggl\MainBundle\Entity\LocalAuthor;
use Buggl\MainBundle\Entity\Profile;
use Buggl\MainBundle\Entity\Location;
use Buggl\MainBundle\Entity\SocialMedia;
use Buggl\MainBundle\Entity\EmailVerification;

class AccountNotificationService
{
	private $mailer;
	private $templating;
	private $slugifier;
	private $entityManager;
	private $constants;
	private $router;
	private $siteUrl;


	public function __construct($mailer, $templating, $slugifier, $entityManager, $constants, $router)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->slugifier = $slugifier;
		$this->entityManager = $entityManager;
		$this->constants = $constants;
		$this->router = $router;
		$this->siteUrl = $this->constants->get('site_url');
	}

	public function sendAccountInactivityNotification($user,$guides)
	{
		$message = \Swift_Message::newInstance();
		$message->setSubject('Buggl wants to know where you’ve been!');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($user->getEmail());

		$user->setLastInactiveNotificationSent(new \DateTime(date('Y-m-d H:i:s')));
		$this->entityManager->persist($user);
		$this->entityManager->flush();
		
		$emailData = array(
			'user' => $user,
			'guides' => $guides,
			'siteUrl' => $this->siteUrl
		);
		//$link = $this->siteUrl
		$message->setBody($this->templating->render('BugglMainBundle:Notification:inactivityNotification.html.twig',$emailData), 'text/html');

		$this->mailer->send($message);
	}
}