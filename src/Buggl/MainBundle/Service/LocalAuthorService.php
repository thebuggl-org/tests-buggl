<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\City;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class LocalAuthorService
{
	private $entityManager;
	private $mailer;
	private $templating;
	private $router;
	private $constants;

	public function __construct( $entityManager, $mailer, $templating, $router, $constants )
	{
		$this->entityManager = $entityManager;
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->router = $router;
		$this->constants = $constants;
	}

	/**
     * update LocalAuthor
     *
	 * @param \LocalAuthor $localAuthor
     * @return \LocalAuthor
     */
	public function updateLocalAuthor( $localAuthor )
	{
		$this->entityManager->persist($localAuthor);
	    $this->entityManager->flush();

		return $localAuthor;
	}

	/**
     * updates password
     *
	 * @param \LocalAuthor $localAuthor
	 * @param string $oldPassword
	 * @param string $newPassword
     * @return \LocalAuthor or false if current password is incorrect
     */
	public function changePassword( $localAuthor, $oldPassword, $newPassword )
	{
		$encoder = new MessageDigestPasswordEncoder();
		$oldEncodedPassword = $encoder->encodePassword($oldPassword,'');
		if($localAuthor->getPassword() == $oldEncodedPassword){
			$localAuthor->setPassword($encoder->encodePassword($newPassword,''));

			return $this->updateLocalAuthor($localAuthor);
		}

		return false;
	}

	/**
     * sets password
     *
	 * @param \LocalAuthor $localAuthor
	 * @param string $newPassword
     * @return \LocalAuthor or false if current password is incorrect
     */
	public function setPassword( $localAuthor, $newPassword )
	{
		$encoder = new MessageDigestPasswordEncoder();
		$localAuthor->setPassword($encoder->encodePassword($newPassword,''));

		return $this->updateLocalAuthor($localAuthor);
	}

	/**
     * update LocalAuthor Location
     *
	 * @param \LocalAuthor $localAuthor
	 * @param Integer $cityId
     * @return \Location
     */
	public function updateLocalAuthorLocation( $localAuthor, $countryName, $cityName )
	{
		// update parameters, localAuthor, country name, city name
		$location = $this->entityManager->getRepository('BugglMainBundle:Location')->getByLocalAuthor($localAuthor,true);
		
		$location->setLocalAuthor($localAuthor);
		$country = $this->entityManager->getRepository('BugglMainBundle:Country')->findOneByName($countryName);
		if(!is_null($country)){
			$city = $this->entityManager->getRepository('BugglMainBundle:City')->findOneBy(array('name' => $cityName, 'country' => $country));
			if(is_null($city)){
				$city = new City();
				$city->setCountry($country);
				$city->setName($cityName);
				$this->entityManager->persist($city);
			}
			$location->setCity($city);
			$this->entityManager->persist($location);
		
			if($localAuthor->getIsLocalAuthor() == 0){
				$localAuthor->setIsLocalAuthor(1);
				$this->entityManager->persist($localAuthor);
			
				$message = \Swift_Message::newInstance();
				$message->setSubject('You’ve decided to share your knowledge on Buggl');
				$message->setFrom($this->constants->get('BUGGL_EMAIL'));
				$message->setTo($localAuthor->getEmail());
				$message->setBody($this->templating->render('BugglMainBundle:Notification:localAuthorUpgradeNotification.html.twig'), 'text/html');
				$this->mailer->send($message);
			}

			$this->entityManager->persist($location);
		    $this->entityManager->flush();
		}

		return $location;
	}
}