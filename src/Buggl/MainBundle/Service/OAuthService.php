<?php

namespace Buggl\MainBundle\Service;

class OAuthService
{
	protected $consumerKey;
	protected $consumerSecret;
	protected $requestTokenUrl;
	protected $accessTokenUrl;
	protected $environmentVars;

	public function __construct($environmentVars)
	{
		$this->environmentVars = $environmentVars;

		$this->requestTokenUrl = 'https://api.login.yahoo.com/oauth/v2/get_request_token';
		$this->accessTokenUrl = 'https://api.login.yahoo.com/oauth/v2/get_token';
	}


	/**
	  * Get a request token.
      *
	  * @param string $callback callback url can be the string 'oob'
	  * @param bool $useHmacSha1Sig use HMAC-SHA1 signature
      *
	  * @return array of response parameters or empty array on error
	  **/
	public function getRequestToken($callBack, $useHmacSha1Sig=true, $usePost = false)
	{
	    $retarr = array();  // return value
	    $response = array();

	    $params['oauth_version'] = '1.0';
	    $params['oauth_nonce'] = mt_rand();
	    $params['oauth_timestamp'] = time();
	    $params['oauth_consumer_key'] = $this->consumerKey;
	    $params['oauth_callback'] = $callBack;

	    $params['oauth_signature_method'] = 'HMAC-SHA1';
	    $params['oauth_signature'] = $this->oauthComputeHmacSig(($usePost ? 'POST' : 'GET'), $this->requestTokenUrl, $params, null);

	    $queryParameterString = $this->oauthHttpBuildQuery($params);

		// POST or GET the request
	    if($usePost){
			//$headers[] = 'Content-Type: application/x-www-form-urlencoded';
			$response = $this->doPost($this->requestTokenUrl, $queryParameterString, 443);
		}
		else {
	    	  $requestUrl = $this->requestTokenUrl . ($queryParameterString ? ('?' . $queryParameterString) : '' );
			  $response = $this->doGet($requestUrl, 443);
	    }

	    // extract successful response
	    if (!empty($response)) {
	      list($info, $header, $body) = $response;
	      $bodyParsed = $this->oauthParseStr($body);

	      $retarr = $response;
	      $retarr[] = $bodyParsed;
	  }

	  return $retarr;
	}

	/**
	 * Compute an OAuth HMAC-SHA1 signature
	 * @param string $http_method GET, POST, etc.
	 * @param string $url
	 * @param array $params an array of query parameters for the request
	 * @param string $consumer_secret
	 * @param string $token_secret
	 * @return string a base64_encoded hmac-sha1 signature
	 * @see http://oauth.net/core/1.0/#rfc.section.A.5.1
	 */
	public function oauthComputeHmacSig($httpMethod, $url, $params, $tokenSecret)
	{
		$baseString = $this->signatureBaseString($httpMethod, $url, $params);
		$signatureKey = $this->rfc3986Encode($this->consumerSecret) . '&' . $this->rfc3986Encode($tokenSecret);
		$signature = base64_encode(hash_hmac('sha1', $baseString, $signatureKey, true));

		return $signature;
	}

	/**
	 * Returns the normalized signature base string of this request
	 * @param string $http_method
	 * @param string $url
	 * @param array $params
	 * The base string is defined as the method, the url and the
	 * parameters (normalized), each urlencoded and the concated with &.
	 * @see http://oauth.net/core/1.0/#rfc.section.A.5.1
	 */
	public function signatureBaseString($httpMethod, $url, $params)
	{
		// Decompose and pull query params out of the url
		$queryString = parse_url($url, PHP_URL_QUERY);
		if($queryString){
			$parsedQuery = $this->oauthParseStr($queryString);
			// merge params from the url with params array from caller
			$params = array_merge($params, $parsedQuery);
		}

		// Remove oauth_signature from params array if present
		if(isset($params['oauth_signature'])){
			unset($params['oauth_signature']);
		}

		// Create the signature base string. Yes, the $params are double encoded.
		$baseString = $this->rfc3986Encode(strtoupper($httpMethod)) . '&' . $this->rfc3986Encode($this->normalizeUrl($url)) . '&' . $this->rfc3986Encode($this->oauthHttpBuildQuery($params));

		return $baseString;
	}

	/**
	 * Encode input per RFC 3986
	 * @param string|array $raw_input
	 * @return string|array properly rfc3986 encoded raw_input
	 * If an array is passed in, rfc3896 encode all elements of the array.
	 * @link http://oauth.net/core/1.0/#encoding_parameters
	 */
	public function rfc3986Encode($rawInput)
	{
		if(is_array($rawInput)){
			$encodedValues = array();
			foreach($rawInput as $input){
				$encodedValues[] = $this->rfc3986Encode($input);
			}
			return $encodedValues;
		}
		else if(is_scalar($rawInput)){
			return str_replace('%7E', '~', rawurlencode($rawInput));
		}
		else{
			return '';
		}
	}

