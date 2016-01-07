<?php

namespace Buggl\MainBundle\Helper;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BugglSearchUrlEncoder
{
    private $keywords;
    private $isIncluded;
    private $parameters;

    public function __construct()
    {
        $this->keywords = array('country','activity','budget','duration','theme','page');
        $this->parameters = array();
    }

    public function clear()
    {
        $this->parameters = array();

        return $this;
    }

    public function setParameter($parameters = array())
    {
        foreach($parameters as $key => $value){
            if( in_array($key,$this->keywords)){
                $this->parameters[] = "$key=$value";    
            }
        }

        return $this;
    }

    public function getUrl()
    {
        return implode('.', $this->parameters);
    }

    public function decode($params = '')
    {
        $params = explode('.',$params);

        foreach($params as $param)
        {
            $value = explode("=",$param);

            
            if(in_array($value[0], $this->keywords) && count($value) == 2){
                $this->parameters[$value[0]] = $value[1];    
            }
        }

        return $this;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

}