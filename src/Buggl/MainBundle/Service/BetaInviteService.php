<?php

namespace Buggl\MainBundle\Service;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

use Buggl\MainBundle\Entity\LocalAuthor;
use Buggl\MainBundle\Entity\Location;
use Buggl\MainBundle\Entity\SocialMedia;
use Buggl\MainBundle\Entity\EmailVerification;
use Buggl\MainBundle\Entity\BetaInvite;

class BetaInviteService
{
	private $mailer;
	private $templating;
	private $entityManager;
	private $constants;
	private $router;
	private $encoder;
	private $siteUrl;


	public function __construct($mailer, $templating, $entityManager, $constants, $router)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->entityManager = $entityManager;
		$this->constants = $constants;
		$this->router = $router;

		$this->encoder = new MessageDigestPasswordEncoder();
		$this->siteUrl = $this->constants->get('site_url');
	}


	public function saveBetaInvite($data,$user)
	{
		$token = substr($this->encoder->encodePassword($data['email'].' : '.strtotime(date("Y-m-d H:i:s")),''),0,300);
		$invite = new BetaInvite();
		$invite->setEmail($data['email']);
		$invite->setToken($token);
		$invite->setTokenExpiration(new \DateTime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +3 days"));
		$invite->setDateInvited(new \DateTime(date('Y-m-d H:i:s')));
		$invite->setStatus($this->constants->get('BETA_INVITE_PENDING'));
		$invite->setSender($user);

		$this->entityManager->persist($invite);
		$this->entityManager->flush();

		return $invite;
	}

	public function sendBetaInviteEmail($invite,$resend=false)
	{
		$message = \Swift_Message::newInstance();
		$message->setSubject('Buggl Beta Invitation');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($invite->getEmail());

		if($resend){
			$token = substr($this->encoder->encodePassword($invite->getEmail().' : '.strtotime(date("Y-m-d H:i:s")),''),0,300);
			$invite->setToken($token);
			$invite->setTokenExpiration(new \DateTime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +3 days"));
			$invite->setDateInvited(new \DateTime(date('Y-m-d H:i:s')));
		}
		
		$invite->setStatus($this->constants->get('BETA_INVITE_SENT'));

		$this->entityManager->persist($invite);
		$this->entityManager->flush();

		$link = $this->router->generate('registration',array(),true);
		$message->setBody($this->templating->render('BugglMainBundle:Notification:betaInvitation.html.twig',array('invite' => $invite,'link' => $link)), 'text/html');

		$this->mailer->send($message);
	}
	
	public function saveInvite($email,$user)
	{
		$token = substr($this->encoder->encodePassword($email.' : '.strtotime(date("Y-m-d H:i:s")),''),0,300);
		$token = str_replace('==','',$token);
		$invite = new BetaInvite();
		$invite->setEmail($email);
		$invite->setToken($token);
		$invite->setTokenExpiration(new \DateTime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +3 days"));
		$invite->setDateInvited(new \DateTime(date('Y-m-d H:i:s')));
		$invite->setStatus($this->constants->get('BETA_INVITE_PENDING'));
		$invite->setSender($user);

		$this->entityManager->persist($invite);
		$this->entityManager->flush();

		return $invite;
	}
	
	public function sendInviteEmail($invite,$resend=false)
	{
		$link2 = null;
		$subject = 'You’ve been invited to join Buggl';
		$isAdmin = true;
		if(strpos(get_class($invite->getSender()),'AdminUsers') === false){
			$link2 = $this->siteUrl.$this->router->generate('local_author_profile',array('slug' => $invite->getSender()->getSlug()));
			$subject = 'You’ve been invited by '.$invite->getSender()->getName().' to Buggl';
			$isAdmin = false;
		}
		
		$message = \Swift_Message::newInstance();
		$message->setSubject($subject);
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($invite->getEmail());

		if($resend){
			$token = substr($this->encoder->encodePassword($invite->getEmail().' : '.strtotime(date("Y-m-d H:i:s")),''),0,300);
			$token = str_replace('==','',$token);
			$invite->setToken($token);
			$invite->setTokenExpiration(new \DateTime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +3 days"));
			$invite->setDateInvited(new \DateTime(date('Y-m-d H:i:s')));
		}
		
		$invite->setStatus($this->constants->get('BETA_INVITE_SENT'));

		$this->entityManager->persist($invite);
		$this->entityManager->flush();

		$link = $this->siteUrl.$this->router->generate('registration',array('ref' => $invite->getToken()));
		$message->setBody($this->templating->render('BugglMainBundle:Notification:invitationEmail.html.twig',array('invite' => $invite,'link' => $link,'link2' => $link2,'isAdmin' => $isAdmin)), 'text/html');

		return $this->mailer->send($message);
	}

	public function deleteBetaInvite($invite)
	{
		$this->entityManager->remove($invite);
		$this->entityManager->flush();
	}

	public function verifyInvitation($email,$token)
	{
		$invite = $this->entityManager->getRepository('BugglMainBundle:BetaInvite')->retrieveNonExpiredByEmailAndToken($email,$token);

		return !is_null($invite);
	}

	public function updateInviteStatus($email,$token,$status)
	{
		$invite = $this->entityManager->getRepository('BugglMainBundle:BetaInvite')->retrieveNonExpiredByEmailAndToken($email,$token);
		if(!is_null($invite)){
			$invite->setStatus($status);
			$this->entityManager->persist($invite);
			$this->entityManager->flush();
		}

		return $invite;
	}
	
	public function saveSuccessFullInvite($invitedAuthor,$token)
	{
		$invite = $this->entityManager->getRepository('BugglMainBundle:BetaInvite')->findOneByToken($token);
		
		if(!is_null($invite) && $invite->getStatus() != $this->constants->get('BETA_INVITE_ACCEPTED')){
			$invite->setStatus($this->constants->get('BETA_INVITE_ACCEPTED'));
			$this->entityManager->persist($invite);
				
			$successFullInvite = new \Buggl\MainBundle\Entity\LocalAuthorToInvite();
			$successFullInvite->setLocalAuthor($invite->getSender());
			$successFullInvite->setInvitedAuthor($invitedAuthor);
			$this->entityManager->persist($successFullInvite);
			
			$this->entityManager->flush();
			
			return $successFullInvite;
		}
		
		return null;
	}
}