	public function rfc3986Decode($rawInput)
	{
		return rawurldecode($rawInput);
	}

	/**
	 * Build a query parameter string according to OAuth Spec.
	 * @param array $params an array of query parameters
	 * @return string all the query parameters properly sorted and encoded
	 * according to the OAuth spec, or an empty string if params is empty.
	 * @link http://oauth.net/core/1.0/#rfc.section.9.1.1
	 */
	public function oauthHttpBuildQuery($params, $excludeOauthParams=false)
	{
		$queryString = '';
		if (!empty($params)) {
			// rfc3986 encode both keys and values
			$keys = $this->rfc3986Encode(array_keys($params));
			$values = $this->rfc3986Encode(array_values($params));
			$params = array_combine($keys, $values);

			// Parameters are sorted by name, using lexicographical byte value ordering.
			// http://oauth.net/core/1.0/#rfc.section.9.1.1
			uksort($params, 'strcmp');

			// Turn params array into an array of "key=value" strings
			$kvpairs = array();
			foreach($params as $k => $v){
				if($excludeOauthParams && substr($k, 0, 5) == 'oauth'){
					continue;
				}
				if(is_array($v)){
					// If two or more parameters share the same name,
					// they are sorted by their value. OAuth Spec: 9.1.1 (1)
					natsort($v);
					foreach ($v as $valueForSameKey) {
					  array_push($kvpairs, ($k . '=' . $valueForSameKey));
					}
				}
				else{
					// For each parameter, the name is separated from the corresponding
					// value by an '=' character (ASCII code 61). OAuth Spec: 9.1.1 (2)
					array_push($kvpairs, ($k . '=' . $v));
				}
			}

			// Each name-value pair is separated by an '&' character, ASCII code 38.
			// OAuth Spec: 9.1.1 (2)
			$queryString = implode('&', $kvpairs);
		}

		return $queryString;
	}

	/**
	 * Build an OAuth header for API calls
	 * @param array $params an array of query parameters
	 * @return string encoded for insertion into HTTP header of API call
	 */
	public function buildOauthHeader($params, $realm='')
	{
		$header = 'Authorization: OAuth ';
		foreach ($params as $k => $v) {
			if(substr($k, 0, 5) == 'oauth'){
				$header .= ',' . $this->rfc3986Encode($k) . '="' . $this->rfc3986Encode($v) . '"';
			}
		}
		return $header;
	}

	/**
	 * Do an HTTP GET
	 * @param string $url
	 * @param int $port (optional)
	 * @param array $headers an array of HTTP headers (optional)
	 * @return array ($info, $header, $response) on success or empty array on error.
	 */
	public function doGet($url, $port=80, $headers=null)
	{
		$retarr = array();  // Return value

		$curlOpts = array(CURLOPT_URL => $url,
		                 CURLOPT_PORT => $port,
		                 CURLOPT_POST => false,
		                 CURLOPT_SSL_VERIFYHOST => false,
		                 CURLOPT_SSL_VERIFYPEER => false,
		                 CURLOPT_RETURNTRANSFER => true);

		if($headers)
			$curlOpts[CURLOPT_HTTPHEADER] = $headers;

		$response = $this->doCurl($curlOpts);

		if(!empty($response))
			$retarr = $response;

		return $retarr;
	}

	/**
	 * Do an HTTP POST
	 * @param string $url
	 * @param int $port (optional)
	 * @param array $headers an array of HTTP headers (optional)
	 * @return array ($info, $header, $response) on success or empty array on error.
	 */
	public function doPost($url, $postbody, $port=80, $headers=null)
	{
		$retarr = array();  // Return value

		$curl_opts = array(CURLOPT_URL => $url,
		                 CURLOPT_PORT => $port,
		                 CURLOPT_POST => true,
		                 CURLOPT_SSL_VERIFYHOST => false,
		                 CURLOPT_SSL_VERIFYPEER => false,
		                 CURLOPT_POSTFIELDS => $postbody,
		                 CURLOPT_RETURNTRANSFER => true);

		if ($headers)
			$curl_opts[CURLOPT_HTTPHEADER] = $headers;

		$response = $this->doCurl($curl_opts);

		if (!empty($response))
			$retarr = $response;

		return $retarr;
	}

