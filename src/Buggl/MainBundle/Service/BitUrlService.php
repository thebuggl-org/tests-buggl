<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\LocalAuthor;

class BitUrlService
/* make a URL small */
{
function make_bitly_url($url,$login,$appkey,$format = 'xml',$version = '2.0.1')
{
	//create the URL
	$bitly = 'http://api.bit.ly/shorten?version='.$version.'&longUrl='.urlencode($url).'&login='.$login.'&apiKey='.$appkey.'&format='.$format;
	//echo $url;
	//get the url
	//could also use cURL here
	$response = file_get_contents($bitly);
	//prinT_r($response); die;
	//parse depending on desired format
	if(strtolower($format) == 'json')
	{
		$json = @json_decode($response,true);
		return $json['results'][$url]['shortUrl'];
	}
	else //xml
	{
		$xml = simplexml_load_string($response);
		return 'http://bit.ly/'.$xml->results->nodeKeyVal->hash;
	}
}
}

/* usage */


// returns:  http://bit.ly/11Owun