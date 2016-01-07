<?php

namespace Buggl\MainBundle\Twig;

class EguideHelperExtension extends \Twig_Extension
{
	private $entityManager;
	private $eguideAndSpotsNativeQuery;
	
	public function __construct($entityManager,$eguideAndSpotsNativeQuery)
	{
		$this->entityManager = $entityManager;
		$this->eguideAndSpotsNativeQuery = $eguideAndSpotsNativeQuery;
	}
	
    public function getFilters()
    {
        return array(
			'tags' => new \Twig_Filter_Method($this, 'getTags'),
			'getSpotPhoto' => new \Twig_Filter_Method($this, 'getSpotPhoto'),
			'getEguidePhoto' => new \Twig_Filter_Method($this, 'getEguidePhoto'),
			'getSpotDetail' => new \Twig_Filter_Method($this, 'getSpotDetail'),
			'getItinerary' => new \Twig_Filter_Method($this, 'getItinerary'),
			'getTypeSlug' => new \Twig_Filter_Method($this, 'getTypeSlug'),
			'getSpotDetailsForHomepage' => new \Twig_Filter_Method($this, 'getSpotDetailsForHomepage'),
			'isSpotFeaturedInGuide' => new \Twig_Filter_Method($this, 'isSpotFeaturedInGuide'),
			'getGoodFor' => new \Twig_Filter_Method($this, 'getGoodFor'),
			'getCategories' => new \Twig_Filter_Method($this, 'getCategories'),
			'renderPrice' => new \Twig_Filter_Method($this, 'renderPrice'),
			'checkImage' => new \Twig_Filter_Method($this, 'checkImageAvailability'),
			'budgetAsText' => new \Twig_Filter_Method($this, 'getBudgetAsText'),
        );
    }
	
	public function getTags($spotDetail)
	{
		$tags = $this->entityManager->getRepository('BugglMainBundle:SpotDetailToSpotLike')->findBy(array('spot_detail' => $spotDetail));

		return $tags;
	}
	
	public function getSpotDetail($eguideToSpot)
	{
		$spotDetail = $this->entityManager->getRepository('BugglMainBundle:SpotDetail')->findOneBy(array('spot' => $eguideToSpot->getSpot(), 'localAuthor' => $eguideToSpot->getEguide()->getLocalAuthor()));
		
		return $spotDetail;
	}
	
	public function getEguidePhoto($eguide,$type=1)
	{
		$photo = $this->entityManager->getRepository('BugglMainBundle:EGuidePhoto')->findOneActiveByEguideAndType($eguide,$type);
		
		if(is_null($photo)){
			return '/bundles/bugglmain/images/custom/cover-guide.jpg';
		}
		
		return $photo->getPhoto();
	}
	
	public function getSpotDetailsForHomepage($eguide)
	{
		$ids = $this->eguideAndSpotsNativeQuery->getFeaturedSpotDetailsId($eguide);
		$spotDetails = $this->entityManager->getRepository('BugglMainBundle:SpotDetail')->findByIds($ids);
		
		return $spotDetails;
	}
	
	public function getSpotPhoto($spot,$localAuthor)
	{
		$spotDetail = $this->entityManager->getRepository('BugglMainBundle:SpotDetail')->getByAuthorAndSpot($localAuthor,$spot);
		
		if(is_null($spotDetail)){
			return '/bundles/bugglmain/images/custom/cover-guide.jpg';
		}
		
		return $spotDetail->getPhoto();
	}
	
	public function getItinerary($eguide)
	{
		return $this->entityManager->getRepository('BugglMainBundle:Itinerary')->findByGuide($eguide);
	}
	
	// temporary
	public function getTypeSlug($spot,$localAuthor)
	{
		$detail = $this->entityManager->getRepository('BugglMainBundle:SpotDetail')->findBySpotAndAuthor($spot,$localAuthor);
		
		if(is_null($detail))
			return 'local-places';
				
		return $detail->getSpotType()->getName();
	}

	public function isSpotFeaturedInGuide($spot,$eguide)
	{
		$eguideToSpotObjects = $this->entityManager->getRepository('BugglMainBundle:EGuideToSpot')->getByEguideAndSpot($eguide,$spot);

		$featured = false;
		foreach($eguideToSpotObjects as $each){
			$featured = $each->getIsFeatured();
			if($featured){
				break;
			}
		}
		
		return $featured;
	}
	
	public function getGoodFor($eguide)
	{
		$eguideToGoodForObjects = $this->entityManager->getRepository('BugglMainBundle:EguideToGoodFor')->findByEguide($eguide);
		
		$goodFor = array();
		foreach($eguideToGoodForObjects as $eguideToGoodForObject){
			$goodFor[] = $eguideToGoodForObject->getGoodFor()->getName();
		}
		
		return implode(', ',$goodFor);
	}
	
	public function getCategories($eguide)
	{
		$categories = $eguide->getCategories();
		
		$categoryNames = array();
		foreach($categories as $category){
			if( $category->getIsPublished() ){
				$categoryNames[] = $category->getName();
			}
		}
		
		return implode(', ',$categoryNames);
	}
	
	public function renderPrice($price)
	{
		if($price == 0){
			return 'FREE';
		}
		
		return '$'.number_format($price,2,'.',',');
	}
	
    public function getName()
    {
        return 'buggl_eguide_helper_extension';
    }

    public function checkImageAvailability($url)
    {
    	$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL,$url);
	    // don't download content
	    curl_setopt($ch, CURLOPT_NOBODY, 1);
	    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	    // echo curl_getinfo($ch, CURLINFO_HTTP_CODE);

	    if(curl_exec($ch)!==FALSE)
	    {
	        return $url;
	    }
	    else
	    {
	        return "/bundles/bugglmain/images/custom/default-spot.jpg";
	    }
    }
	
	public function getBudgetAsText($budget)
	{
		$budgetValues = array(
			0 => '',
			1 => 'Cheap',
			2 => 'Value',
			3 => 'Mid-range',
			4 => 'Expensive',
			5 => 'Luxury',
		);
		
		return $budgetValues[$budget];
	}
}