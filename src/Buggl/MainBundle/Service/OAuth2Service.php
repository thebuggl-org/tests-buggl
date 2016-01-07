<?php

namespace Buggl\MainBundle\Service;

class OAuth2Service
{
	protected $authenticationUrl = 'https://accounts.google.com/o/oauth2/auth';
	protected $accessTokenUrl = 'https://accounts.google.com/o/oauth2/token';
	protected $revokeTokenUrl = 'https://accounts.google.com/o/oauth2/revoke';

	protected $clientId;
	protected $clientSecret;

	protected $environmentVars;

	public function __construct($environmentVars)
	{
		$this->environmentVars = $environmentVars;

		$this->clientId = $this->environmentVars->getVariable('googleClientId');
		$this->clientSecret = $this->environmentVars->getVariable('googleClientSecret');
	}

	public function getAuthenticationUrl($redirectUri, $scope, $accessType = 'online', $responseType = 'code')
	{
		return $this->authenticationUrl . '?scope=' . $scope . '&redirect_uri=' . $redirectUri . '&response_type=' .
			   $responseType . '&access_type=' . $accessType . '&client_id=' . $this->clientId;
	}

	public function getAccessToken($code, $scope, $redirectUri, $includeRefreshToken = false)
	{
		$params = 'code=' . $code . '&redirect_uri=' . $redirectUri . '&client_id=' . $this->clientId .
		          '&scope='. $scope . '&client_secret=' . $this->clientSecret . '&grant_type=authorization_code';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->accessTokenUrl);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

		$httpResponse = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($httpResponse);
		$returnValue = '';
		if(property_exists($result,'access_token')){
			if($includeRefreshToken){
				$returnValue['accessToken']	= $result->access_token;
				if(property_exists($result,'refresh_token')){
					$returnValue['refreshToken'] = $result->refresh_token;
				}
			}
			else{
				$returnValue = $result->access_token;
			}
		}

		return $returnValue;
	}

	public function refreshAccessToken($refreshToken)
	{
		$params = '&client_id=' . $this->clientId . '&client_secret=' . $this->clientSecret . '&refresh_token='. $refreshToken .'&grant_type=refresh_token';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->accessTokenUrl);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

		$httpResponse = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($httpResponse);

		$returnValue = null;
		if(property_exists($result,'access_token')){
			$returnValue = $result->access_token;
		}

		return $returnValue;
	}

	public function revokeAccess($token)
	{
		$params = 'token=' . $token;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->revokeTokenUrl);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

		$httpResponse = curl_exec($ch);
		$result = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		return $result;
	}
}