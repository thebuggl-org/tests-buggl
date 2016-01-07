<?php

namespace Buggl\MainBundle\Service;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BreadcrumbService
{
    private $breadcrumbs;
    private $isSet;
    private $breadcrumbsFactory;

    public function __construct($breadcrumbsFactory)
    {
        $this->breadcrumbs = null;
        $this->isSet = false;

        $this->breadcrumbsFactory = $breadcrumbsFactory;
    }


    public function init($type)
    {
        $this->breadcrumbs = $this->breadcrumbsFactory->build($type);

        return $this;
    }


    public function setParameters($parameters = array())
    {
        $this->isSet = $this->breadcrumbs->set($parameters);

        return $this;
    }


    public function getBreadcrumbs()
    {

        if(!$this->isSet){
            throw new  NotFoundHttpException("Please set breadcrumbs parameters correctly");
        }

        return $this->breadcrumbs->getBreadcrumbs();
    }
}