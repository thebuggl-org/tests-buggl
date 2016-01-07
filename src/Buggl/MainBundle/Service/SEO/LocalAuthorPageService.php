<?php

namespace Buggl\MainBundle\Service\SEO;


class LocalAuthorPageService
{
	const TITLE = 'title';
	const DESC  = 'description';
	private $em = null;
	
	public function __construct($_entityManager)
	{
		$this->em = $_entityManager;
	}
	
	public function buildMetaAttributes(\Buggl\MainBundle\Entity\LocalAuthor $author)
	{
		return $this->meta = array(
			self::TITLE => $this->generateTitle($author),
			self::DESC => $this->generateMetaDescription($author)
			);
	}
	
	private function generateTitle($author)
	{
		return sprintf('%s - Insider at Buggl.com', $author->getName());
	}
	
	private function generateMetaDescription($author)
	{
		$name = $author->getName();
		if( $author->getLocation() )
		{
			$city = $author->getLocation()->getCity();
			$location = $city->getName() . ", " . $city->getCountry()->getName();
			
			if(!is_null($author->getProfile()) && !is_null($author->getProfile()->getLocalSince()))
				$localSince = $author->getProfile()->getLocalSince();
			else
				$localSince = '1990'; // added default value, LOL
			
			$totalPublishedGuides = $this->em->getRepository("BugglMainBundle:EGuide")->countByLocalAuthorAndStatus($author, 2);
			
			return "Meet Buggl Insider $name a local in $location since $localSince. Explore $totalPublishedGuides guides created by $name and experience a new way to travel.";
		}
		else {
			$totalPublishedGuides = $this->em->getRepository("BugglMainBundle:EGuide")->countByLocalAuthorAndStatus($author, 2);
			return "Meet Buggl Insider $name. Explore $totalPublishedGuides guides created by $name and experience a new way to travel.";
		}
		
	}
}