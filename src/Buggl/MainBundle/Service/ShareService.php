<?php

namespace Buggl\MainBundle\Service;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Process\ProcessBuilder;

use Buggl\MainBundle\Entity\BugglShare;

class ShareService
{
	protected $mailer;
	protected $templating;
	protected $constants;
	protected $entityManager;
	protected $router;
	private $encoder;
	private $siteUrl;

	public function __construct($mailer, $templating, $constants, $entityManager, $router)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->constants = $constants;
		$this->entityManager = $entityManager;
		$this->router = $router;

		$this->encoder = new MessageDigestPasswordEncoder();
		$this->siteUrl = $this->constants->get('site_url');
	}
	
	public function saveShareInfo($email,$user)
	{
		if($user->getEmail() == $email){
			return null;
		}
		$token = substr($this->encoder->encodePassword($email.' : '.strtotime(date("Y-m-d H:i:s")),''),0,300);
		$token = str_replace('==','',$token);
		$share = new BugglShare();
		$share->setEmail($email);
		$share->setToken($token);
		$share->setStatus($this->constants->get('SHARE_PENDING'));
		$share->setSender($user);

		$this->entityManager->persist($share);
		$this->entityManager->flush();

		return $share;
	}
	
	public function sendShareEmail($share,$resend=false)
	{
		$message = \Swift_Message::newInstance();
		$message->setSubject('Buggl Invitation');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($share->getEmail());

		if($resend){
			$token = substr($this->encoder->encodePassword($share->getEmail().' : '.strtotime(date("Y-m-d H:i:s")),''),0,300);
			$token = str_replace('==','',$token);
			$share->setToken($token);
		}
		
		$share->setStatus($this->constants->get('SHARE_SENT'));

		$this->entityManager->persist($share);
		$this->entityManager->flush();

		$link = $this->siteUrl.$this->router->generate('buggl_homepage');
		$message->setBody($this->templating->render('BugglMainBundle:Notification:shareEmail.html.twig',array('share' => $share,'link' => $link)), 'text/html');

		$this->mailer->send($message);
	}
}