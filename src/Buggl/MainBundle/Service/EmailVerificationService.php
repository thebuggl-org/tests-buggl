<?php

namespace Buggl\MainBundle\Service;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

use Buggl\MainBundle\Entity\EmailVerification;

class EmailVerificationService
{
	private $mailer;
	private $templating;
	private $slugifier;
	private $entityManager;
	private $constants;
	private $router;

	private $encoder;


	public function __construct($mailer, $templating, $slugifier, $entityManager, $constants, $router)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->slugifier = $slugifier;
		$this->entityManager = $entityManager;
		$this->constants = $constants;
		$this->router = $router;

		$this->encoder = new MessageDigestPasswordEncoder();
	}

	public function requestEmailUpdate($newEmail, $user)
	{
		$emailVerification = $this->entityManager->getRepository('BugglMainBundle:EmailVerification')->findByUser($user,true);
		$emailVerification->setUser($user);
		$emailVerification->setEmail($newEmail);
		$emailVerification->setToken($this->generateToken($user));
		$emailVerification->setTokenExpiration(new \DateTime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +7 days"));
		$emailVerification->setStatus($this->constants->get('VERIFICATION_PENDING'));

		$this->entityManager->persist($emailVerification);
		$this->entityManager->flush();

		return $emailVerification;
	}

	public function sendRequestEmailUpdateConfirmation($emailVerification)
	{
		$message = \Swift_Message::newInstance();
		$message->setSubject('Buggl Registration');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($emailVerification->getUser()->getEmail());

		$token = $this->generateToken($emailVerification->getUser());

		$link = $this->router->generate('local_author_confirm_email_update',array('token' => urlencode($token)),true);
		$message->setBody($this->templating->render('BugglMainBundle:Notification:changeEmailAddressConfirmation.html.twig',array('emailVerification' => $emailVerification,'link' => $link)), 'text/html');

		$this->mailer->send($message);
	}

	public function cancelRequestEmailUpdate($emailVerification)
	{
		$emailVerification->setStatus($this->constants->get('VERIFICATION_CANCELED'));

		$this->entityManager->persist($emailVerification);
		$this->entityManager->flush();

		return $emailVerification;
	}

	public function verifyRequest($token, $user)
	{
		$emailVerification = $this->entityManager->getRepository('BugglMainBundle:EmailVerification')->findByUserAndToken($user,$token);
		if(!is_null($emailVerification) && $emailVerification->getStatus() == $this->constants->get('VERIFICATION_PENDING')){
			return $emailVerification;
		}

		return null;
	}

	public function updateVerifiedEmail($emailVerification)
	{
		$user = $emailVerification->getUser();

		$user->setEmail($emailVerification->getEmail());
		$user->setStatus($this->constants->get('EMAIL_VERIFIED'));
		$this->entityManager->persist($user);

		$emailVerification->setStatus($this->constants->get('VERIFICATION_APPROVED'));
		$this->entityManager->persist($emailVerification);

		$user = $emailVerification->getUser();
		$user->setEmailVerified(1);
		$this->entityManager->persist($user);

		$this->entityManager->flush();
	}

	private function generateToken($user)
	{
		//NOTE: TOKENS are generated through encoding using MessageDigestPasswordEncoder,
		// find a better way if necessary

		return $this->encoder->encodePassword($user->getEmail().' '.$user->getId(),'');
	}
}