	/**
	 * Make a curl call with given options.
	 * @param array $curl_opts an array of options to curl
	 * @return array ($info, $header, $response) on success or empty array on error.
	 */
	public function doCurl($curlOpts)
	{
		$retarr = array();  // Return value

		if(!$curlOpts){
			return $retarr;
		}

		// Open curl session
		$ch = curl_init();

		if(!$ch){
			return $retarr;
		}

		// Set curl options that were passed in
		curl_setopt_array($ch, $curlOpts);

		// Ensure that we receive full header
		curl_setopt($ch, CURLOPT_HEADER, true);

		if(false){ // debug?
			curl_setopt($ch, CURLINFO_HEADER_OUT, true);
			curl_setopt($ch, CURLOPT_VERBOSE, true);
		}

		$response = curl_exec($ch);

		// Check for errors
		if(curl_errno($ch)){
			$errno = curl_errno($ch);
			$errmsg = curl_error($ch);
			curl_close($ch);
			unset($ch);

			return $retarr;
		}

		// Get information about the transfer
		$info = curl_getinfo($ch);

		// Parse out header and body
		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $headerSize);
		$body = substr($response, $headerSize );

		// Close curl session
		curl_close($ch);
		unset($ch);

		// Set return value
		array_push($retarr, $info, $header, $body);

		return $retarr;
	}

	/**
	 * Parse a query string into an array.
	 * @param string $query_string an OAuth query parameter string
	 * @return array an array of query parameters
	 * @link http://oauth.net/core/1.0/#rfc.section.9.1.1
	 */
	public function oauthParseStr($queryString)
	{
	  $queryArray = array();

	  if (isset($queryString)) {

	    // Separate single string into an array of "key=value" strings
	    $kvpairs = explode('&', $queryString);

	    // Separate each "key=value" string into an array[key] = value
	    foreach ($kvpairs as $pair) {
	      list($k, $v) = explode('=', $pair, 2);

	      // Handle the case where multiple values map to the same key
	      // by pulling those values into an array themselves
	      if (isset($queryArray[$k])) {
	        // If the existing value is a scalar, turn it into an array
	        if (is_scalar($queryArray[$k])) {
	          $queryArray[$k] = array($queryArray[$k]);
	        }
	        array_push($queryArray[$k], $v);
	      } else {
	        $queryArray[$k] = $v;
	      }
	    }
	  }

	  return $queryArray;
	}

	/**
	 * Make the URL conform to the format scheme://host/path
	 * @param string $url
	 * @return string the url in the form of scheme://host/path
	 */
	public function normalizeUrl($url)
	{
		$parts = parse_url($url);

		$scheme = $parts['scheme'];
		$host = $parts['host'];
		$port = isset($parts['port']) ? $parts['port'] : null;
		$path = $parts['path'];

		if (!$port) {
			$port = ($scheme == 'https') ? '443' : '80';
		}

		if (($scheme == 'https' && $port != '443') || ($scheme == 'http' && $port != '80')) {
			$host = "$host:$port";
		}

		return "$scheme://$host$path";
	}

	/**
	 * Get an access token using a request token and OAuth Verifier.
	 * @param string $consumer_key obtained when you registered your app
	 * @param string $consumer_secret obtained when you registered your app
	 * @param string $requestToken obtained from getreqtok
	 * @param string $requestTokenSecret obtained from getreqtok
	 * @param string $oauthVerifier obtained from step 3
	 * @param bool $usePost use HTTP POST instead of GET
	 * @param bool $useHmacSha1Sig use HMAC-SHA1 signature
	 * @return array of response parameters or empty array on error
	 */
	public function getAccessToken($requestToken, $requestTokenSecret, $oauthVerifier, $useHmacSha1Sig=true, $usePost=false)
	{
		$retarr = array();  // return value
		$response = array();

		$params['oauth_version'] = '1.0';
		$params['oauth_nonce'] = mt_rand();
		$params['oauth_timestamp'] = time();
		$params['oauth_consumer_key'] = $this->consumerKey;
		$params['oauth_token']= $requestToken;
		$params['oauth_verifier'] = $oauthVerifier;

		// compute signature and add it to the params list
		if($useHmacSha1Sig){
			$params['oauth_signature_method'] = 'HMAC-SHA1';
			$params['oauth_signature'] = $this->oauthComputeHmacSig('GET', $this->accessTokenUrl, $params, $requestTokenSecret);
		}

		$queryParameterString = $this->oauthHttpBuildQuery($params);

		// POST or GET the request
		if($usePost) {
			//$headers[] = 'Content-Type: application/x-www-form-urlencoded';
			$response = $this->doPost($this->accessTokenUrl, $queryParameterString, 443);
		}
		else {
			$requestUrl = $this->accessTokenUrl . ($queryParameterString ? ('?' . $queryParameterString) : '' );
			$response = $this->doGet($requestUrl, 443);
		}

		// extract successful response
		if (!empty($response)) {
			list($info, $header, $body) = $response;
			$bodyParsed = $this->oauthParseStr($body);
			$retarr = $response;
			$retarr[] = $bodyParsed;
		}

		return $retarr;
	}
}