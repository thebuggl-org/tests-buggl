<?php

namespace Buggl\MainBundle\Helper;

class BugglSlugifier
{
    private $dashChars = array("-","$","&","+",",","/",":",";","=","?","@","<",">","{","}","|",'\\','^','~','[',']','%',' ',',','(',')','.');

    private $removeChars = array('#','`','"',"'",'!');

    private $string;

    private $keywords;

    public function __construct()
    {
        $this->string = '';
        $this->keywords = null;
    }

    public function format($string=''){
        $this->string = strtolower( trim( preg_replace('/\s+/', ' ', $string) ) );
        $this->string = str_replace($this->removeChars,'',$this->string);
        $this->string = str_replace($this->dashChars,'-', $this->string);
        $this->string = preg_replace('/--*/','-',$this->string);
        $this->string = preg_replace('/-$/','',$this->string);

        return $this;
    }

    public function append($string='',$separator="-")
    {
        $this->string .= "$separator$string";

        return $this;
    }

    public function explode($string='')
    {
        $this->keywords = explode('-',$string);

        return $this;
    }

    public function reverse()
    {
        $this->keywords = array_reverse($this->keywords);

        return $this;
    }

    public function clear()
    {
        $this->string = '';
        $this->keywords = null;

        return $this;
    }

    public function get($id=0)
    {
        return isset($this->keywords[$id]) ? $this->keywords[$id] : null;
    }

    public function getSlug()
    {
        return str_replace(' ', '-', $this->string);
    }

    public function getSlugText()
    {
        return str_replace('-', ' ', $this->string);
    }
}