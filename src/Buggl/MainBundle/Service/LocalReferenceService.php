<?php

namespace Buggl\MainBundle\Service;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Process\ProcessBuilder;

use Buggl\MainBundle\Entity\LocalAuthorToLocalReference;
use Buggl\MainBundle\Entity\LocalReference;

class LocalReferenceService
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
		$this->siteUrl = $this->constants->get('site_url');

		$this->encoder = new MessageDigestPasswordEncoder();
	}

	public function saveLocalReference($email, $localAuthor, $uniqueToken = 'default', $name='')
	{
		$localAuthorToLocalReference = $this->entityManager->getRepository('BugglMainBundle:LocalAuthorToLocalReference')->retrieveOneByLocalAuthorAndEmail($localAuthor,$email,true);
		// if non existent, find one expired
		if(is_null($localAuthorToLocalReference))
			$localAuthorToLocalReference = $this->entityManager->getRepository('BugglMainBundle:LocalAuthorToLocalReference')->retrieveOneUnresponded($localAuthor);

		$localReference = is_null($localAuthorToLocalReference) ? new LocalReference() : $localAuthorToLocalReference->getLocalReference();
		$localReference->setName($name == 'No Name' ? '' : $name);
		$localReference->setReferenceEmail($email);
		$localReference->setIsFeatured(false);
		$localReference->setDateUpdated(new \DateTime(date("Y-m-d H:i:s")));
		$this->entityManager->persist($localReference);

		$token = substr($this->encoder->encodePassword($email.' : '.date("Y-m-d H:i:s").' : '.$localAuthor->getId(),''),0,100);

		// if still null, create new
		if(is_null($localAuthorToLocalReference))
			$localAuthorToLocalReference = new LocalAuthorToLocalReference();

		$localAuthorToLocalReference->setLocalAuthor($localAuthor);
		$localAuthorToLocalReference->setLocalReference($localReference);
		$localAuthorToLocalReference->setToken($token);
		$localAuthorToLocalReference->setTokenExpiration(new \DateTime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +7 days"));
		$localAuthorToLocalReference->setStatus(0);
		$this->entityManager->persist($localAuthorToLocalReference);

		$this->entityManager->flush();

		return $localAuthorToLocalReference;
	}

	public function updateLocalReference($localReference)
	{
		$localReference->setDateUpdated(new \DateTime(date("Y-m-d H:i:s")));
		$this->entityManager->persist($localReference);
		$this->entityManager->flush();

		return $localReference;
	}

	public function sendLocalReferenceRequest($localReferenceRequest)
	{
		$emailData = array(
			'localAuthorToLocalReference' => $localReferenceRequest,
			'responseLink' => $this->siteUrl.$this->router->generate('reference_request_response',array('token' => urlencode($localReferenceRequest->getToken()))),
		);

		$message = \Swift_Message::newInstance();
		$message->setSubject('Vouch for '.$localReferenceRequest->getLocalAuthor()->getName().' on Buggl');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($localReferenceRequest->getLocalReference()->getReferenceEmail());
		$message->setBody($this->templating->render('BugglMainBundle:Notification:localReferenceRequestNotification.html.twig', $emailData), 'text/html');
		$result = $this->mailer->send($message);

		if($result){
			$localReferenceRequest->setStatus($this->constants->get('LOCAL_REF_SENT'));
		}
		else{
			$localReferenceRequest->setStatus($this->constants->get('LOCAL_REF_UNSENT'));
		}
		$this->entityManager->persist($localReferenceRequest);
		$this->entityManager->flush();
	}
	
	public function sendLocalReferenceResponseNotification($localReferenceRequest)
	{
		$emailData = array(
			'localAuthorToLocalReference' => $localReferenceRequest,
			'link1' => $this->siteUrl.$this->router->generate('local_author_references'),
			'link2' => $this->siteUrl.$this->router->generate('local_author_references'),
		);
		
		$message = \Swift_Message::newInstance();
		$message->setSubject('You’ve been vouched for on Buggl');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($localReferenceRequest->getLocalAuthor()->getEmail());
		$message->setBody($this->templating->render('BugglMainBundle:Notification:localReferenceResponseNotification.html.twig', $emailData), 'text/html');
		$result = $this->mailer->send($message);
	}

	public function executeBatchEmailCommand($recipients, $localAuthorId)
	{
		//app/console buggl:bulk_email 0 noel.bacarisas@goabroad.com 5
		//"php","-i", "|", "grep", "Configuration File"

		echo 'BATCH SENDING...';
		$builder = new ProcessBuilder(array('php','console', 'buggl:bulk_email', 0, implode(',',$recipients), $localAuthorId));
		$builder->setWorkingDirectory('../app/');
		$builder->setTimeout(null);
		$builder->getProcess()->run();
	}

	public function truncateEmailsWithPendingRequest($contacts, $localAuthor)
	{
		$statuses = array($this->constants->get('LOCAL_REF_PENDING'),$this->constants->get('LOCAL_REF_SENT'));
		$pendingRequests = $this->entityManager->getRepository('BugglMainBundle:LocalAuthorToLocalReference')->retrieveRequestsByStatus($statuses,$localAuthor,true);
		$emailsWithPendingRequest = array();
		foreach($pendingRequests as $pendingRequest){
			if($pendingRequest->getStatus() == $this->constants->get('LOCAL_REF_PENDING') || $this->constants->get('LOCAL_REF_SENT'))
				$emailsWithPendingRequest[] = $pendingRequest->getLocalReference()->getReferenceEmail();
		}

		$trimmedContacts = array();
		foreach($contacts as $email => $name){
			if(!in_array($email,$emailsWithPendingRequest))
				$trimmedContacts[$email] = $name;
		}

		return $trimmedContacts;
	}

	public function truncateEmailsWithRequest($contacts, $localAuthor)
	{
		$statuses = array($this->constants->get('LOCAL_REF_PENDING'),$this->constants->get('LOCAL_REF_SENT'),$this->constants->get('LOCAL_REF_LIST'));
		$requests = $this->entityManager->getRepository('BugglMainBundle:LocalAuthorToLocalReference')->retrieveRequestsByStatus($statuses,$localAuthor,true);

		$emails = array();
		foreach($requests as $request){
			$emails[] = $request->getLocalReference()->getReferenceEmail();
		}

		$trimmedContacts = array();
		foreach($contacts as $email => $name){
			if(!in_array($email,$emails))
				$trimmedContacts[$email] = $name;
		}

		return $trimmedContacts;
	}

	/*
	 * Returns localAuthorToLocalReference when valid
	 */
	public function validateToken($token)
	{
		$valid = false;
		$localReferenceRequest = $this->entityManager->getRepository('BugglMainBundle:LocalAuthorToLocalReference')->findOneBy(array('token' => $token));

		if(!is_null($localReferenceRequest) && $localReferenceRequest->getStatus() != $this->constants->get('LOCAL_REF_LIST')){
			$valid = strtotime($localReferenceRequest->getTokenExpiration()->format('Y-m-d H:i:s')) >= strtotime(date('Y-m-d H:i:s')) ? $localReferenceRequest : false;
		}

		return $valid;
	}

	public function updateLocalReferenceRequestStatus($localReference)
	{
		$localAuthorToLocalReference = $this->entityManager->getRepository('BugglMainBundle:LocalAuthorToLocalReference')->retrieveOneByLocalReference($localReference);
		$localAuthorToLocalReference->setStatus($this->constants->get('LOCAL_REF_LIST'));
		$localAuthorToLocalReference->setToken('');
		$this->entityManager->persist($localAuthorToLocalReference);

		$this->entityManager->flush();

		return $localAuthorToLocalReference;
	}

	public function updateFeatureStatus($localReference,$status)
	{
		$localReference->setIsFeatured($status);
		$this->entityManager->persist($localReference);
		$this->entityManager->flush();

		return $localReference;
	}

	public function validateEmails($emails)
	{
		$data = array();
		$invalidMessage = '';
		$invalidEmails = array();
		$validEmails = array();

		if(empty($emails)){
			$invalidMessage = 'Please input valid email address.';
		}
		else{
			foreach(explode(',',$emails) as $email){
				if(!preg_match("/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i",trim($email)))
					$invalidEmails[] = trim($email);
				else
					$validEmails[] = trim($email);
			}

			if(!empty($invalidEmails)){
				$invalidMessage = count($invalidEmails) ? 'The following email addresses are invalid : ' : 'The following email address is invalid : ';
				$invalidMessage .= implode(', ',$invalidEmails).'.';
			}
		}

		$data['invalidMessage'] = $invalidMessage;
		$data['invalidEmails'] = $invalidEmails;
		$data['validEmails'] = $validEmails;

		return $data;
	}
}