<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\StripeInfo;

class StripeService
{
	protected $endPoint = 'https://api.stripe.com/v1/';
	protected $authenticationUrl = 'https://connect.stripe.com/oauth/authorize';
	protected $accessTokenUrl = 'https://connect.stripe.com/oauth/token';
	protected $chargeUrl = 'https://api.stripe.com/v1/charges';

	protected $clientId;
	protected $secretKey;
	protected $publishableKey;

	protected $environmentVars;
	protected $entityManager;

	public function __construct($environmentVars, $entityManager)
	{
		$this->environmentVars = $environmentVars;
		$this->entityManager = $entityManager;

		$this->secretKey = $this->environmentVars->getVariable('stripe_secret_key');
		$this->publishableKey = $this->environmentVars->getVariable('stripe_publishable_key');
		$this->clientId = $this->environmentVars->getVariable('stripe_client_id');
	}

	public function getAuthenticationUrl()
	{
		return $this->authenticationUrl.'?response_type=code&client_id='.$this->clientId.'&scope=read_write';;
	}

	public function getAccessTokenInfo($code)
	{
		$params = 'client_secret=' . $this->secretKey. '&code=' . $code . '&grant_type=authorization_code';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->accessTokenUrl);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

		$httpResponse = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($httpResponse);
		$returnValue = array('status' => 'error');
		if(isset($result->access_token)){
			$returnValue = array(
				'status' => 'success',
				'access_token' => $result->access_token,
				'refresh_token' => $result->refresh_token,
				'stripe_publishable_key' => $result->stripe_publishable_key,
				'stripe_user_id' => $result->stripe_user_id
			);
		}

		return $returnValue;
	}

	public function saveStripeInfo($code,$localAuthor)
	{
		$params = 'client_secret=' . $this->secretKey. '&code=' . $code . '&grant_type=authorization_code';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->accessTokenUrl);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

		$httpResponse = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($httpResponse);
		$returnValue = array();
		if(isset($result->access_token)){
			$stripeInfo = $this->entityManager->getRepository('BugglMainBundle:StripeInfo')->findbyLocalAuthor($localAuthor,true);
			$stripeInfo->setLocalAuthor($localAuthor);
			$stripeInfo->setAccessToken($result->access_token);
			$stripeInfo->setRefreshToken($result->refresh_token);
			$stripeInfo->setStripePubKey($result->stripe_publishable_key);
			$stripeInfo->setStripeUserId($result->stripe_publishable_key);

			$this->entityManager->persist($stripeInfo);
			$this->entityManager->flush();

			return $stripeInfo;
		}

		return null;
	}

	public function disconnectStripeAccount($localAuthor)
	{
		$stripeInfo = $this->entityManager->getRepository('BugglMainBundle:StripeInfo')->findbyLocalAuthor($localAuthor);

		if(!is_null($stripeInfo)){
			$stripeInfo->setAccessToken(null);
			$stripeInfo->setRefreshToken(null);
			$stripeInfo->setStripePubKey(null);
			$stripeInfo->setStripeUserId(null);

			$this->entityManager->persist($stripeInfo);
			$this->entityManager->flush();
		}

		return $stripeInfo;
	}

	/*
		Accepts only card as token (from stripe.js)
		Refer to https://stripe.com/docs/api#create_charge
	*/
	public function charge($amount,$customerId,$card,$description=null,$applicationFee=0,$accessToken=null,$currency='usd',$capture='true')
	{
		$params = 'amount=' . $amount . '&currency=' . $currency;

		if(!is_null($customerId))
			$params .= '&customer=' . $customerId;
		else if(!is_null($card))
			$params .= '&card=' . $card;

		$params .= '&description=' . $description . '&capture=' . $capture;

		if($applicationFee > 0 && !is_null($accessToken)){
			$params .= '&application_fee=' . $applicationFee;
		    //$headers = array('Authorization: Bearer ' . $accessToken);
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->chargeUrl);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERPWD, "$accessToken:");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$httpResponse = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($httpResponse);

		return $result;
	}

	public function exampleRequest()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->endPoint.'charges');
		//curl_setopt($ch, CURLOPT_POST, TRUE);
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERPWD, "$this->secretKey:");

		$httpResponse = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($httpResponse);

		var_dump($result);
	}
}