<?php

namespace Buggl\MainBundle\Service;

class GmailContactsService
{
	protected $clientId;
	protected $clientSecret;
	protected $baseUrl = 'https://www.google.com/m8/feeds/contacts/default/full';

	protected $environmentVars;

	public function __construct($environmentVars)
	{
		$this->environmentVars = $environmentVars;

		$this->clientId = $this->environmentVars->getVariable('googleClientId');
		$this->clientSecret = $this->environmentVars->getVariable('googleClientSecret');
	}

	public function getGmailContacts($accesstoken, $maxResults=1000)
	{
		$hostUrl = $this->baseUrl.'?max-results='.$maxResults.'&oauth_token='.$accesstoken.'&v=3.0';
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $hostUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$xmlResponse = curl_exec($ch);
		$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($httpStatus == 401)
			return array('error' => 401);

		$doc = new \DOMDocument;
		$doc->recover = true;
		$doc->loadXML($xmlResponse);

		$xpath = new \DOMXPath($doc);
		$xpath->registerNamespace('gd', 'http://schemas.google.com/g/2005');
		$emails = $xpath->query('//gd:email');

		$contacts = array();
		foreach( $emails as $email ){
			$name = $email->parentNode->getElementsByTagName('title')->item(0)->nodeValue;

			if(!isset($contacts[$email->getAttribute('address')]) || !isset($contacts[$email->getAttribute('address')]['name']) || $contacts[$email->getAttribute('address')]['name'] == 'No Name'){
				$contacts[$email->getAttribute('address')] = empty($name) ? 'No Name' : $name;
			}

		}

		return $contacts;
	}
}