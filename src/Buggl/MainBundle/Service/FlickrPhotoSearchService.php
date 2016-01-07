<?php

namespace Buggl\MainBundle\Service;

class FlickrPhotoSearchService
{
	private $rest_endpoint = 'http://api.flickr.com/services/rest/';
	private $api_key = "3b96caf266fe688074b85045bd643b3e";
	private $secret = "a153715ed50db4f1";
	private $default = array();
	protected $response = array();
	protected $token = null;

	public function __construct()
	{
		$this->default = array(
			'method' => 'flickr.photos.search',
			'api_key' => $this->api_key,
			'format' => 'json',
			'nojsoncallback' => 1,
			'page' => 1,
			'per_page' => 25
			);
	}

	public function search($options = array())
	{

		$args = array_merge($this->default, $options);
		if (!empty($this->token)) {
			$args = array_merge($args, array("auth_token" => $this->token));
		} elseif (!empty($_SESSION['phpFlickr_auth_token'])) {
			$args = array_merge($args, array("auth_token" => $_SESSION['phpFlickr_auth_token']));
		}

		ksort($args);
		$this->signRequest($args);
		$this->sendGetRequest($args);
		return $this;
	}

	public function getResponse()
	{
		return $this->response;
	}

	private function signRequest(&$args = array())
	{
		$auth_sig = "";
		foreach ($args as $key => $data) {
			if ( is_null($data) ) {
				unset($args[$key]);
				continue;
			}
			$auth_sig .= $key . $data;
		}
		if (!empty($this->secret)) {
			$api_sig = md5($this->secret . $auth_sig);
			$args['api_sig'] = $api_sig;
		}

	}

	private function sendGetRequest($args = array())
	{
		// echo "<pre>";
		// var_dump($args);
		// exit;
		$url = 'http://api.flickr.com/services/rest/';
		$qstring = "?";
		foreach($args as $key => $value)
		{
			$qstring .= "$key=".urlencode(trim($value))."&";
			// $qstring .= "$key=$value&";
		}

		$ch = curl_init();

		// Set query data here with the URL
		curl_setopt($ch, CURLOPT_URL, $this->rest_endpoint . rtrim($qstring, "&"));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$content = trim(curl_exec($ch));
		curl_close($ch);
		$result = json_decode($content);
		// var_dump($result);
		// exit;

		if($result->stat == 'ok')
		{
		    $photos = $result->photos->photo;
		    $this->response['count'] = count($photos);
		    $this->response['pages'] = $result->photos->pages;
		    $this->response['page'] = $args['page'];
		    if(count($photos) > 0)
		    {
			    foreach($photos as $photo)
			    {
			    	$farmId = $photo->farm;
			        $serverId = $photo->server;
			        $id = $photo->id;
			        $secret = $photo->secret;
			        $imagePathThumbnail = 'http://farm'.$farmId.'.static.flickr.com/'.$serverId.'/'.$id.'_'.$secret.'_m.jpg';
			        $imagePathLarge = 'http://farm'.$farmId.'.static.flickr.com/'.$serverId.'/'.$id.'_'.$secret.'_b.jpg';

			        $this->response['photos'][] = array(
			        	'title' => $photo->title,
			        	'thumbnail' => $imagePathThumbnail,
			        	'large' => $imagePathLarge,
			        	'filename' => $id.'_'.$secret.'_b.jpg',
			        	'web_path' => 'http://farm'.$farmId.'.static.flickr.com/'.$serverId.'/'
			        	);

			    }
		    }
		    else
		    {
		        $this->response['photos'] = array();
		    }
		}
		else
		{
			$this->response['error_message'] = $result->message;
		}
	}
}