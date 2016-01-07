<?php

namespace Buggl\MainBundle\Service\SEO;


class FullGuidePageService
{
	private $meta = array();
	private $em = null;

	public function __construct($_entityManager)
	{
		$this->em = $_entityManager;
	}

	public function buildMetaAttributes(\Buggl\MainBundle\Entity\EGuide $eguide, $section = 'overview')
	{
		return $this->meta = array(
			'title' => $this->generateTitle($eguide, $section),
			'description' => $this->generateMetaDescription($eguide, $section)
			);
	}

	private function generateTitle($eguide, $section)
	{
		if('overview' == $section)
			return $eguide->getPlainTitle();
		else if('local-secret' == $section)
			return "Local Secrets - ".$eguide->getPlainTitle();
		else if('itinerary' == $section)
			return "Itinerary - ".$eguide->getPlainTitle();
		
		return "";
	}

	private function generateMetaDescription($eguide, $section)
	{
		if('overview' == $section)
		{
			$desc = $eguide->getExcerpts();
			$egtc = $eguide->getCategories();
			$categories = "";
			foreach($egtc as $cObj)
			{
				$categories .= (0 < strlen($categories)) ? ", " : "";
				$categories .= $cObj->getName();
			}

			$egtgf = $eguide->getGoodFor();
			$goodFor = "";
			foreach($egtgf as $gfObj)
			{
				$goodFor .= (0 < strlen($goodFor)) ? ", " : "";
				$goodFor .= $gfObj->getName();
			}
			return $this->sanitizeString($desc) . $eguide->getTripTheme()->getName() . ". " . $categories . ". " . $goodFor . "." ;
		}
		else if('local-secret' == $section)
		{
			return sprintf("Explore the Local Secerts in %s", $eguide->getPlainTitle());
		}
		else if('itinerary' == $section)
		{
			$author = $eguide->getLocalAuthor()->getName(true);
			$desc = sprintf("An itinerary for %s guide: %s", $author, $eguide->getPlainTitle());
			
			return $desc;
		}

		return "";
	}

	private function sanitizeString($str)
	{
		return filter_var($str, FILTER_SANITIZE_STRING);
	}
}