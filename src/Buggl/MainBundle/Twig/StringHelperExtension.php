<?php

namespace Buggl\MainBundle\Twig;

class StringHelperExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
			'truncate' => new \Twig_Filter_Method($this, 'truncate'),
			'fillBlank' => new \Twig_Filter_Method($this, 'fillBlank'),
			'hideDetails' => new \Twig_Filter_Method($this, 'hideDetails'),
			'nullObjectPlaceHolder' => new \Twig_Filter_Method($this, 'nullObjectPlaceHolder'),
            'nullObjectReturnDefault' => new \Twig_Filter_Method($this, 'nullObjectReturnDefault'),
            'yearsAgo' => new \Twig_Filter_Method($this, 'yearsAgo'),
            'transactionType' => new \Twig_Filter_Method($this, 'transactionType'),
			'getAppropriateArticleFor' => new \Twig_Filter_Method($this, 'getAppropriateArticleFor'),
        );
    }

	public function truncate($string,$length,$addDotsToEnd=true)
    {
		if (strlen($string) > $length) {
		   	$string = substr($string, 0, strpos(wordwrap($string, $length), "\n"));
			if($addDotsToEnd){
				$string = $string.'...';
			}
		}

		return $string;
    }

    /**
     * Gets type of transaction in payment: Sold or Purchased
     * @param  LocalAuthor $seller []
     * @param  LocalAuthor $user   []
     *
     * @return String
     *
     * @author Vincent Farly G. Taboada <farly.taboada@goabroad.com>
     */
    public function transactionType($seller,$user)
    {
        if ($seller->getId() === $user->getId()) {
            return 'S';
        }

        return 'P';
    }

    public function yearsAgo($date,$nYears,$format="Y-m-d")
    {
        $dateNow = $date->getTimestamp();

        return date($format,strtotime("-{$nYears} years",$dateNow));
    }

    public function nullObjectReturnDefault($object,$default)
    {
        if(!is_null($object)){
            return $object;
        }

        return $default;
    }

	public function nullObjectPlaceHolder($object,$method,$defaultReturn='')
	{
		if(is_null($object) || !method_exists($object,$method) || is_null($object->$method()))
			return $defaultReturn;

		return $object->$method();
	}

	public function fillBlank($string)
	{
		if(is_null($string) || trim($string) == '')
			return '-';

		return $string;
	}

	public function hideDetails($string, $replacementCharacter='X', $visibleLimit=2)
	{
		$coveredString = "";
		for ($i = 0; $i<strlen($string); $i++)  {
			$char = substr($string,$i,1);
			if($i + $visibleLimit >= strlen($string) || $char == '')
				$coveredString .= $char;
			else
				$coveredString .= "<span>".$replacementCharacter."</span>";
		}

		return $coveredString;
	}
	
	public function getAppropriateArticleFor($object,$method)
	{
		if(is_null($object) || !method_exists($object,$method) || is_null($object->$method()))
			return 'a';
		
		$vowels = array('a','e','i','o','u');		
		$string = $object->$method();
		if(!empty($string)){
			$startLetter = substr($string,0,1);
			if(in_array($startLetter,$vowels)){
				return 'an';
			}
		}
		
		
		return 'a';
	}

    public function getName()
    {
        return 'buggl_string_helper_extension';
    }
}