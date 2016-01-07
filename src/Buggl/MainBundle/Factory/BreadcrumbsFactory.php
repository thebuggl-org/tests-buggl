<?php

namespace Buggl\MainBundle\Factory;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Buggl\MainBundle\Helper\FrontendProfileViewBreadcrumbs;
use Buggl\MainBundle\Helper\FrontendResultPageBreadcrumbs;
use Buggl\MainBundle\Helper\FrontendCountryGuidePageBreadcrumbs;

class BreadcrumbsFactory
{
    private $class;

    private $breadcrumbTypes;

    public function __construct()
    {
        $this->class = null;

        $this->breadcrumbTypes = array('profile_view','result_page','country_guide_page');
    }

    public function build($type)
    {      
        if(!in_array($type, $this->breadcrumbTypes)){
            throw new NotFoundHttpException("$type does not exist");    
        }

        if($type == 'profile_view'){
            $this->class = new FrontendProfileViewBreadcrumbs();
        }
        else if($type == 'result_page'){
            $this->class = new FrontendResultPageBreadcrumbs();
        }
        else if($type == 'country_guide_page'){
            $this->class = new FrontendCountryGuidePageBreadcrumbs();
        }
            

        return $this->class;
    }
}