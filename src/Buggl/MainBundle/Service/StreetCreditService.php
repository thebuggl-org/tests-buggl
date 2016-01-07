<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\StreetCredit;

class StreetCreditService
{
	protected $entityManager;

	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}
	
	public function updateGuideStatus($localAuthor)
	{
		$streetCredit = $this->getStreetCredit($localAuthor);
		
		if(!is_null($streetCredit->getGuideStatus()) && $streetCredit->getGuideStatus() == 1){
			return $streetCredit;
		}
		
		$guidesCount = $this->entityManager->getRepository('BugglMainBundle:EGuide')->countApprovedByLocalAuthor($localAuthor);
		
		if($guidesCount > 0){
			$streetCredit->setGuideStatus(1);
		
			$this->entityManager->persist($streetCredit);
			$this->entityManager->flush();
		}
		
		return $streetCredit;
	}
	
	public function updateProfileStatus($localAuthor)
	{
		$streetCredit = $this->getStreetCredit($localAuthor);
		
		if(!is_null($streetCredit->getProfileStatus()) && $streetCredit->getProfileStatus() == 1){
			return $streetCredit;
		}
		
		$profile = $localAuthor->getProfile();

		// TODO: refactor with dashboard profile completion percentage
		$neededData = array(
			'getLocalSince' => 'Local Since.',
			'getInterestAndActivities' => 'Do you have no interest and activities?',
			'getBirthDate' => 'You did not mention your age.',
			'getProfilePic' => 'You have no Profile Pic.',
			'getAboutYou' => 'You did not share anything about you.',
			'getSelfComment' => 'What makes you the best local?',
			'getAccomplishments' => 'Any accomplishments you would like to share?'
		);
		
		$complete = false;
		if(!is_null($profile)){
			foreach($neededData as $key => $val){
				$tempVal = $profile->$key();
				if(is_null($tempVal) || empty($tempVal)){
					$complete = false;
					break;
				}
				else{
					$complete = true;
				}
			}
		}
		
		if($complete){
			$streetCredit->setProfileStatus(1);
		
			$this->entityManager->persist($streetCredit);
			$this->entityManager->flush();
		}
		
		return $streetCredit;
	}
	
	public function updateVouchStatus($localAuthor)
	{
		$streetCredit = $this->getStreetCredit($localAuthor);
		if(!is_null($streetCredit->getVouchStatus()) && $streetCredit->getVouchStatus() == 1){
			return $streetCredit;
		}
		
		$localAuthorToLocalReferencesCount = $this->entityManager->getRepository('BugglMainBundle:LocalAuthorToLocalReference')->countAllFeatureByLocalAuthor($localAuthor);
		
		if($localAuthorToLocalReferencesCount > 0){
			$streetCredit->setVouchStatus(1);
		
			$this->entityManager->persist($streetCredit);
			$this->entityManager->flush();
		}
		
		return $streetCredit;
	}
	
	public function updateShareStatus($localAuthor)
	{
		$streetCredit = $this->getStreetCredit($localAuthor);
		if(!is_null($streetCredit->getShareStatus()) && $streetCredit->getShareStatus() == 1){
			return $streetCredit;
		}
		
		$streetCredit->setShareStatus(1);
		
		$this->entityManager->persist($streetCredit);
		$this->entityManager->flush();
		
		return $streetCredit;
	}
	
	public function updateInviteAuthorStatus($localAuthor)
	{
		$invitation = $this->entityManager->getRepository('BugglMainBundle:LocalAuthorToInvite')->findOneByInvitedAuthor($localAuthor);
		if(is_null($invitation)){
			return null;
		}
		
		$streetCredit = $this->getStreetCredit($invitation->getLocalAuthor());
		
		if(!is_null($streetCredit->getInviteAuthorStatus()) && $streetCredit->getInviteAuthorStatus() == 1){
			return $streetCredit;
		}
		
		$guidesCount = $this->entityManager->getRepository('BugglMainBundle:EGuide')->countApprovedByLocalAuthor($invitation->getInvitedAuthor());
		if($guidesCount > 0){
			$streetCredit->setInviteAuthorStatus(1);
			
			$this->entityManager->persist($streetCredit);
			$this->entityManager->flush();
		}
		
		return $streetCredit;
	}
	
	public function updateConnectStatus($localAuthor)
	{
		$streetCredit = $this->getStreetCredit($localAuthor);
		if(!is_null($streetCredit->getConnectStatus()) && $streetCredit->getConnectStatus() == 1){
			return $streetCredit;
		}
		
		$socialMedia = $this->entityManager->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor);
		
		$connectCompletion = false;
		if(!is_null($socialMedia)){
			$connectCompletion = $connectCompletion || !is_null($socialMedia->getFbId());
			$connectCompletion = $connectCompletion || !is_null($socialMedia->getTwitterId());
			$connectCompletion = $connectCompletion || !is_null($socialMedia->getGooglePlusId());
		}	
		
		if($connectCompletion){
			$streetCredit->setConnectStatus(1);
			
			$this->entityManager->persist($streetCredit);
			$this->entityManager->flush();
		}
		
		return $streetCredit;
		
	}
	
	private function getStreetCredit($localAuthor)
	{
		$streetCredit = $this->entityManager->getRepository("BugglMainBundle:StreetCredit")->findOneByLocalAuthor($localAuthor);
		
		if(is_null($streetCredit)){
			$streetCredit = new StreetCredit();
			$streetCredit->setLocalAuthor($localAuthor);
		}
		
		return $streetCredit;
	}
}