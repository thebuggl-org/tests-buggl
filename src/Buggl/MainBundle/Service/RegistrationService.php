<?php

namespace Buggl\MainBundle\Service;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

use Buggl\MainBundle\Entity\LocalAuthor;
use Buggl\MainBundle\Entity\Profile;
use Buggl\MainBundle\Entity\Location;
use Buggl\MainBundle\Entity\City;
use Buggl\MainBundle\Entity\SocialMedia;
use Buggl\MainBundle\Entity\EmailVerification;

class RegistrationService
{
	private $mailer;
	private $templating;
	private $slugifier;
	private $entityManager;
	private $constants;
	private $router;
	private $socialMediaService;
	private $serviceContainer;
	private $encoder;


	public function __construct($mailer, $templating, $slugifier, $entityManager, $constants, $router, $socialMediaService, $serviceContainer)
	{
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->slugifier = $slugifier;
		$this->entityManager = $entityManager;
		$this->constants = $constants;
		$this->router = $router;
		$this->socialMediaService = $socialMediaService;
		$this->serviceContainer = $serviceContainer;

		$this->encoder = new MessageDigestPasswordEncoder();
	}

	public function registerLocalAuthor($data)
	{
		$password = isset($data['password']['first']) ? $this->encoder->encodePassword($data['password']['first'],'') : null;
		$email = isset($data['fbId']) ? $data['email'] : $data['email']['first'];

		$localAuthor = new LocalAuthor();
		$localAuthor->setFirstName($data['firstName']);
		$localAuthor->setLastName($data['lastName']);
		$localAuthor->setEmail($email);
		$localAuthor->setPassword($password);
		$localAuthor->setEmailVerified(1);
		// (isset($data['fbEmail']) && $email == $data['fbEmail']) ?  $this->constants->get('EMAIL_VERIFIED') : $this->constants->get('EMAIL_UNVERIFIED')
		$localAuthor->setIsLocalAuthor($data['type']);
		$localAuthor->setDateJoined(new \DateTime(date('Y-m-d H:i:s')));
		$localAuthor->setStatus(0);
		$localAuthor->setStreetCredit(0);
		$localAuthor->setIsApproved(1);
		$localAuthor->setIsBetaParticipant(isset($data['betaToken']));
		$localAuthor->setLastActive(new \DateTime(date('Y-m-d H:i:s')));
		$localAuthor->setLastInactiveNotificationSent(new \DateTime(date('Y-m-d H:i:s')));
		$this->entityManager->persist($localAuthor);

		$bypassLocation = true;
		if($data['type'] == 1 AND !$bypassLocation ){
			// this is in preparation for when location would be non-required : Nash Lesigon - May 8, 2014
			if( strlen( trim($data['country']) ) && strlen( trim($data['city']) ) )
			{
				$location = new Location();
				$location->setLocalAuthor($localAuthor);
				// find city by name, if not found create new city
				// data['city], data['country']
				$country = $this->entityManager->getRepository('BugglMainBundle:Country')->findOneByName($data['country']);
				if(!is_null($country)){
					$city = $this->entityManager->getRepository('BugglMainBundle:City')->findOneBy(array('name' => $data['city'], 'country' => $country));
					if(is_null($city)){
						$city = new City();
						$city->setCountry($country);
						$city->setName($data['city']);
						$this->entityManager->persist($city);
					}
					$location->setCity($city);
					$this->entityManager->persist($location);
				}
			}
			
		}

		$this->entityManager->flush();
		
		$profile = new Profile();
		$profile->setLocalAuthor($localAuthor);

		if(isset($data['fbId'])){
			$this->socialMediaService->saveFacebookInfo($data,$localAuthor);

			$tempPath = 'uploads/profile_pics';
			$uploadRootDir = $this->serviceContainer->get('kernel')->getRootdir().'/../web/'.$tempPath;
			$filename = 'fb_'.$data['fbId'].'.jpg';
			copy('https://graph.facebook.com/'.$data['fbId'].'/picture?width=285&height=285', $uploadRootDir.'/'.$filename);

			$profile->setProfilePic($filename);
			$profile->setWork($data['fbWork']);
			$profile->setBirthDate($data['fbBirthday']);
			
			$this->entityManager->persist($profile);
			$this->entityManager->flush();
		}
		else if(isset($data['twitterId'])){
			$this->socialMediaService->saveTwitterInfo($data,$localAuthor);
		}
		else if(isset($data['googlePlusId'])){
			$this->socialMediaService->saveGooglePlusInfo($data,$localAuthor);
		}
		else{
			$this->entityManager->persist($profile);
			$this->entityManager->flush();
		}

		return $localAuthor;
	}

