<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\LocalAuthor;
use Buggl\MainBundle\Entity\LocalInterest;

class ProfileService
{
	private $entityManager;

	public function __construct( $entityManager )
	{
		$this->entityManager = $entityManager;
	}

	/**
     * update Profile
     *
	 * @param \Profile $profile
	 * @param \LocalAuthor $localAuthor
     * @return \Profile
     */
	public function updateProfile( $profile, $localAuthor )
	{
		if(is_null($profile->getLocalAuthor()))
			$profile->setLocalAuthor($localAuthor);

		$this->entityManager->persist($profile);
	    $this->entityManager->flush();

		return $profile;
	}

	/**
     * Get updated field
     *
	 * @param \Profile $profile
	 * @param Integer $fieldId
     * @return String content
     */
	public function getUpdatedProfileContentByFieldId( $profile, $fieldId )
	{
		$content = "";
		// refactor this
		if($fieldId == 0){
			$content = $profile->getPhone();
		}
		else if($fieldId == 1){
			$content = $profile->getSkypeId();
		}
		else if($fieldId == 2){
			$content = $profile->getLocalSince();
		}
		else if($fieldId == 3){
			$content = $profile->getInterestAndActivities();
		}
		else if($fieldId == 4){
			$content = $profile->getAccomplishments();
		}
		else if($fieldId == 5){
			$content = $profile->getAge();
		}
		else if($fieldId == 6){
			$content = $profile->getAboutYou();
		}
		else if($fieldId == 7){
			$content = $profile->getSelfComment();
		}
		else if($fieldId == 8){
			$content = $profile->getKidsInfo();
		}
		else if($fieldId == 10){
			$content = $profile->getWork();
		}

		return $content;
	}

	/**
     * save Local Interest from form
     *
     * @param \LocalInterest $localInterest
	 * @param \LocalAuthor $localAuthor
     * @return \LocalInterest
     */
	public function saveInterest( $localInterest, $localAuthor )
	{
		$localInterest->setLocalAuthor($localAuthor);

	    $this->entityManager->persist($localInterest);
	    $this->entityManager->flush();

		return $localInterest;
	}

	/**
     * delete Local Interest
     *
	 * @param Integer $localInterestId
     * @return \LocalInterest
     */
	public function deleteInterestById( $localInterestId )
	{
		$localInterest = $this->entityManager->getRepository('BugglMainBundle:LocalPassion')->findOneBy(array('id' => $localInterestId));

		if(!is_null($localInterest)){
			$this->entityManager->remove($localInterest);
		    $this->entityManager->flush();
		}
	}

	/**
     * update Travel Info
     *
	 * @param \TravelInfo $travelInfo
	 * @param \LocalAuthor $localAuthor
	 * @return \LocalInterest
     */
	public function updateTravelInfo( $travelInfo, $localAuthor )
	{
		// this may not be the best way, research on embedded forms
		$travelInfo->setLocalAuthor($localAuthor);

		$this->entityManager->persist($travelInfo);
	    $this->entityManager->flush();

		return $travelInfo;
	}
}