<?php

namespace Buggl\MainBundle\Service;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Process\ProcessBuilder;

class EGuideRequestEmailService
{
	protected $mailer;
	protected $templating;
	protected $constants;
	protected $entityManager;
	protected $router;
	private $encoder;

	public function __construct($mailer, $templating, $constants, $entityManager, $router)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->constants = $constants;
		$this->entityManager = $entityManager;
		$this->router = $router;

		$this->encoder = new MessageDigestPasswordEncoder();
	}

	public function sendEGuideRequest($eguidereq,$email,$useremail,$userip,$destination,$duration,$visitcountry)
	{
		$id=$eguidereq->getId();
		$query = unserialize(file_get_contents('http://ip-api.com/php/'.$userip));
		//print_r($query); die;
if($query && $query['status'] == 'success') {
  $now= $query['timezone']; 

} else {
  $now=$userip;
}
		
		$emailData = array(
			'useremail'=>$useremail,
			'now'=>$now,
			'visitcountry'=>$visitcountry,
			'eguiderequests' => $eguidereq,
			'destination'=>$destination,
			'duration'=>$duration,
			'link' => $this->router->generate('add_travel_guide_info',array('request'=>$id),true)
		);
		
		if ( $email != '' ) {
			$message2 = \Swift_Message::newInstance();
			$message2->setSubject('Someone on Buggl wants your help');
			$message2->setFrom($this->constants->get('BUGGL_EMAIL'));
			$message2->setTo($email);
			$message2->setBody($this->templating->render('BugglMainBundle:Notification:eguideRequestNotification.html.twig', $emailData), 'text/html');

			$this->mailer->send($message2);
		}
			
		//send to traveler
		$message = \Swift_Message::newInstance();
		$message->setSubject('Your custom travel guide request from Buggl');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($eguidereq->getUser()->getEmail());
		$message->setBody($this->templating->render('BugglMainBundle:Notification:eguideRequestConfirmation.html.twig'), 'text/html');

		$result = $this->mailer->send($message);
	}
	public function sendEGuideReply($mail,$email)
	{ 
		//$id=$mail->getEmail(); die;
		 $reciever= $mail[0]->getUser()->getEmail();
		 $id=$mail[0]->getId();
		$emailData = array(
			'eguiderequests' => $mail,
		 	'threadId'=>$id,
			'link' => $this->router->generate('local_author_messages_eguide_thread',array('type'=>'guide_request','messageThreadToUserId'=> $id),true)
		);
		
		if ( $reciever != '' ) {
			$message2 = \Swift_Message::newInstance();
			$message2->setSubject('Itinerary request');
			$message2->setFrom($this->constants->get('BUGGL_EMAIL'));
			$message2->setTo($reciever);
			$message2->setBody($this->templating->render('BugglMainBundle:Notification:eguideRequestEmailNotification.html.twig', $emailData), 'text/html');

			$this->mailer->send($message2);
		}
			
		//send to traveler
		/*$message = \Swift_Message::newInstance();
		$message->setSubject('Your custom travel guide request from Buggl');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($eguidereq->getUser()->getEmail());
		$message->setBody($this->templating->render('BugglMainBundle:Notification:eguideRequestConfirmation.html.twig'), 'text/html');

		$result = $this->mailer->send($message);*/
	}
}