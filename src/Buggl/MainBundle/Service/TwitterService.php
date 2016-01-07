<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Service\OAuthService;
use Buggl\MainBundle\Entity\LocalAuthor;

class TwitterService extends OAuthService
{
	protected $environmentVars;
	protected $consumerKey;
	protected $consumerSecret;
	protected $requestTokenUrl = 'https://api.twitter.com/oauth/request_token';
	protected $accessTokenUrl = 'https://api.twitter.com/oauth/access_token';

	private $authorizationUrl = 'https://api.twitter.com/oauth/authenticate';
	private $accountInfoUrl = 'https://api.twitter.com/1.1/account/verify_credentials.json';
	private $usersLookupUrl = 'https://api.twitter.com/1.1/users/lookup.json';
	
	public function __construct($environmentVars, $entityManager)
	{
		$this->environmentVars = $environmentVars;
		$this->entityManager = $entityManager;

		$this->consumerKey = $this->environmentVars->getVariable('twitterConsumerKey');
		$this->consumerSecret = $this->environmentVars->getVariable('twitterConsumerSecret');
	}

	public function getAuthorizationUrl($oauthToken)
	{
		return $this->authorizationUrl.'?oauth_token='.$oauthToken;
	}

	public function getTwitterInfo($accessToken,$tokenSecret)
	{
	    $twitterInfo = array();
	    $response = array();

	    $params['oauth_version'] = '1.0';
	    $params['oauth_nonce'] = mt_rand();
	    $params['oauth_timestamp'] = time();
	    $params['oauth_consumer_key'] = $this->consumerKey;
		$params['oauth_token'] = $accessToken;
	    $params['oauth_signature_method'] = 'HMAC-SHA1';
	    $params['oauth_signature'] = $this->oauthComputeHmacSig('GET', $this->accountInfoUrl, $params, $tokenSecret);

	    $queryParameterString = $this->oauthHttpBuildQuery($params);

		$requestUrl = $this->accountInfoUrl . ($queryParameterString ? ('?' . $queryParameterString) : '' );
	  	$response = $this->doGet($requestUrl, 443);

	    // extract successful response
	    if (!empty($response)) {
			list($info, $headers, $body) = $response;
			if ($info['http_code'] == 200 && !empty($body)) {
				$user = json_decode($body);
				//$name = explode(' ',$user->name);
				$twitterInfo['firstName'] = '';//$name[0];
				$twitterInfo['lastName'] = '';//$name[1];
				$twitterInfo['twitterId'] = $user->id_str;
				$twitterInfo['twitterUrl'] = 'https://twitter.com/'.$user->screen_name;
			}
	  	}

	  	return $twitterInfo;
	}
	
	/*
		NOTES:
			This could actually be used as a better twitter info source than getTwitterInfo() as this returns more details 
			and most details returned in the aforementioned method are also available through this 
			the problem though, is that this method requires twitter_id which we don't have unless we use the former.
	
		TODO:
			Refactor to be more reusable in fetching more info other than followers count
	*/
	public function countTwitterFollowers($accessToken,$tokenSecret,$twitterId)
	{
		$oauth = array( 
			'user_id' => $twitterId,
			'oauth_consumer_key' => $this->consumerKey, 
			'oauth_nonce' => time(), 
			'oauth_signature_method' => 'HMAC-SHA1', 
			'oauth_token' => $accessToken, 
			'oauth_timestamp' => time(), 
			'oauth_version' => '1.0'
		);

		$baseInfo = $this->buildBaseString($this->usersLookupUrl, 'GET', $oauth); 
		$compositeKey = rawurlencode($this->consumerSecret) . '&' . rawurlencode($tokenSecret); 
		$oauthSignature = base64_encode(hash_hmac('sha1', $baseInfo, $compositeKey, true)); 
		$oauth['oauth_signature'] = $oauthSignature;

		$header = array($this->buildAuthorizationHeader($oauth), 'Expect:'); 
		$options = array(
			 CURLOPT_HTTPHEADER => $header,
			 CURLOPT_HEADER => false, 
			 CURLOPT_URL => $this->usersLookupUrl.'?user_id='.$twitterId, 
			 CURLOPT_RETURNTRANSFER => true, 
			 CURLOPT_SSL_VERIFYPEER => false
		 );


		$ch = curl_init(); 
		curl_setopt_array($ch, $options); 
		$json = curl_exec($ch); 
		curl_close($ch);
		
		$twitterData = json_decode($json);
		return isset($twitterData[0]->followers_count) ? $twitterData[0]->followers_count : 0;
	}
	
	public function tweet($accessToken,$tokenSecret,$tweetData)
	{
		$tweetUrl = 'https://api.twitter.com/1.1/statuses/update_with_media.json';
		$oauth = array(
			'oauth_consumer_key' => $this->consumerKey, 
			'oauth_nonce' => time(), 
			'oauth_signature_method' => 'HMAC-SHA1', 
			'oauth_token' => $accessToken, 
			'oauth_timestamp' => time(), 
			'oauth_version' => '1.0'
		);

		$baseInfo = $this->buildBaseString($tweetUrl, 'POST', $oauth); 
		$compositeKey = rawurlencode($this->consumerSecret) . '&' . rawurlencode($tokenSecret); 
		$oauthSignature = base64_encode(hash_hmac('sha1', $baseInfo, $compositeKey, true)); 
		$oauth['oauth_signature'] = $oauthSignature;

		$header = array($this->buildAuthorizationHeader($oauth), 'Expect:'); 
		$options = array(
			 CURLOPT_HTTPHEADER => $header,
			 CURLOPT_HEADER => false, 
			 CURLOPT_URL => $tweetUrl, 
			 CURLOPT_RETURNTRANSFER => true, 
			 CURLOPT_SSL_VERIFYPEER => false,
			 CURLOPT_POST => true,
			 CURLOPT_POSTFIELDS => $tweetData
		 );


		$ch = curl_init(); 
		curl_setopt_array($ch, $options); 
		$json = curl_exec($ch); 
		curl_close($ch);
		
		$twitterData = json_decode($json);
		//var_dump($twitterData);
		return $twitterData;
	}

	/**
     * Get Local Expert populated with info from Twitter account
     *
     * @param string $code
     * @return LocalAuthor
     */
	public function getTwitterPrefilledLocalAuthor( $twitterInfo )
	{
		$localAuthor = new LocalAuthor();

		$localAuthor->setFirstName($twitterInfo['firstName']);
		$localAuthor->setLastName($twitterInfo['lastName']);
		$localAuthor->setPassword(null);

		return $localAuthor;
	}

	/**
     * Check if Twitter account is already registered
     *
     * @param string $twitterId
     * @return boolean
     */
	public function checkIfTwitterAccountInUse( $twitterId )
	{
		$socialMedia = $this->entityManager->getRepository('BugglMainBundle:SocialMedia')->findOneBy(array('twitterId' => $twitterId));

		return !is_null($socialMedia);
	}
	
	public function buildBaseString($baseURI, $method, $params) 
	{ 
		$r = array(); 
		ksort($params); 
		foreach($params as $key=>$value){
			$r[] = "$key=" . rawurlencode($value); 
		} 
	
		return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r)); 
	}
	
	public function buildAuthorizationHeader($oauth) 
	{
		$r = 'Authorization: OAuth '; 
		$values = array(); 
		foreach($oauth as $key=>$value) 
			$values[] = "$key=\"" . rawurlencode($value) . "\""; 
		
		$r .= implode(', ', $values);

		return $r; 
	}
}