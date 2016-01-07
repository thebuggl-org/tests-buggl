<?php

namespace Buggl\MainBundle\EventListener;

use Buggl\MainBundle\Event\PasswordResetEvent;

class PasswordResetListener
{
	protected $mailer;
	protected $templating;
	protected $entityManager;
	protected $constants;
	protected $router;

	public function __construct($mailer, $templating, $entityManager, $constants, $router)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->entityManager = $entityManager;
		$this->constants = $constants;
		$this->router = $router;
	}

	public function sendPasswordResetEmail(PasswordResetEvent $event)
	{
		$passwordResetInfo = $event->getPasswordResetInfo();

		$user = $passwordResetInfo->getUser();

		$resetLink = $this->router->generate('buggl_password_reset',array('token' => urlencode($passwordResetInfo->getToken())),true);

		$message = \Swift_Message::newInstance();
		$message->setSubject('Reset your Buggl password');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($event->getEmail());
		$message->setBody($this->templating->render('BugglMainBundle:Notification:passwordResetNotification.html.twig',
			array(
				'resetLink' => $resetLink,
				'name' => $user->getName()
			)), 'text/html');
		$this->mailer->send($message);
	}
}