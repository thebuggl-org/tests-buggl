<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\LocalAuthor;

class FacebookService
{
	private $entityManager;
	private $router;
	private $environmentVars;

	private $appId;
	private $appSecret;

	public function __construct( $environmentVars, $entityManager, $router )
	{
		$this->environmentVars = $environmentVars;
		$this->entityManager = $entityManager;
		$this->router = $router;

		$this->appId = $this->environmentVars->getVariable('facebookAppId');
		$this->appSecret = $this->environmentVars->getVariable('facebookAppSecret');
	}

	/**
     * Get Url used for registration, connect or login via Facebook
     *
     * @return url
     */
	public function getFBConnectUrl($redirectUrl)
	{
		if(strpos($_SERVER['HTTP_HOST'],'http') === false)
			$redirectUrl = 'http://'.$_SERVER['HTTP_HOST'].$redirectUrl;

		return "https://www.facebook.com/dialog/oauth?client_id=$this->appId&redirect_uri=$redirectUrl&scope=publish_stream,email,user_birthday";
	}

	/**
     * Get access token based on code retrieved from facebook
     *
     * @param string $code
	 * @param string $redirectUrl (this should be the same as the one used in getFBConnectUrl())
     * @return string
     */
	public function getFacebookAccessToken( $code, $redirectUrl )
	{
		if(strpos($_SERVER['HTTP_HOST'],'http') === false)
			$redirectUrl = 'http://'.$_SERVER['HTTP_HOST'].$redirectUrl;

		$tokenUrl = "https://graph.facebook.com/oauth/access_token?client_id=$this->appId"."&client_secret=$this->appSecret&code=$code&scope=publish_stream&redirect_uri=".urlencode($redirectUrl);

		$ch = curl_init($tokenUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$httpResponse = curl_exec($ch);
		curl_close($ch);

		if(strpos($httpResponse,'access_token') !== false){
			$params = array();
			foreach(explode('&',$httpResponse) as $param){
				$temp = explode('=',$param);
				$params[$temp[0]] = $temp[1];
			}

			return $params['access_token'];
		}

		return null;
	}

	public function getFacebookAccessTokenFromDB($user)
	{
		$socialMedia = $this->entityManager->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($user);

		if(!is_null($socialMedia) && !is_null($socialMedia->getFbAccessToken())){
			return $socialMedia->getFbAccessToken();
		}

		return null;
	}

	/**
     * Get Local Expert populated with info from Facebook account
     *
     * @param string $code
     * @return LocalAuthor
     */
	public function getFBPrefilledLocalAuthor( $fbInfo )
	{
		$localAuthor = new LocalAuthor();

		$localAuthor->setFirstName($fbInfo['firstName']);
		$localAuthor->setLastName($fbInfo['lastName']);
		$localAuthor->setPassword(null);

		return $localAuthor;
	}

	/**
     * Get info from Facebook account
     *
     * @param string $accessToken
     * @return array
     */
	public function getFbInfo( $accessToken )
	{
		$graphUrl = "https://graph.facebook.com/me?access_token=".$accessToken;
		$ch = curl_init($graphUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$httpResponse = curl_exec($ch);
		$user = json_decode($httpResponse);
		$workInfo = isset($user->work) ? $user->work : array();
		
		$fbInfo['firstName'] = $user->first_name;
		$fbInfo['lastName'] = $user->last_name;
		$fbInfo['fbId'] = $user->id;
		$fbInfo['fbUrl'] = $user->link;
		$fbInfo['fbEmail'] = $user->email;
		$fbInfo['fbBirthday'] = isset($user->birthday) ? date('Y:m:d H:i:s',strtotime($user->birthday)) : null;
		$fbInfo['fbWork'] = isset($workInfo[0]->position) ? $workInfo[0]->position->name : null;
		
		return $fbInfo;
	}

	/**
     * Check if Facebook account is already registered
     *
     * @param facebook id
     * @return boolean
     */
	public function checkIfFBAccountInUse( $fbId )
	{
		$socialMedia = $this->entityManager->getRepository('BugglMainBundle:SocialMedia')->findOneBy(array('fbId' => $fbId));

		return !is_null($socialMedia);
	}

	/**
     * count facebook friends
     *
     * @param accessToken
     * @return int
     */
	public function countFriends($accessToken)
	{
		$graphUrl = "https://graph.facebook.com/fql?q=SELECT+friend_count+FROM+user+WHERE+uid=me()&access_token=$accessToken";
		$ch = curl_init($graphUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$httpResponse = curl_exec($ch);
		curl_close($ch);

		$cleanResponse = $this->cleanResponse($httpResponse);
		if(!is_null($cleanResponse)){
			return isset($cleanResponse->data[0]->friend_count) ? $cleanResponse->data[0]->friend_count : 0;
		}

		return null;
	}
	
	public function postStatus($postData,$accessToken)
	{
		$graphUrl = 'https://graph.facebook.com/me/feed';
		
		$data = array_merge($postData,array('access_token' => $accessToken));
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $graphUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		
		$httpResponse = curl_exec($ch);
		curl_close($ch);
		//var_dump($httpResponse);
		$response = $this->cleanResponse($httpResponse);
		
		return $response;
	}

	public function cleanResponse($httpResponse)
	{
		$response = json_decode($httpResponse);
		if(isset($response->error)){
			/*if($response->error->type == "OAuthException"){
				return null;
			}*/
			return null;
		}

		return $response;
	}
}