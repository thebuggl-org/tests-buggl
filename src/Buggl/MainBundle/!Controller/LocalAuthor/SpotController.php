<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Buggl\MainBundle\Entity\EGuide;
// use Buggl\MainBundle\Entity\EGuideToCity;
use Buggl\MainBundle\Entity\EGuideToSpot;

use Buggl\MainBundle\Entity\City;
use Buggl\MainBundle\Entity\Spot;
use Buggl\MainBundle\Entity\SpotDetail;
use Buggl\MainBundle\Entity\SpotCategory;
use Buggl\MainBundle\Entity\SpotLike;
use Buggl\MainBundle\Entity\SpotToSpotLike;
use Buggl\MainBundle\Entity\SpotDetailToSpotLike;
use Buggl\MainBundle\Entity\SpotDetailToSpotCategory;
use Buggl\MainBundle\Entity\Itinerary;

use Buggl\MainBundle\Entity\EGuideToSpotDetail;
use Buggl\MainBundle\Entity\ItineraryToSpotDetail;

class SpotController extends Controller
{
	public function indexAction(Request $request)
	{
		$page = $request->query->get('page',1);
		$offset = 0;
		$limit = 15;
		$offset = $limit * ($page - 1);

		$activeType = $request->get('type','all');
		$type = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findOneByName(ucwords(str_replace('-',' ',$activeType)));
		$spotTypes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findAll();
		
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$paginator = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findByAuthorAndType($localAuthor, $type, $limit, $offset, true);
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));

		$softPageLimit = 8;
		$hardPageLimit = 12;

		$data = array(
			'activeTab' => 'spots',
			'paginator' => $paginator,
			'itemLimit' => $limit,
			'currentPage' => $page,
			'softPageLimit' => $softPageLimit,
			'hardPageLimit' => $hardPageLimit,
			'spotTypes' => $spotTypes,
			'newRequestCount' => $newEGuideRequestCount,
			'activeType' => $activeType
		);

		return $this->render('BugglMainBundle:LocalAuthor\Spot:index.html.twig', $data);
	}

	public function viewSpotDescriptionsAction(Request $request)
	{
		$spot = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findOneBy( array('id' => $request->get('id')) );
		$localAuthor = $this->get('security.context')->getToken()->getUser();

		$spotDetails = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findBy(array('spot' => $spot, 'localAuthor' => $localAuthor), array('dateAdded' => 'DESC'));
		$data = array(
			'spot' => $spot,
			'spotDetails' => $spotDetails
			);
		return $this->render('BugglMainBundle:LocalAuthor\Spot:descriptions.html.twig', $data);
	}

	public function findSpotAction(Request $request)
	{
		$id = $request->request->get('id');

		$spotDetail = $this->get('doctrine.orm.entity_manager')->find('BugglMainBundle:SpotDetail',$id);

		if (is_null($spotDetail)) {
			$data = array('success' => false);
		} else {
			$details = array(
				'name' => $spotDetail->getSpot()->getName(),
				'country' => $spotDetail->getSpot()->getCity()->getCountry()->getName(),
				'city' => $spotDetail->getSpot()->getCity()->getName(),
				'contact' => $spotDetail->getSpot()->getContactNumber(),
				'address' => $spotDetail->getSpot()->getAddress(),
				'content'=> $spotDetail->getDescription(),
				'bestthing' => $spotDetail->getBestThing(),
				'photo' => $spotDetail->getPhoto()
			);


			$data = array(
				'success' => true,
				'details' => $details
			);
		}

		return new JsonResponse($data, 200);
	}

	public function fetchSpotInfoAction(Request $request)
	{
		$id = $request->get('id',0);
		$spot = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findOneById($id);
		if($spot)
		{
			$info = array(
				'name' => $spot->getName(),
				'address' => $spot->getAddress(),
				'city' => $spot->getCity()->getName(),
				'contact_number' => $spot->getContactNumber(),
				'website' => $spot->getWebsite()
				);

			$data = array(
				'success' => true,
				'spot' => $info
			);
			return new JsonResponse($data, 200);
		}

		return new JsonResponse(array('success' => false), 200);
	}

	public function saveAction(Request $request)
	{
		$this->processSpot($request);

		$response = array('id' => $this->spot->getID(), 'redirectLink' => $this->redirectLink);
		
		return new JsonResponse($response,200);
	}

	public function updateAction(Request $request)
	{
		$this->redirectLink = null;
		
		$replaceSpot = (bool)$request->get('replaceSpot');
		if($replaceSpot)
			$this->replaceSpot($request);
		else 
			$this->processSpot($request);

		$response = array('id' => $this->spot->getID(), 'redirectLink' => $this->redirectLink);
		return new JsonResponse($response,200);
	}

	public function getAddSpotFormAction(Request $request)
	{
		// $data = $this->getAddSpotForm($request);
		// return new JsonResponse($data,200);
		// // exit;
		$slug = $request->get('slug');
		$spot_id = $request->get('spot_id', 0);
		$spot_detail_id = $request->get('spot_detail_id', 0);
		$day_num = $request->get('day_num', 0);
		$time_of_day = $request->get('time_of_day', 0);
		$pagename = $request->get('pagename', 'localplaces');
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$withTimeOfDay = (0 == $day_num) ? false : true;
		$eguide = false;
		$country = null;
		if(!is_null($slug)){
			$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($request->get('slug'));
			if($eguide)
			{
				$country = $eguide->getCountry();
				$cities = $eguide->getCities();
				// $noOfDays = $eguide->getDuration()->getNoOfDays();
			}
			else
			{
				$cities = $this->getDoctrine()->getRepository('BugglMainBundle:City')->findOneBySlug($request->get('slug'));
				// $noOfDays = 30;
			}
		}
		else {

			$location = $this->getDoctrine()->getRepository('BugglMainBundle:Location')->findByLocalAuthor($localAuthor);

			$cities = array();
			foreach($location as $iObj){
				$cities[] = $iObj->getCity();
			}
			// $noOfDays = null;
		}

		$types = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findAll();

		$hasSpot = false;
		$hasSpotDetail = false;
		$spot = null;
		$spotDetail = null;
		$categories = null;
		$likes = null;
		$sdCategories = null;
		$sdLikes = null;
		$schedule = null;
		if($spot_id)
		{	
			$spot = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findOneById($spot_id);
			if($spot && ($eguide || $spot_detail_id) )
			{
				$hasSpot = true;
				$spotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneById($spot_detail_id);
				if($spotDetail)
				{
					$hasSpotDetail = true;
					$spotType = $spotDetail->getSpotType();
					$sdToCategories = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetailToSpotCategory')->findBy(array('spot_detail' => $spotDetail));
					$sdToLikes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetailToSpotLike')->findBy(array('spot_detail' => $spotDetail));

					foreach($sdToCategories as $sdtc){
						$sdCategories[$sdtc->getSpotCategory()->getId()] = $sdtc;
					}

					foreach($sdToLikes as $sdtl){
						$sdLikes[$sdtl->getSpotLike()->getId()] = $sdtl;
					}

					// $categories = $this->getDoctrine()->getRepository('BugglMainBundle:SpotCategory')->findBy(array('spotType' => $spotType, 'status' => 1));
					// $likes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotLike')->findBy(array('spotType' => $spotType, 'status' => 1));

					$categories = $this->getDoctrine()->getRepository('BugglMainBundle:SpotCategory')->findBySpotType($spotType, $localAuthor);
					$likes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotLike')->findBySpotType($spotType, $localAuthor);
					
					if($withTimeOfDay)
					{
						$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $day_num));
						$schedule = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->findOneBy(array('itinerary' => $itinerary, 'spotDetail' => $spotDetail));
					}
				}
			}
		}

		$data['html'] = $this->renderView("BugglMainBundle:LocalAuthor/Spot:addSpotForm.html.twig",
			array('cities' => $cities,
				'withTimeOfDay' => $withTimeOfDay,
				'time_of_day' => $time_of_day,
				'types' => $types,
				'country' => $country,
				'hasSpot' => $hasSpot,
				'hasSpotDetail' => $hasSpotDetail,
				'spot' => $spot,
				'spotDetail' => $spotDetail,
				'schedule' => $schedule,
				'sdLikes' => $sdLikes,
				'sdCategories' => $sdCategories,
				'categories' => $categories,
				'likes' => $likes));
		return new JsonResponse($data,200);
	}

	private function getAddSpotForm(Request $request)
	{
		$slug = $request->get('slug');
		$spot_id = $request->get('spot_id', 0);
		// echo $slug;

		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$withTimeOfDay = false;
		$eguide = false;
		$country = null;
		if(!is_null($slug)){
			$withTimeOfDay = true;
			$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($request->get('slug'));
			if($eguide)
			{
				$country = $eguide->getCountry();
				$cities = $eguide->getCities();
				// $noOfDays = $eguide->getDuration()->getNoOfDays();
			}
			else
			{
				$cities = $this->getDoctrine()->getRepository('BugglMainBundle:City')->findOneBySlug($request->get('slug'));
				// $noOfDays = 30;
			}
		}
		else {

			$location = $this->getDoctrine()->getRepository('BugglMainBundle:Location')->findByLocalAuthor($localAuthor);

			$cities = array();
			foreach($location as $iObj){
				$cities[] = $iObj->getCity();
			}
			// $noOfDays = null;
		}

		$types = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findAll();

		$hasSpot = false;
		$spot = null;
		$spotDetail = null;
		$categories = null;
		$likes = null;
		$sdCategories = null;
		$sdLikes = null;
		if($spot_id)
		{
			$hasSpot = true;
			$spot = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findOneById($spot_id);
			// $spotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneBy(array('spot' => $spot, 'localAuthor' => $localAuthor));
			if($eguide)
			{
				$egtsd = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->findOneBy(array('eGuide' => $eguide));
				if($egtsd)
				{
					$spotDetail = $egtsd->getSpotDetail();
					$spotType = $spotDetail->getSpotType();
					$sdToCategories = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetailToSpotCategory')->findBy(array('spot_detail' => $spotDetail));
					$sdToLikes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetailToSpotLike')->findBy(array('spot_detail' => $spotDetail));

					foreach($sdToCategories as $sdtc){
						$sdCategories[$sdtc->getSpotCategory()->getId()] = $sdtc;
					}

					foreach($sdToLikes as $sdtl){
						$sdLikes[$sdtl->getSpotLike()->getId()] = $sdtl;
					}

					$categories = $this->getDoctrine()->getRepository('BugglMainBundle:SpotCategory')->findBy(array('spotType' => $spotType, 'status' => 1));
					$likes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotLike')->findBy(array('spotType' => $spotType, 'status' => 1));
				}
			}
		}

		$data['html'] = $this->renderView("BugglMainBundle:LocalAuthor/Spot:addSpotForm.html.twig",
			array('cities' => $cities,
				'withTimeOfDay' => $withTimeOfDay,
				'types' => $types,
				'country' => $country,
				'hasSpot' => $hasSpot,
				'spot' => $spot,
				'spotDetail' => $spotDetail,
				'sdLikes' => $sdLikes,
				'sdCategories' => $sdCategories,
				'categories' => $categories,
				'likes' => $likes));
		// return new JsonResponse($data,200);
		// exit;
	}

	public function getEditSpotFormAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();

		$location = $this->getDoctrine()->getRepository('BugglMainBundle:Location')->findByLocalAuthor($localAuthor);
		$cities = array();
		foreach($location as $iObj){
			$cities[] = $iObj->getCity();
		}

		$spot = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findOneBy(array('id' => $request->get('spot_id')));
		$spotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneBy(array('spot' => $spot, 'localAuthor' => $localAuthor));

		$types = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findAll();
		$categories = $this->getDoctrine()->getRepository('BugglMainBundle:SpotCategory')->findBySpotType($spotDetail->getSpotType());
		$likes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotLike')->findBySpotType($spotDetail->getSpotType());
		$spotLikes = array();
		foreach($spotDetail->getSpotLikes() as $sLike)
		{
			$spotLikes[$sLike->getId()] = $sLike;
		}
		// $spotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->getByAuthorAndSpot($localAuthor, $spot);

		$data['html'] = $this->renderView("BugglMainBundle:LocalAuthor/Spot:editSpotForm.html.twig", array('cities' => $cities, 'types' => $types, 'categories' => $categories, 'likes' => $likes, 'spotLikes' => $spotLikes, 'spotDetail' => $spotDetail));
		return new JsonResponse($data,200);
	}

	public function getSpotsListAction(Request $request)
	{ 
		$activeType = $request->get('spotType');
		$currentPage = $request->get('page',0);
		$initialLoad = $request->get('initialLoad',1);
		$limit = 8;
		$offset = $currentPage * $limit;

		$type = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findOneByName(ucwords(str_replace('-',' ',$activeType)));
		$spotTypes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findAll();
		
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($request->get('slug'));
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$daynum = $request->get('daynum');
		if("local-secret" == $request->get('type'))
		{
			$spotDetails = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->getAllInLocalSecretsByType($eguide, $localAuthor, $type, 0, $offset, $limit + 1);
		}
		else
		{
			$spotDetails = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->getAllNotInLocalSecretsByType($eguide, $localAuthor, $type, $offset, $limit + 1);
		}
		
		$egts = array();
		$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $daynum));
		foreach($spotDetails as $iObj)
		{
			if($itinerary)
			{
				$itsd = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->findOneBy(array('itinerary' => $itinerary, 'spotDetail' => $iObj));
				if($itsd)
					$egts[$iObj->getId()] = $itsd;
			}
		}
		
		$hasNext = false;
		if(count($spotDetails) > $limit){
			$hasNext = true;
			$spotDetails = array_chunk($spotDetails, $limit);
			$spotDetails = $spotDetails[0];
		}
		
		$renderData = array(
			'spotDetails' => $spotDetails,
			'eguide' => $eguide, 
			'daynum' => $request->get('daynum'), 
			'egts' => $egts,
			'spotTypes' => $spotTypes,
			'activeType' => $activeType,
			'type' => $request->get('type')
		);
		
		$data['hasNext'] = $hasNext;
		if($initialLoad == 'true'){
			$data['html'] = $this->renderView("BugglMainBundle:LocalAuthor/Spot:spotsList.html.twig", $renderData);
		}
		else{
			$data['html'] = $this->renderView("BugglMainBundle:LocalAuthor/Spot:spotItems.html.twig", $renderData);
		}
		//print_r($data); 
		return new JsonResponse($data,200);
	}

	public function checkSpotAvailabilityAction(Request $request)
	{
		$params = $request->query->all();
		
		if( 0 == strlen($params['lat']) || 0 == strlen($params['lng']) )
			$spots = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findBy( array('address' => $params['address']) );
		else
			$spots = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findBy(array('latitude' => $params['lat'], 'longitude' => $params['lng']));
		
		if(count($spots))
		{
			$response = array(
				'html' => $this->renderView("BugglMainBundle:LocalAuthor/Spot:duplicateSpotList.html.twig", array('spots' => $spots))
				);
			return new JsonResponse($response,200);
		}
		
		$response = array();
		return new JsonResponse($response,200);
	}

	public function getSpotDetailsAction(Request $request)
	{
		$spotId = $request->get('spot_id');
		$spot = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findOneById($spotId);

		$localAuthor = $this->get('security.context')->getToken()->getUser();
		// try and fetch if author has written detail for the spot
		$detail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneBy(array('spot' => $spot, 'localAuthor' => $localAuthor));
		$owned = true;

		if(!$detail)
		{
			$owned = false;
			// fetch one from the database
			$detail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneBy(array('spot' => $spot));
		}
		
		$city = array('id' => $spot->getCity()->getId(), 'name' => $spot->getCity()->getName());
		$country = array('id' => $spot->getCity()->getCountry()->getId(), 'name' => $spot->getCity()->getCountry()->getName());

		$response = array(
			'id' => $spot->getId(),
			'name' => $spot->getName(),
			'address' => $spot->getAddress(),
			'contact_number' => $spot->getContactNumber(),
			'location' => array('city' => $city, 'country' => $country, 'lat' => $spot->getLatitude(), 'lng' => $spot->getLongitude()),
			'hasOwnDetail' => $owned,
			'hasDetail' => ($detail) ? true : false
			);

		if($owned and $detail)
		{
			$spotLikes = $detail->getSpotLikes();
			$sLikes = array();
			foreach($spotLikes as $iObj)
			{
				$sLikes[] = array('id' => $iObj->getId(), 'name' => $iObj->getName());
			}

			$categories = $detail->getSpotCategories();
			$cats = array();
			foreach($categories as $cat)
			{
				$cats[] = array('id' => $cat->getId(), 'name' => $cat->getName());
			}

			$response['detail'] = array(
				'id' => $detail->getId(),
				'author_id' => $detail->getLocalAuthor()->getId(),
				'rating' => $detail->getRating(),
				'title' => $detail->getTitle(),
				'photo' => $detail->getPhoto(),
				'best_thing' => $detail->getBestThing(),
				'description' => $detail->getDescription(),
				// 'category' => array('id' => $detail->getSpotCategory()->getId(), 'name' => $detail->getSpotCategory()->getName()),
				'categories' => $cats,
				'type' => array('id' => $detail->getSpotType()->getId(), 'name' => $detail->getSpotType()->getName()),
				'likes' => $sLikes
				);
		}
		return new JsonResponse($response,200);
	}

	public function uploadPhotoAction(Request $request)
	{
		$photo = $request->files->get('spot-photo');

		// if ( $photo->getClientSize() >= $photo->getMaxFilesize() )
		// {
		// 	$response = array('errorMsg' => 'The file you want to upload exceeds the maximum file upload limit of 10MB!', 'max_upload_size' => $photo->getMaxFilesize() );
		// 	return new JsonResponse($response,200, array("Content-Type" => "text/plain"));
		// }

		$filename = sha1(uniqid(mt_rand(), true)).'.'.$photo->guessExtension();

        // $uploadDir = 'uploads/spot_pics';
        $uploadDir = 'uploads/travel_guide_temp';
        $uploadRootDir = $this->container->get('kernel')->getRootdir().'/../web/'.$uploadDir;
        $photo->move($uploadRootDir, $filename);

        $webPath = 'http://'.$request->getHost().'/'.$uploadDir.'/'.$filename;

        $src = $uploadRootDir . "/" . $filename;
        $size = getimagesize($src);
		$width = $size[0];
		$height = $size[1];

        $response = array('url' => $webPath, 'filename' => $filename, 'width' => $width, 'height' => $height);
        return new JsonResponse($response,200, array("Content-Type" => "text/plain"));
	}

	public function flickrSearchAction(Request $request)
	{
		$f = $this->get('buggl_main.flickr_photo_search');
		$text = $request->get('text');
		$page = $request->get('page', 1);
		$tags = explode(" ", $text);
		$args = array('text' => $text, 'page' => $page, 'per_page' => 6);

		$result = $f->search($args)->getResponse();

		return new JsonResponse($result,200);
	}

	public function addSpotToGuideAction(Request $request)
	{
		$form = $request->query->all();
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('guide_id'));
		$newAdditionCnt = 0;
		foreach($form['spot-details'] as $time => $ids)
		{
			foreach($ids as $id)
			{
				$detail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneById($id);
				if($detail)
				{
					$newAdditionCnt++;
					if(0 < $form['day_num'])
					{
						$this->saveAsLocalSecret( $eguide , $detail );
						$this->saveAsItinerary( $eguide , $detail, $form['day_num'], $time );
					}
					else {
						$this->saveAsLocalSecret( $eguide , $detail );
					}
				}
				
			}
		}
		$this->eguide = $eguide;
		$this->form = $form;
		$this->form['page'] = ((int)$this->form['page'] + $newAdditionCnt) - 1;
		$this->generateRedirectLink();
		$response = array('id' => $eguide->getId(), 'redirectLink' => $this->redirectLink );
		return new JsonResponse($response,200,array("Content-Type" => "text/plain"));
	}

	public function removeSpotAction(Request $request)
	{
		$spotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneById($request->get('id'));
		$type = $request->get('type');
		$guideId = $request->get('guide_id',0);
		$daynum = $request->get('day_num', 0);
		$eguide = ($guideId > 0) ? $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('guide_id')) : null;

		$message = null;
		if(!is_null($eguide))
		{
			if('itinerary' == $type)
			{
				
				$this->removeFromItinerary( $eguide, $spotDetail, $daynum );
				$message = "The spot ".$spotDetail->getSpot()->getName()." has been successfully removed from your Itinerary!";
			}
			else 
			{	
				$this->removeFromLocalSecret( $eguide, $spotDetail );
				$this->removeFromItinerary( $eguide, $spotDetail, $daynum );
				$message = "The spot ".$spotDetail->getSpot()->getName()." has been successfully removed from your local secret and itinerary!";
			}
			
		}

		$response = array('message' => $message);
		return new JsonResponse($response,200);
	}

	private function replaceSpot(Request $request)
	{
		$this->form = $request->query->all();
		
		$currentSpot = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findOneById($this->form['spotId']);
		
		$this->spotType = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findOneById($this->form['spot-secret']);
		$this->country = $this->getDoctrine()->getRepository('BugglMainBundle:Country')->findOneByName($this->form['country']);
		$this->city = $this->getDoctrine()->getRepository('BugglMainBundle:City')->findOneBy(array('country' => $this->country, 'name' => $this->form['city']));
		if(!$this->city)
		{
			$this->city = new City();
			$this->city->setName($this->form['city']);
			$this->city->setCountry($this->country);
			$this->city->setLat($this->form['city-lat']);
			$this->city->setLong($this->form['city-lng']);

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($this->city);
			$em->flush();
		}

		$this->spot = new Spot();

		$this->saveNewSpot();
		
		$this->saveSpotDetails();

		$this->eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($this->form['slug']);
		$egts = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->findBy(array('eGuide' => $this->eguide, 'spot' => $currentSpot));
		$em = $this->getDoctrine()->getEntityManager();
		
		foreach($egts as $obj)
		{
			$obj->setSpot($this->spot);
			$em->persist($obj);
			$em->flush();
		}

		$this->generateRedirectLink();
		// echo 'here';
		// exit;
	}

	private function processSpot(Request $request)
	{
		$this->form = $request->query->all();
		
		// $_spot = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findOneById($this->form['spotId']);
		$this->spot = (0 < $this->form['spotId']) ? $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findOneById($this->form['spotId']) : new Spot();
		$this->spotType = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findOneById($this->form['spot-secret']);
		$this->country = $this->getDoctrine()->getRepository('BugglMainBundle:Country')->findOneByName($this->form['country']);
		$this->city = $this->getDoctrine()->getRepository('BugglMainBundle:City')->findOneBy(array('country' => $this->country, 'name' => $this->form['city']));
		if(!$this->city)
		{
			$this->city = new City();
			$this->city->setName($this->form['city']);
			$this->city->setCountry($this->country);
			$this->city->setLat($this->form['city-lat']);
			$this->city->setLong($this->form['city-lng']);

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($this->city);
			$em->flush();
		}

		// just try to check if there is change in the name and address
		// Note: maybe there is a much better way than this?
		if( 0 < $this->form['spotId']
			&& $this->spot->getName() != $this->form['name']
			&& $this->spot->getAddress() != $this->form['address'] )
		{
			$this->spot = new Spot();
		}

		$this->saveNewSpot();

		// save or update spot details
		$this->saveSpotDetails();
		// crop and save photo if available
		if( strlen($this->form['filename']) > 0 ){
			$this->cropSpotPhoto($request);
			$spotPicsDir = 'uploads/spot_pics';
			$filename = $this->form['filename'];

			// upload image to amazon s3
			// @author NRL <nash.lesigon@goabroad.com>
			$key = $this->get('buggl_main.constants')->get('SPOT_PHOTOS') . $filename;
	        $sourceFile = $this->container->get('kernel')->getRootdir().'/../web/' . $key;
	        $this->get('buggl_aws.wrapper')->upload($sourceFile, $key);

	        // $webPath = 'http://'.$request->getHost().'/uploads/travel_guide_photos/' . $filename;
	        $baseUrl = $this->get('buggl_main.constants')->get('S3_BASE_URL');
	        $webPath = $baseUrl . $key;


			// $webPath = 'http://'.$request->getHost().'/' . $spotPicsDir . '/' . $filename;
			$this->detail->setPhoto($webPath);
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($this->detail);
			$em->flush();
		}

		$guideSlug = isset($this->form['slug']) ? $this->form['slug'] : null;
		if(!is_null($guideSlug)){
			// echo 'has guide';
			$this->eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($this->form['slug']);

			if("itinerary" == $this->form['pagename'])
			{
				$this->saveAsLocalSecret( $this->eguide , $this->detail );
				$this->saveAsItinerary( $this->eguide , $this->detail, $this->form['day_num'], $this->form['time_of_day'] );

			}
			else if("localplaces" == $this->form['pagename'])
			{
				$this->saveAsLocalSecret( $this->eguide , $this->detail );
			}

		}

		$this->generateRedirectLink();

	}

	private function saveAsLocalSecret(\Buggl\MainBundle\Entity\EGuide $eguide, \Buggl\MainBundle\Entity\SpotDetail $detail)
	{
		$obj = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->findOneBy(array('eGuide' => $eguide, 'spotDetail' => $detail));
		if(!$obj)
		{
			$em = $this->getDoctrine()->getEntityManager();
			$lastOrder = $em->getRepository('BugglMainBundle:EGuideToSpotDetail')->getLastOrderByGuide($eguide);
	        $order = ($lastOrder) ? (int)$lastOrder[0]->getOrder() + 1 : 1;

	        $obj = new EGuideToSpotDetail();
	        $obj->setEGuide($eguide);
	        $obj->setSpotDetail($detail);
	        $obj->setOrder($order);
	        $obj->setIsFeatured(0);
	        $dateAdded = new \DateTime(date('Y-m-d H:i:s'));
	        $obj->setDateAdded($dateAdded);

	        $em->persist($obj);
	        $em->flush();
		}
		
	}

	private function removeFromLocalSecret(\Buggl\MainBundle\Entity\EGuide $eguide, \Buggl\MainBundle\Entity\SpotDetail $detail)
	{
		$obj = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->findOneBy(array('eGuide' => $eguide, 'spotDetail' => $detail));
		if($obj)
		{
			$em = $this->getDoctrine()->getEntityManager();
			$em->remove($obj);
			$em->flush();
		}
	}

	private function saveAsItinerary(\Buggl\MainBundle\Entity\EGuide $eguide, \Buggl\MainBundle\Entity\SpotDetail $detail, $day, $time_of_day)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $day));
		if(!$itinerary)
		{
			$itinerary = new Itinerary();
			$itinerary->setEGuide($eguide);
			$itinerary->setDayNum($day);
			
			$em->persist($itinerary);
			$em->flush();
		}

		$itsd = $em->getRepository('BugglMainBundle:ItineraryToSpotDetail')
					->findOneBy( array('itinerary' => $itinerary, 'spotDetail' => $detail, 'periodOfDay' => $time_of_day) );
		if( !$itsd )
		{
			$lastOrder = $em->getRepository('BugglMainBundle:ItineraryToSpotDetail')->getLastOrderByItineraryAndPeriodOfDay($itinerary, $time_of_day);
	        $order = ($lastOrder) ? (int)$lastOrder[0]->getOrder() + 1 : 1;

			$itsd = new ItineraryToSpotDetail();
			$itsd->setItinerary($itinerary);
			$itsd->setSpotDetail($detail);
			$itsd->setPeriodOfDay($time_of_day);
			$itsd->setOrder($order);
			$dateAdded = new \DateTime(date('Y-m-d H:i:s'));
			$itsd->setDateAdded($dateAdded);

			$em->persist($itsd);
			$em->flush();
		}
	}

	private function removeFromItinerary(\Buggl\MainBundle\Entity\EGuide $eguide, \Buggl\MainBundle\Entity\SpotDetail $detail, $day)
	{
		// if no day number is given then just remove every instance of it.
		if( 0 == $day ){
		 	$objs = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->findByGuideAndSpotDetail($eguide,$detail);
		 	$em = $this->getDoctrine()->getEntityManager();
		 	foreach($objs as $obj)
		 	{
				$em->remove($obj);
				$em->flush();
		 	}
		}
		else {
			$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $day));
			if($itinerary)
			{
				$obj = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->findOneBy(array('itinerary' => $itinerary, 'spotDetail' => $detail));
				if($obj)
				{
					$em = $this->getDoctrine()->getEntityManager();
					$em->remove($obj);
					$em->flush();
				}
			}
		}
		
	}

	private function generateRedirectLink(){

		if(isset($this->eguide))
		{
			if("itinerary" == $this->form['pagename'])
			{
				$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $this->eguide, 'day_num' => $this->form['day_num']));
				$dayNum = $itinerary->getDayNum();
				$timeOfDays = array('morning' => 1, 'afternoon' => 2, 'evening' => 3);
				$timeOfDay = 0;
				$timeOfDayCnt = 1;
				$_page = 0;
				$totalPages = array();
				$morningSds = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->getByItinerary($itinerary, $timeOfDays['morning']);
				if(count($morningSds))
				{
					$timeOfDay = 1;
					$_page = $_page + count( array_chunk($morningSds, 2) );
					$timeOfDayCnt = count( array_chunk($morningSds, 2) );
				}

				$afternoonSds = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->getByItinerary($itinerary, $timeOfDays['afternoon']);
				if(count($afternoonSds))
				{
					$timeOfDay = 2;
					$_page = $_page + count( array_chunk($afternoonSds, 2) );
					$timeOfDayCnt = count( array_chunk($afternoonSds, 2) );
				}

				$eveningSds = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->getByItinerary($itinerary, $timeOfDays['evening']);
				if(count($eveningSds))
				{
					$timeOfDay = 3;
					$_page = $_page + count( array_chunk($eveningSds, 2) );
					$timeOfDayCnt = count( array_chunk($eveningSds, 2) );
				}

				$page = $this->form['page'] + $_page;
				$this->redirectLink = $this->generateUrl(
		            'travel_guide_cover_page',
		            array('page_name' => $this->form['pagename'],
		            	'travel_guide_id' => $this->eguide->getId(),
		            	'page' => $page,
		            	'day' => $dayNum,
		            	'time_of_day' => $timeOfDay,
		            	'time_of_day_cnt' => $timeOfDayCnt)
		        );
			}
			else {
				$page = (0 == $this->form['page'] ) ? 1 : $this->form['page'];
				$this->redirectLink = $this->generateUrl(
		            'travel_guide_cover_page',
		            array('page_name' => $this->form['pagename'],
		            	'travel_guide_id' => $this->eguide->getId(),
		            	'page' => $page)
		        );
			}
		}
		else {
			$this->redirectLink = $this->generateUrl('local_author_gallery_spots');
		}

	}

	private function checkItineraryDayCount($eguide, $daynum)
	{
		if($daynum > 0)
			{
				$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $daynum));
				if(!$itinerary)
				{
					// echo 'try and save itinerary';
					$itinerary = new Itinerary();
					$itinerary->setEGuide($eguide);
					$itinerary->setDayNum($daynum);

					$em = $this->getDoctrine()->getEntityManager();
					$em->persist($itinerary);
					$em->flush();
				}
			}
	}

	private function saveNewSpot()
	{
		$this->spot->setName($this->form['name'])
			->setCity($this->city)
			->setAddress($this->form['address'])
			->setLatitude($this->form['latitude'])
			->setLongitude($this->form['longitude'])
			->setContactNumber($this->form['contact_number'])
			->setWebsite($this->form['website']);

		$dateAdded = new \DateTime(date('Y-m-d H:i:s'));
		$this->spot->setDateAdded($dateAdded);

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($this->spot);
		$em->flush();
	}

	private function saveSpotDetails()
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$_detail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneBy(array('id' => $this->form['spotDetailId'], 'localAuthor' => $localAuthor));
		$this->detail = ($_detail) ? $_detail : new SpotDetail();
		
		$this->detail->setSpot($this->spot)
				->setLocalAuthor($localAuthor)
				->setRating($this->form['spot-rating'])
				->setDescription($this->form['spot-description'])
				->setBestThing($this->form['best-thing'])
				->setPhoto($this->form['photo-url'])
				->setSpotCategory(null)
				->setSpotType($this->spotType);

		if(is_null($this->detail->getDateAdded()))
		{
			$dateAdded = new \DateTime(date('Y-m-d H:i:s'));
			$this->detail->setDateAdded($dateAdded);
		}

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($this->detail);
		$em->flush();
		
		$this->saveSpotDetailLikes($this->detail);

		$this->saveSpotDetailToSpotCategory($this->detail);

	}

	private function saveSpotDetailLikes(\Buggl\MainBundle\Entity\SpotDetail $detail = null)
	{
		$spotDetailLikes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetailToSpotLike')->findBy(array('spot_detail' => $detail));
		if(count($spotDetailLikes))
		{
			foreach($spotDetailLikes as $sLike)
			{
				$key = array_search($sLike->getSpotLike()->getId(), $this->form['spot-likes']);
				if($key === false)
				{
					$em = $this->getDoctrine()->getEntityManager();
					$em->remove($sLike);
					$em->flush();
				}
				else {
					unset($this->form['spot-likes'][$key]);
					$this->form['spot-likes'] = array_values($this->form['spot-likes']);
				}
			}
		}

		if(count($this->form['spot-likes']))
		{
			foreach($this->form['spot-likes'] as $likeId)
			{
				$spotLike = $this->getDoctrine()->getRepository('BugglMainBundle:SpotLike')->findOneById($likeId);
				$spotDetailToSpotLike = new SpotDetailToSpotLike();
				$spotDetailToSpotLike->setSpotDetail($detail)
					->setSpotLike($spotLike);

				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($spotDetailToSpotLike);
				$em->flush();
			}
		}
	}

	private function saveSpotDetailToSpotCategory(\Buggl\MainBundle\Entity\SpotDetail $detail = null)
	{
		$spotDetailCategories = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetailToSpotCategory')->findBy(array('spot_detail' => $detail));
		if(count($spotDetailCategories))
		{
			foreach($spotDetailCategories as $sCat)
			{
				$key = array_search($sCat->getSpotCategory()->getId(), $this->form['spot-categories']);
				if($key === false)
				{
					$em = $this->getDoctrine()->getEntityManager();
					$em->remove($sCat);
					$em->flush();
				}
				else {
					unset($this->form['spot-categories'][$key]);
					$this->form['spot-categories'] = array_values($this->form['spot-categories']);
				}
			}
		}

		if(isset($this->form['spot-categories']) && count($this->form['spot-categories']))
		{
			foreach($this->form['spot-categories'] as $catId)
			{
				$spotCategory = $this->getDoctrine()->getRepository('BugglMainBundle:SpotCategory')->findOneById($catId);
				$spotDetailToSpotCategory = new SpotDetailToSpotCategory();
				$spotDetailToSpotCategory->setSpotDetail($detail)
					->setSpotCategory($spotCategory);

				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($spotDetailToSpotCategory);
				$em->flush();
			}
		}
	}

	private function saveEGuideToSpot()
	{
		$eguideToSpot = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->findOneBy(array('eGuide' => $this->eguide, 'spot' => $this->spot));
		if(!$eguideToSpot)
			$eguideToSpot = new EGuideToSpot();


		$timeOfDay = isset($this->form['time_of_day']) ? $this->form['time_of_day'] : 0;

		$dateAdded = new \DateTime(date('Y-m-d H:i:s'));
		$eguideToSpot->setDayNum($this->form['day_num'])
			->setPeriodOfDay($timeOfDay)
			->setEGuide($this->eguide)
			->setSpot($this->spot)
			->setOrder("1")
			// ->setType($type)
			->setIsFeatured(0)
			->setDateAdded($dateAdded);

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($eguideToSpot);
		$em->flush();
	}

	public function saveCustomCategoryAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$newCatName = $request->get('new_category_name');
		if(strlen(trim($newCatName)) > 0)
		{
			$spotCategory = $this->getDoctrine()->getRepository('BugglMainBundle:SpotCategory')->findOneByName($newCatName);
			$spotType = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findOneById($request->get('spot_type_id'));
			if(!$spotCategory)
			{
				$spotCategory = new SpotCategory();
				$dateAdded = new \DateTime(date('Y-m-d H:i:s'));
				$spotCategory->setDateAdded($dateAdded);
				$spotCategory->setSpotType($spotType);
				$spotCategory->setName($newCatName);
				$spotCategory->setStatus(0);
				$spotCategory->setLocalAuthor($localAuthor);

				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($spotCategory);
				$em->flush();
			}

			$response = array('id' => $spotCategory->getId(), 'name' => $spotCategory->getName());
			return new JsonResponse($response,200);
		}

		$response = array('error' => "No category name passed!");
		return new JsonResponse($response,200);
	}

	public function saveCustomSpotLikeAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$newSpotLikeName = $request->get('new_spot_like');
		if(strlen(trim($newSpotLikeName)) > 0)
		{
			$spotLike = $this->getDoctrine()->getRepository('BugglMainBundle:SpotLike')->findOneByName($newSpotLikeName);
			$spotType = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findOneById($request->get('spot_type_id'));

			if(!$spotLike)
			{
				$spotLike = new SpotLike();
				$dateAdded = new \DateTime(date('Y-m-d H:i:s'));
				$spotLike->setDateAdded($dateAdded);
				$spotLike->setSpotType($spotType);
				$spotLike->setName($newSpotLikeName);
				$spotLike->setStatus(0);
				$spotLike->setLocalAuthor($localAuthor);

				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($spotLike);
				$em->flush();
			}

			$response = array('id' => $spotLike->getId(), 'name' => $spotLike->getName());
			return new JsonResponse($response,200);
		}

		$response = array('error' => "No category name passed!");
		return new JsonResponse($response,200);
	}

	/**
	 * fetch all of the given spot's description
	 */ 
	public function fetchSpotDetailsAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$spotID = $request->get('id');
		$spot = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findOneById($spotID);
		if($spot)
		{
			// fetch all available spot details
			$details = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findBy(array('localAuthor' => $localAuthor, 'spot' => $spot));
			$data['html'] = $this->renderView("BugglMainBundle:LocalAuthor/Spot:spotDescriptionList.html.twig", array('details' => $details));
			return new JsonResponse($data,200);
		}

		return new JsonResponse(array('error' => "Could not find spot with id '$spotID'"), 200);
	}

	public function viewSpotDetailsAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$spot = $this->getDoctrine()->getRepository('BugglMainBundle:Spot')->findOneById($request->get('id'));
		if(!$spot)
			throw $this->createNotFoundException('The product does not exist');

		
		$spotDetails = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findBy(
			array('spot' => $spot, 'localAuthor' => $localAuthor), 
			array('dateAdded' => 'DESC')
			);

		return $this->render('BugglMainBundle:LocalAuthor\Spot:descriptions.html.twig', 
			array('spot' => $spot, 'spotDetails' => $spotDetails));
	}

	public function cropPhotoAction(Request $request)
	{
		$params = $request->request->all();
		
		$this->cropSpotPhoto($request);

		$description = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneById( $request->get('desc_id') );
		
		$filename = $request->get('filename');
		$spotPicsDir = 'uploads/spot_pics';
		$webPath = 'http://'.$request->getHost().'/' . $spotPicsDir . '/' . $filename;
		$description->setPhoto($webPath);
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($description);
		$em->flush();

		return new JsonResponse(array('img_src' => $webPath), 200, array("Content-Type" => "text/plain"));
	}

	public function updateSpotDescriptionAction(Request $request)
	{
		$desc_id = $request->get('desc_id');
		$content = $request->get('description');
		$description = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneById( $request->get('desc_id') );
		if($description){
			$description->setDescription($content);

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($description);
			$em->flush();

			return new JsonResponse(array('error' => 0), 200);
		}

		return new JsonResponse(array('error' => 1), 200);
	}

	/**
	*
	* Private method in cropping photo
	*
	**/
	private function cropSpotPhoto(Request $request)
	{
		$filename = $request->get('filename');

		$width = 515;
		$height = 770;
		$tempDir = $this->container->get('kernel')->getRootdir(). '/../web/uploads/travel_guide_temp';
		$src = $tempDir . '/' . $filename;

		$spotPicsDir = $this->get('buggl_main.constants')->get('SPOT_PHOTOS');
		$targetDir = $this->container->get('kernel')->getRootdir().'/../web/' . $spotPicsDir;

		$target = $targetDir . $filename;
		$options = array(
			'start_x'	=> $request->get('start-x'),
			'start_y'	=> $request->get('start-y'),
			'target_x'	=> $request->get('target-x'),
			'target_y'	=> $request->get('target-y'),
			'width'		=> 515,
			'height'	=> 335,
			'src'		=> $src,
			'target'	=> $target,
			'dir'		=> $targetDir
			);

		$this->get('buggl_main.photo_cropper')->crop($options);
	}

}