<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Service\OAuthService;

class YahooMailContactsService extends OAuthService
{
	protected $environmentVars;
	protected $consumerKey;
	protected $consumerSecret;
	protected $requestTokenUrl = 'https://api.login.yahoo.com/oauth/v2/get_request_token';
	protected $accessTokenUrl = 'https://api.login.yahoo.com/oauth/v2/get_token';

	private $apiEndpoint = 'http://social.yahooapis.com/v1/user/';

	public function __construct($environmentVars)
	{
		$this->environmentVars = $environmentVars;

		$this->consumerKey = $this->environmentVars->getVariable('yahooMailConsumerKey');
		$this->consumerSecret = $this->environmentVars->getVariable('yahooMailConsumerSecret');
	}

	/**
	 * Call the Yahoo Contact API
	 * @param string $consumer_key obtained when you registered your app
	 * @param string $consumer_secret obtained when you registered your app
	 * @param string $guid obtained from getacctok
	 * @param string $access_token obtained from getacctok
	 * @param string $access_token_secret obtained from getacctok
	 * @param bool $usePost use HTTP POST instead of GET
	 * @param bool $passOAuthInHeader pass the OAuth credentials in HTTP header
	 * @return response string with token or empty array on error
	 */
	public function retrieveContacts($guid, $accessToken, $accessTokenSecret, $passOAuthInHeader=true)
	{
		$retarr = array();
		$response = array();
		$url = $this->apiEndpoint.$guid.'/contacts;count=100';

		$params['format'] = 'json';
		$params['view'] = 'compact';
		$params['oauth_version'] = '1.0';
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_timestamp'] = time();
		$params['oauth_consumer_key'] = $this->consumerKey;
		$params['oauth_token'] = $accessToken;

		// compute hmac-sha1 signature and add it to the params list
		$params['oauth_signature_method'] = 'HMAC-SHA1';
		$params['oauth_signature'] = $this->oauthComputeHmacSig('GET', $url, $params, $accessTokenSecret);

		// Pass OAuth credentials in a separate header or in the query string
		if($passOAuthInHeader){
			$queryParameterString = $this->oauthHttpBuildQuery($params, true);
			$header = $this->buildOauthHeader($params, "yahooapis.com");
			$headers[] = $header;
		}
		else{
			$queryParameterString = $this->oauthHttpBuildQuery($params);
		}

		// POST or GET the request
		if (false) {
			/*$request_url = $url;
			$headers[] = 'Content-Type: application/x-www-form-urlencoded';
			$response = do_post($request_url, $queryParameterString, 80, $headers);*/
		}
		else {
			$request_url = $url . ($queryParameterString ? ('?' . $queryParameterString) : '' );
			$response = $this->doGet($request_url, 80, $headers);
		}

		// extract successful response
		if (!empty($response)) {
			list($info, $header, $body) = $response;
			$retarr = $response;
		}

		$results = json_decode($retarr[2]);
		if(!isset($results->contacts))
			return array('error' => 401);

		$contacts = array();
		foreach( $results->contacts->contact as $result ){
			if(!isset($result->fields))
				continue;

			$name = '';
			$email = '';
			foreach($result->fields as $field){
				if($field->type == 'email'){
					$email = $field->value;
				}
				else if($field->type == 'name'){
					foreach($field->value as $nameParts)
						$name .= ' '.$nameParts;
				}
			}

			if(!empty($email))
				$contacts[$email] = trim($name);
		}


		return $contacts;
	}
}