<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\LocalAuthor;

class GooglePlusService
{
	protected $clientId;
	protected $clientSecret;

	protected $environmentVars;
	protected $entityManager;

	private $baseUrl = 'https://www.googleapis.com/plus/v1';

	public function __construct($environmentVars, $entityManager)
	{
		$this->environmentVars = $environmentVars;
		$this->entityManager = $entityManager;

		$this->clientId = $this->environmentVars->getVariable('googleClientId');
		$this->clientSecret = $this->environmentVars->getVariable('googleClientSecret');

	}

	public function getGooglePlusInfo($accessToken)
	{
		$hostUrl = $this->baseUrl.'/people/me?access_token='.$accessToken;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $hostUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$httpResponse = curl_exec($ch);
		$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($httpStatus == 401)
			return array('error' => 401);

		$result = json_decode($httpResponse);

		$info = array(
			'firstName' => $result->name->givenName,
			'lastName' => $result->name->familyName,
			'googlePlusId' => $result->id,
			'googlePlusUrl' => $result->url
		);

		return $info;
	}

	/**
     * Get Local Expert populated with info from Google Plus account
     *
     * @param string $code
     * @return LocalAuthor
     */
	public function getGooglePlusPrefilledLocalAuthor( $googlePlusInfo )
	{
		$localAuthor = new LocalAuthor();

		$localAuthor->setFirstName($googlePlusInfo['firstName']);
		$localAuthor->setLastName($googlePlusInfo['lastName']);
		$localAuthor->setPassword(null);

		return $localAuthor;
	}

	/**
     * Check if Google Plus account is already registered
     *
     * @param string $twitterId
     * @return boolean
     */
	public function checkIfGooglePlusAccountInUse( $googlePlusId )
	{
		$socialMedia = $this->entityManager->getRepository('BugglMainBundle:SocialMedia')->findOneBy(array('googlePlusId' => $googlePlusId));

		return !is_null($socialMedia);
	}
}