<?php

namespace Buggl\MainBundle\Helper;

use Buggl\MainBundle\Interfaces\BugglBreadcrumbs;


class FrontendCountryGuidePageBreadcrumbs implements BugglBreadcrumbs
{

    private $requiredKeys = null;
    private $breadcrumbs = null;
    private $current = null;

    public function __construct()
    {
        $this->requiredKeys = array('country-name');
        $this->links = array();
        $this->current = '';
    }   


    public function set($parameters=array())
    {
        foreach( $this->requiredKeys as $key )
        {
            if(!array_key_exists($key, $parameters)){
                return false;
            }
        }

        $this->current = $parameters['country-name'];

        return true;
    }

    public function getBreadcrumbs()
    {
        return array('links' => $this->links,
                      'current' => $this->current
                     );
    }
}