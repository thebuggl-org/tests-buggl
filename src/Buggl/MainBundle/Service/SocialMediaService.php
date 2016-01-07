<?php

namespace Buggl\MainBundle\Service;

class SocialMediaService
{
	private $entityManager;

	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function saveSocialMediaSettings($socialMedia,$localAuthor)
	{
		$socialMedia->setLocalAuthor($localAuthor);

		$this->entityManager->persist($socialMedia);
		$this->entityManager->flush();
	}

	public function saveFacebookInfo($fbInfo,$localAuthor)
	{
		$socialMedia = $this->entityManager->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor,true);
		$socialMedia->setLocalAuthor($localAuthor);

		foreach($fbInfo as $key => $value){
			$method = 'set'.ucwords($key);
			if(method_exists($socialMedia,$method)){
				$socialMedia->$method($value);
			}
		}

		$this->entityManager->persist($socialMedia);
		$this->entityManager->flush();
	}

	public function saveTwitterInfo($twitterInfo,$localAuthor)
	{
		$socialMedia = $this->entityManager->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor,true);
		$socialMedia->setLocalAuthor($localAuthor);

		foreach($twitterInfo as $key => $value){
			$method = 'set'.ucwords($key);
			if(method_exists($socialMedia,$method)){
				$socialMedia->$method($value);
			}
		}

		/*
		$socialMedia->setTwitterId($twitterInfo['twitterId']);
		$socialMedia->setTwitterUrl($twitterInfo['twitterUrl']);
		$socialMedia->setTwitterAccessToken($twitterInfo['twitterAccessToken']);
		*/

		$this->entityManager->persist($socialMedia);
		$this->entityManager->flush();
	}

	public function saveGooglePlusInfo($googlePlusInfo,$localAuthor)
	{
		$socialMedia = $this->entityManager->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor,true);
		$socialMedia->setLocalAuthor($localAuthor);

		foreach($googlePlusInfo as $key => $value){
			$method = 'set'.ucwords($key);
			if(method_exists($socialMedia,$method)){
				$socialMedia->$method($value);
			}
		}

		/*
		$socialMedia->setGooglePlusId($googlePlusInfo['googlePlusId']);
		$socialMedia->setGooglePlusUrl($googlePlusInfo['googlePlusUrl']);
		$socialMedia->setGooglePlusRefreshToken($googlePlusInfo['googlePlusRefreshToken']);
		*/

		$this->entityManager->persist($socialMedia);
		$this->entityManager->flush();
	}
}