	public function sendConfirmationEmail($localAuthor,$redirect = null)
	{
		if(is_null($redirect)){
			$redirect = $this->router->generate('buggl_homepage',array(),true);
		}
		$message = \Swift_Message::newInstance();
		$message->setSubject('Confirm your email on Buggl');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($localAuthor->getEmail());
		$token = $this->generateToken($localAuthor);

		$emailVerification = $this->entityManager->getRepository('BugglMainBundle:EmailVerification')->findByUser($localAuthor,true);
		$emailVerification->setUser($localAuthor);
		$emailVerification->setEmail($localAuthor->getEmail());
		$emailVerification->setToken($token);
		$emailVerification->setTokenExpiration(new \DateTime(date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s"))) . " +8 days"));
		$emailVerification->setStatus($this->constants->get('VERIFICATION_PENDING'));

		$this->entityManager->persist($emailVerification);
		$this->entityManager->flush();

		$link = $this->router->generate('registration_confirm',array('token' => urlencode($token),'email' => $localAuthor->getEmail(),'referer' => urlencode($redirect)),true);
		$message->setBody($this->templating->render('BugglMainBundle:Notification:registrationNotification.html.twig',array('localAuthor' => $localAuthor,'link' => $link)), 'text/html');

		$this->mailer->send($message);
	}
	
	public function sendSignupViaFbConfirmationEmail($localAuthor)
	{
		$message = \Swift_Message::newInstance();
		$message->setSubject('Welcome to Buggl');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($localAuthor->getEmail());

		$emailData = array(
			'localAuthor' => $localAuthor,
			'link1' => $localAuthor->getIsLocalAuthor() ? $this->router->generate('add_travel_guide_info',array(),true) : $this->router->generate('buggl_write_a_guide',array(),true),
			'link2' => $this->router->generate('buggl_homepage',array(),true),
			'link3' => $this->router->generate('buggl_static_our_tribe',array(),true)
		);
		$message->setBody($this->templating->render('BugglMainBundle:Notification:signUpViaFbNotification.html.twig',$emailData), 'text/html');

		$this->mailer->send($message);
	}

	public function createSlug($localAuthor)
	{
		$localAuthor->setSlug($this->slugifier->format($localAuthor->getName())
											  ->append($localAuthor->getId())
											  ->getSlug()
			);

		$this->entityManager->persist($localAuthor);
		$this->entityManager->flush();

		return $localAuthor;
	}

	public function verifyLocalAuthor($localAuthor, $token)
	{
		if(!is_null($localAuthor)){
			$emailVerification = $this->entityManager->getRepository('BugglMainBundle:EmailVerification')->findPendingByUser($localAuthor);
			if(!is_null($emailVerification) && $emailVerification->getToken() == $token){
				$localAuthor->setEmailVerified($this->constants->get('EMAIL_VERIFIED'));
				$this->entityManager->persist($localAuthor);

				$emailVerification->setStatus($this->constants->get('VERIFICATION_APPROVED'));
				$this->entityManager->persist($emailVerification);
				$this->entityManager->flush();

				return $localAuthor;
			}
		}

		return false;
	}

	private function generateToken($localAuthor)
	{
		//NOTE: TOKENS are generated through encoding using MessageDigestPasswordEncoder,
		// find a better way if necessary

		return $this->encoder->encodePassword($localAuthor->getEmail().' '.$localAuthor->getId(),'');
	}


	// for beta purposes only
	public function sendBetaTokenRequest($data)
	{
		$message = \Swift_Message::newInstance();
		$message->setSubject('Buggl Beta Token Request');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo('nash.lesigon@goabroad.com');
		//$message->setBcc('nash.lesigon@goabroad.com');
		$message->addBcc('noel.bacarisas@goabroad.com');
		$message->addBcc('farly.taboada@goabroad.com');

		$message->setBody($this->templating->render('BugglMainBundle:Notification:betaTokenRequestNotification.html.twig',$data), 'text/html');

		$this->mailer->send($message);
	}

	public function notifyVerifiedEmail($localAuthor=null)
	{
		if (!is_null($localAuthor)) {
			$data = array(
				'createGuide' => $this->router->generate('add_travel_guide_info',array(),true),
				'homepage' => $this->router->generate('buggl_homepage',array(),true),
				'story' => $this->router->generate('buggl_static_our_tribe',array(),true)
			);

			$message = \Swift_Message::newInstance();
			$message->setSubject('You’re in! Welcome to Buggl!');
			$message->setFrom($this->constants->get('BUGGL_EMAIL'));
			$message->setTo($localAuthor->getEmail());

			$message->setBody($this->templating->render('BugglMainBundle:Notification:accountVerified.html.twig',$data), 'text/html');

			$this->mailer->send($message);
		}
	}
}