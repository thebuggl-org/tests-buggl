<?php

namespace Buggl\MainBundle\Service\SEO;


class FullSpotPageService
{
	private $meta = array();
	private $em = null;

	public function __construct($_entityManager)
	{
		$this->em = $_entityManager;
	}

	public function buildMetaAttributes(\Buggl\MainBundle\Entity\SpotDetail $spotDetail)
	{
		return $this->meta = array(
			'title' => $this->generateTitle($spotDetail),
			'description' => $this->generateMetaDescription($spotDetail)
			);
	}
	
	private function generateTitle($spotDetail)
	{
		// [Spot Name] - [Keed to Know Tags]
		$likeTags = "";
		foreach($spotDetail->getSpotLikes() as $like)
		{
			$likeTags .= (0 < strlen($likeTags)) ? ", " : "";
			$likeTags .= $like->getName();
		}
		return $spotDetail->getSpot()->getName() . " - " . $likeTags;
	}
	
	private function generateMetaDescription($spotDetail)
	{
		$description = strip_tags($spotDetail->getDescription());
		return ( 160 < strlen($description) ) ? substr($description, 0, 156) . "..." : $description;
	}
	
}