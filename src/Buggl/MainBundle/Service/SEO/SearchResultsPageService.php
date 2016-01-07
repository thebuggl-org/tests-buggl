<?php

namespace Buggl\MainBundle\Service\SEO;


class SearchResultsPageService
{
	private $meta = array();

	private $location = null;
	private $activity = null;

	public function __construct(){}

	public function buildMetaAttributes($params)
	{
		$this->location = ( isset($params['location']) AND (0 < strlen($params['location'])) ) ? $params['location'] : null;
		$this->activity = ( isset($params['activity']) AND (0 < strlen($params['activity'])) ) ? $params['activity'] : null;
		
		return $this->meta = array(
			'title' => $this->generateTitle(),
			'description' => $this->generateMetaDescription()
			);
	}

	private function generateTitle()
	{
		if( !is_null($this->location) AND !is_null($this->activity) )
			return sprintf("Buggl Search: %s in %s Travel Guides", ucwords($this->activity), ucwords($this->location) );
		else if( is_null($this->location) AND !is_null($this->activity) )
			return sprintf("Buggl Search: %s Travel Guides", ucwords($this->activity) );
		else if( is_null($this->activity) AND !is_null($this->location) )
			return sprintf("Buggl Search: %s Travel Guides", ucwords($this->location) );

		return 'Buggl Search: Travel Guides';
	}

	private function generateMetaDescription()
	{
		if( !is_null($this->location) AND !is_null($this->activity) )
			return sprintf("Learn how to %s in %s through Insider travel guides on Buggl.com. Find new places and ways to experience the things you love.", ucwords($this->activity), ucwords($this->location) );
		else if( is_null($this->location) AND !is_null($this->activity) )
			return sprintf("%s travel guides built for travelers like you. Find a new, unique place to travel and discover.", ucwords($this->activity) );
		else if( is_null($this->activity) AND !is_null($this->location) )
			return sprintf("Unique travel guides for %s from Buggl Insiders. Find your perfect guide to new ways of traveling and experiencing the world.", ucwords($this->location) );

		return "";
	}

}