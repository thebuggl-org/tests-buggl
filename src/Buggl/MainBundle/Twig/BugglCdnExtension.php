<?php

namespace Buggl\MainBundle\Twig;

class BugglCdnExtension extends \Twig_Extension
{
	private $domain = "www.buggl.com";
	private $container = null;

	public function __construct($_container)
    {
        $this->container = $_container;
        // $request = $this->container->get('request');
        // $this->domain = $request->getHttpHost();
    }

	public function getFilters()
    {
        return array(
            's3Cdn' => new \Twig_Filter_Method($this, 'awsCdn')
        );
    }

    public function awsCdn($url)
    {
        /**
        temporary fix for the cdn problem referred by Jonas
        */
        return $this->cdnTempFix($url);

        // return str_replace("www.buggl-local.com", "static1.buggl.com", $url);
        if( strpos($url, $this->domain) )
        	return str_replace($this->domain, "static1.buggl.com", str_replace("/app_dev.php/", "", $url) );

        return "http://static1.buggl.com/" . str_replace("/app_dev.php/", "", $url);
    }

    private function cdnTempFix($url)
    {
        if( strpos($url, $this->domain) )
            return str_replace($this->domain, "buggl.s3.amazonaws.com", str_replace("/app_dev.php/", "", $url) );

        // return "https://s3.amazonaws.com/buggl-assets/" . str_replace("/app_dev.php/", "", $url);
        return "http://buggl.s3.amazonaws.com/" . str_replace("/app_dev.php/", "", $url);
        
    }

    public function getName()
    {
        return 'buggl_cdn_extension';
    }
}