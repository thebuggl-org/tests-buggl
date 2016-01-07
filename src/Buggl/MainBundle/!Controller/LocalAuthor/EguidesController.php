<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Buggl\MainBundle\Event\EguideEvent;
use Buggl\MainBundle\Event\Mail\RepublishEvent;

use Buggl\MainBundle\Entity\EGuide;
use Buggl\MainBundle\Entity\Country;
use Buggl\MainBundle\Entity\Category;
use Buggl\MainBundle\Entity\City;
use Buggl\MainBundle\Entity\GoodFor;
use Buggl\MainBundle\Entity\EGuideContent;
use Buggl\MainBundle\Entity\EGuideLocation;
use Buggl\MainBundle\Entity\EGuideToCategory;
use Buggl\MainBundle\Entity\EGuideToCity;
use Buggl\MainBundle\Entity\EguideToGoodFor;
use Buggl\MainBundle\Entity\Itinerary;
use Buggl\MainBundle\Entity\EGuidePhoto;
use Buggl\MainBundle\Entity\BeforeYouGo;
use Buggl\MainBundle\Entity\EGuideRequest;
use Buggl\MainBundle\Entity\PaypalPurchaseInfo;


use Buggl\MainBundle\Entity\EGuidePriceChangelog;

// use Doctrine\ORM\Query\ResultSetMapping;
// use Doctrine\ORM\Query\ResultSetMappingBuilder;
// NASH NOTE: transfer default CRUD travel guide controller
// 			to separate bundle
// ADVISE TO SELF: REFACTOR
class EGuidesController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
	private $steps = array('step-1' => 'prepareStepOne', 'step-2' => 'prepareStepTwo');

	public function indexAction(Request $request)
	{
		$activeTab = $request->get('status');
		//echo $activeTab;
		$status = $this->get('buggl_main.constants')->get($activeTab);
		$limit = 3;
		$page = $request->get('page', 1);
		$localAuthor = $this->get('security.context')->getToken()->getUser();

		if ($status == $this->get('buggl_main.constants')->get('featured')) {
			$status = $this->get('buggl_main.constants')->get('published');
		}

		$counts = array(
			'published' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('published')),
			// 'featured' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countFeaturedInProfile($localAuthor),
		    'unpublished' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('unpublished')),
			'archived' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('archived')),
			'draft' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('draft')),
			'denied' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('denied'))
		);
		$newEGuideRequestCount = $this->getDoctrine()
			                     ->getEntityManager()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countRequestByStatus($localAuthor,array('0'=>'0'));
		if($activeTab == "featured") {
			$paginator = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findFeaturedInProfile($localAuthor);
		}/*elseif($activeTab == "unpublished")
		{
			$paginator = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findByLocalAuthorPaginatorun($localAuthor, $status, $limit, $page);
		}*/
		 else {
			$paginator = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findByLocalAuthorPaginator($localAuthor, $status, $limit, $page);
		}

		$softPageLimit = 8;
		$hardPageLimit = 12;

		$data = array(
			"activeTab" => $activeTab,
			'paginator' => $paginator,
			'counts' => $counts,
			'itemLimit' => $limit,
			'currentPage' => $page,
			'newRequestCount'=>$newEGuideRequestCount,
			'softPageLimit' => $softPageLimit,
			'hardPageLimit' => $hardPageLimit,
		);
		
		return $this->render('BugglMainBundle:LocalAuthor\Eguides:eguides.html.twig', $data);
	}

	public function addTravelGuideAction(Request $request){

		$request_id=$request->query->get('request'); 
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$userId = $localAuthor->getId();
		$user=0;
		//$messagerequest=0;
		//echo $userId; die;
		$em = $this->getDoctrine()->getEntityManager();
		if(empty($request_id)){
			$messagerequest=0; 
        }
		//$messagerequest='';
    	
		$newEGuideRequestCount = $this->getDoctrine()
			                     ->getEntityManager()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countRequestByStatus($localAuthor,array('0'=>'0'));
		if(!empty($request_id))
		{	
			$messagerequest = $em->getRepository('BugglMainBundle:EGuideRequest')->findOneById($request_id);
			if(empty($messagerequest))
			{
			$this->get('session')->setFlash('error','Cannot open page!');
			return new RedirectResponse($this->generateUrl('local_author_dashboard'));
			}
		    
		    $localuser=$messagerequest->getlocalAuthor();
		    $user=$messagerequest->getUser();
		    $user=$user->getId();
		    $price=$messagerequest->getPrice();
		   

		    if(!empty($localuser))
		   {
		    $localuserid=$localuser->getId();
	   
		     if($localuserid !=$userId)
		     {
			 $this->get('session')->setFlash('error','Cannot open page!');
			 return new RedirectResponse($this->generateUrl('local_author_dashboard'));
		     }
		     if($messagerequest->getStatus()!=0)
		     {
		     $this->get('session')->setFlash('success','This request has been proceeds');	
		     return new RedirectResponse($this->generateUrl('local_author_eguides', array('status'=>'unpublished')));
		     }
		     /*if($messagerequest->getStatus()==1)
		     {
		     	
		     }*/
		   }
		 else
		 {
		 	$this->get('session')->setFlash('error','Cannot open page!');
			return new RedirectResponse($this->generateUrl('local_author_dashboard'));
		 }
	    }
		//print_r($id1); echo $id1;  die;
		$this->eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('travel_guide_id'));
		$this->eguide = (!$this->eguide) ? new EGuide() : $this->eguide;

		if(!is_null($this->eguide->getLocalAuthor()) && $localAuthor->getId() !== $this->eguide->getLocalAuthor()->getId())
		{
			$this->get('session')->setFlash('error','Cannot open page!');
			return new RedirectResponse($this->generateUrl('local_author_dashboard'));
		}
		
		$location = $localAuthor->getLocation();
		$durations = $this->getDoctrine()->getRepository('BugglMainBundle:Duration')->findAll();
		$tripThemes = $this->getDoctrine()->getRepository('BugglMainBundle:TripTheme')->findBy(array('status' => 1));
		$interests = $this->getDoctrine()->getRepository('BugglMainBundle:Category')->findAll();
		$goodFor = $this->getDoctrine()->getRepository('BugglMainBundle:GoodFor')->findAll();

		$egInterests = ($request->get('travel_guide_id') > 0) ? $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToCategory')->findBy(array('e_guide' => $this->eguide)) : array();
		$bestTimes = (is_null($this->eguide->getBestTimeToGo())) ? NULL : explode(",", $this->eguide->getBestTimeToGo());

		if ($request->getMethod() == 'POST') {
			$infoForm = $request->request->all();
			
			$result = $this->processStepOneForm_v2($request);

			if($result){

				// exit;
				// $this->newProcessStepOneForm($request);

				// $this->processStepOneForm($request);
				$slug = $this->eguide->getSlug();
				return new RedirectResponse($this->generateUrl('travel_guide_cover_page', array('travel_guide_id' => $this->eguide->getId())));
			}
		}


		return $this->render('BugglMainBundle:LocalAuthor/Eguides/Themes/Default:guideInfo.html.twig',
			array('eguide' => $this->eguide,
				'travel_guide_id' => $this->eguide->getId(),
				'location' => $location,
				'egInterests' => $egInterests,
				'interests' => $interests,
				'request_guide_id'=>$request_id,
				'request_user'=>$user,
			    'durations' => $durations,
			    'newRequestCount'=>$newEGuideRequestCount,
				'tripThemes' => $tripThemes,
				'messagerequest' => $messagerequest,
				'goodFor' => $goodFor,
				'bestTimes' => $bestTimes) );
	}

	public function updateGuidePagesAction(Request $request)
	{
		$this->eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('travel_guide_id'));

		$pageName = $request->get('page_name');

		$pageName = lcfirst(str_replace(" ", "",ucwords(str_replace("-", " ", $request->get('page_name'))))).'Page';
		$localAuthor = $this->get('security.context')->getToken()->getUser();

		if($localAuthor->getId() !== $this->eguide->getLocalAuthor()->getId())
		{
			$this->get('session')->setFlash('error','Cannot open page!');
			return new RedirectResponse($this->generateUrl('local_author_dashboard'));

		}
		$newEGuideRequestCount = $this->getDoctrine()
			                     ->getEntityManager()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countRequestByStatus($localAuthor,array('0'=>'0'));
		$contents = null;
		$eguidePhoto = null;
		$location = null;
		$eguideToSpot = null;
		$noOfDays = 0;
		$dailyPaginator = array();


		$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findBy(array('e_guide' => $this->eguide));
		$hasItinerary = (count($itinerary) == 0) ? 0 : 1;


		if('cover' == $request->get('page_name'))
		{
			$eguidePhoto = $this->getDoctrine()->getRepository('BugglMainBundle:EGuidePhoto')->findOneBy(array('e_guide' => $this->eguide, 'type' => EGuidePhoto::COVER_PHOTO, 'status' => 1));
		}
		else if('overview' == $request->get('page_name'))
		{
			$contents = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideContent')->findBy(array('e_guide' => $this->eguide, 'type' => EGuideContent::OVERVIEW_TYPE));
			$eguidePhoto = $this->getDoctrine()->getRepository('BugglMainBundle:EGuidePhoto')->findOneBy(array('e_guide' => $this->eguide, 'type' => EGuidePhoto::OVERVIEW_PHOTO, 'status' => 1));
		}
		else if('before-you-go' == $request->get('page_name'))
		{
			$contents = $this->getDoctrine()->getRepository('BugglMainBundle:BeforeYouGo')->findOneBy(array('e_guide' => $this->eguide));

		}
		else if('author' == $request->get('page_name'))
		{
			$location = $this->getDoctrine()->getRepository('BugglMainBundle:Location')->getByLocalAuthor($localAuthor);
		}
		else if('itinerary' == $request->get('page_name'))
		{
			// $itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findBy(array('e_guide' => $this->eguide));
			$noOfDays = (count($itinerary) == 0) ? 1 : count($itinerary);

			$dailyPaginator = $this->getItineraryPages();
			$page = $request->get('page');
			if(1 < $page AND 0 == count($dailyPaginator))
			{
				$p = intval($page) - 1;
				return new RedirectResponse($this->generateUrl('travel_guide_cover_page', array('travel_guide_id' => $request->get('travel_guide_id'), 'page_name' => 'itinerary', 'page' => $p , 'day' => $request->get('day'))));
			}

		}
		else if('localplaces' == $request->get('page_name'))
		{
			$dailyPaginator = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->findBy(array('eGuide' => $this->eguide));
		}


		$streetCredit = $this->getDoctrine()->getRepository('BugglMainBundle:StreetCredit')->findOneBy(array('localAuthor' => $localAuthor));
		
		$template = "BugglMainBundle:LocalAuthor/Eguides/Themes/Default:$pageName.html.twig";
		$this->params = array('template' => $template,
						'eguide' => $this->eguide,
						'hasItinerary' => $hasItinerary,
						'travel_guide_id' => $this->eguide->getId(),
						'contents' => $contents,
						'day' => $request->get('day'),
						'page' => $request->get('page'),
						'time_of_day' => $request->get('time_of_day'),
						'time_of_day_cnt' => $request->get('time_of_day_cnt'),
						'noOfDays' => $noOfDays,
						'eguidePhoto' => $eguidePhoto,
						'localAuthor' => $localAuthor,
						'location' => $location,
						'newRequestCount'=>$newEGuideRequestCount,
						'dailyPaginator' => $dailyPaginator,
						'pageName' => $pageName,
						'completeProfile' => (is_null($streetCredit)) ? false : $streetCredit->getProfileStatus());


		// var_dump(array_keys($this->params)); exit;
		return $this->render('BugglMainBundle:LocalAuthor/Eguides/Themes/Default:guidePages.html.twig', $this->params);
	}

	public function itineraryAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('travel_guide_id'));

		$page = $request->get('page');
		$day = $request->get('day');

		$screen = 1;
		$offset = 0;


		$screen = ceil($page/2);

		$limit = ($screen == 1) ? 1 : 2;
		$dailyIntro = null;
		if(2 == $screen)
			$offset = 1;
		else if(2 < $screen)
			$offset = (($screen - 1) * $limit) - 1;

		if(1 == $screen)
			$dailyIntro = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $day));

		$paginator = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getByGuide($eguide, $offset, $limit, $day);
		$spotDetails = array();
		foreach($paginator as $obj)
		{
			$spotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneBy(array('spot' => $obj->getSpot(), 'localAuthor' => $localAuthor));
			$spotDetails[] = array('detail' => $spotDetail, 'period_of_day' => $obj->getTime());
		}

		return $this->render('BugglMainBundle:LocalAuthor/Eguides/Themes/Default:itinerary.html.twig',
				array('page' => $page, 'paginator' => $paginator, 'day' => $day, 'spotDetails' => $spotDetails, 'dailyIntro' => $dailyIntro, 'eguide' => $eguide));
	}

	private function getPlainTitle($title)
	{
		$title = str_replace('&nbsp;'," ",$title);
		$title = str_replace('&rsquo;',"",$title);
		$plainTitle = trim(html_entity_decode(strip_tags($title), ENT_QUOTES, 'UTF-8'));

		return $plainTitle;
	}

	private function getTotalPages()
	{
		$eguideToSpot = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getEGuideItinerarySpots($this->eguide);
		$timeOfDays = array('morning' => 1, 'afternoon' => 2, 'evening' => 3);
		$dailyPaginator = array();
		foreach($eguideToSpot as $obj)
		{
			$time_of_day = $obj->getPeriodOfDay();
			$dailyPaginator[$obj->getDayNum()][$time_of_day][] = array('id' => $obj->getId(), 'time_of_day' => strtolower($obj->getTime()));
		}

		$totalPages = array();
		if(count($dailyPaginator))
		{

			foreach($dailyPaginator as $dayNum => $day)
			{
				$totalPages[$dayNum]['morning'] = 0;
				$totalPages[$dayNum]['afternoon'] = 0;
				$totalPages[$dayNum]['evening'] = 0;
				foreach($timeOfDays as $strTime => $time)
				{
					if(isset($day[$time]))
					{
						$page = ceil(count($day[$time]) / 2);
						$totalPages[$dayNum][$strTime] = $page;
					}
				}

			}
		}

		return $totalPages;

	}

	public function newItineraryV1Action(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('travel_guide_id'));

		$day = $request->get('day');
		$page = $request->get('page');
		$time = $request->get('time_of_day');
		$time_cnt = $request->get('time_of_day_cnt');
		$limit = 2;
		$baseOffset = 0;

		$offset = ($time_cnt <= 1) ? 0 : ($time_cnt - 1) * $limit;
		$baseOffset = ($time_cnt <= 1) ? 0 : ($time_cnt - 1) * $limit;
		$oddOffset = 0;
		$evenOffset = 0;

		$dailyIntro = null;
		
		$oddPageObjects = array();
		$evenPageObjects = array();


		if($page <= 2)
		{
			$dailyIntro = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $day));
			$earliestEGTS = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getEarliestTimeOfDay($eguide, $day);
			if($earliestEGTS)
			{
				$time = $earliestEGTS->getPeriodOfDay();
				$evenPageObjects = $this->prepareEGuideToSpots($eguide, $offset, $limit, $day, $time);
			}
		}
		else {
			$recordCount = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getTotalRecordPerTime($eguide, $day, $time);
			// echo "<br/>count : $recordCount";

			$timePageCnt = ceil($recordCount/$limit);

			$odd = ($page % 2);

			if($odd)
			{
				$oddPageObjects = $this->prepareEGuideToSpots($eguide, $offset, $limit, $day, $time);
				// exit;
				if($time_cnt < $timePageCnt)
				{
					$evenOffset = $offset + $limit;
					$evenPageObjects = $this->prepareEGuideToSpots($eguide, $evenOffset, $limit, $day, $time);
				}
				else
				{
					$nextEGTS = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getNextTimeOfDay($eguide, $day, $time);
					if($nextEGTS)
					{
						$evenTime = $nextEGTS->getPeriodOfDay();
						$evenPageObjects = $this->prepareEGuideToSpots($eguide, $evenOffset, $limit, $day, $evenTime);
					}
				}
			}
			else
			{
				$evenPageObjects = $this->prepareEGuideToSpots($eguide, $offset, $limit, $day, $time);
				if(1 == $time_cnt)
				{
					$prevEGTS = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getPreviousTimeOfDay($eguide, $day, $time);

					if($prevEGTS)
					{
						$oddTime = $prevEGTS->getPeriodOfDay();
						$prevRecordCount = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getTotalRecordPerTime($eguide, $day, $oddTime);
						$prevTimePageCnt = ceil($prevRecordCount/$limit);
						$prevOffset = ($prevTimePageCnt - 1) * $limit;

						$oddPageObjects = $this->prepareEGuideToSpots($eguide, $prevOffset, $limit, $day, $oddTime);
					}
				}
				else
				{
					$oddOffset = $offset - $limit;
					// echo "<br/>oddOffset : $oddOffset";
					$oddPageObjects = $this->prepareEGuideToSpots($eguide, $oddOffset, $limit, $day, $time);

				}
			}
		}

		return $this->render('BugglMainBundle:LocalAuthor/Eguides/Themes/Default:newItinerary.html.twig',
			array('page' => $page,
				'day' => $day,
				'eguide' => $eguide,
				'dailyIntro' => $dailyIntro,
				'oddPageObjects' => $oddPageObjects,
				'evenPageObjects' => $evenPageObjects));

	}

	public function newItineraryAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('travel_guide_id'));

		$day = $request->get('day');
		$page = $request->get('page');
		$time = $request->get('time_of_day');
		$time_cnt = $request->get('time_of_day_cnt');
		// $page = $_page - 1;
		$limit = 2;

		$offset = ($time_cnt <= 1) ? 0 : ($time_cnt - 1) * $limit;
		$baseOffset = ($time_cnt <= 1) ? 0 : ($time_cnt - 1) * $limit;
		$oddOffset = 0;
		$evenOffset = 0;

		$itinerarySpotDetails = array();
		$dailyIntro = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $day));
		if(1 < $page)
			$itinerarySpotDetails = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->getByItinerary($dailyIntro, $time, $limit, $offset);

		$timeOfDay = array(1 => 'Morning', 2 => 'Afternoon', 3 => 'Evening');
        $periodOfDay = isset($timeOfDay[$time]) ? strtolower($timeOfDay[$time]) : null;

    
		return $this->render('BugglMainBundle:LocalAuthor/Eguides/Themes/Default:newItineraryV2.html.twig',
			array('page' => $page,
				'day' => $day,
				'eguide' => $eguide,
				'periodOfDay' => $periodOfDay,
				'dailyIntro' => $dailyIntro,
				'itinerarySpotDetails' => $itinerarySpotDetails));

	}

	private function getItineraryPages()
	{
		$itineraries = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findBy(array('e_guide' => $this->eguide));
		$timeOfDays = array('morning' => 1, 'afternoon' => 2, 'evening' => 3);
		$totalPages = array();
		if(count($itineraries))
		{

			foreach($itineraries as $obj)
			{
				$dayNum = $obj->getDayNum();

				$morningSds = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->getByItinerary($obj, $timeOfDays['morning']);
				if(count($morningSds))
				{
					$totalPages[$dayNum]['morning'] = array_chunk($morningSds, 2);
				}

				$afternoonSds = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->getByItinerary($obj, $timeOfDays['afternoon']);
				if(count($afternoonSds))
				{
					$totalPages[$dayNum]['afternoon'] = array_chunk($afternoonSds, 2);
				}

				$eveningSds = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->getByItinerary($obj, $timeOfDays['evening']);
				if(count($eveningSds))
				{
					$totalPages[$dayNum]['evening'] = array_chunk($eveningSds, 2);
				}

			}
		}


		return $totalPages;
	}

	private function prepareEGuideToSpots($eguide, $offset, $limit, $day, $time)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$eguideToSpots = array();
		$EGTSObjects = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getByGuideDailySpots($eguide, (int)$offset, $limit, $day, $time);
		if($EGTSObjects)
		{
			foreach($EGTSObjects as $iObj)
			{
				$eguideToSpots['period_of_day'] = strtolower($iObj->getTime());
				$spotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneBy(array('spot' => $iObj->getSpot(), 'localAuthor' => $localAuthor));
				$eguideToSpots['details'][] = $spotDetail;
			}
		}
		return $eguideToSpots;
	}

	public function localplacesAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('travel_guide_id'));
		$page = $request->get('page');
		$day = 0;

		$screen = ceil($page/2);
		$offset = 0;
		$limit = ($screen == 1) ? 1 : 2;
		if(2 == $screen)
			$offset = 1;
		else if(2 < $screen)
			$offset = (($screen - 1) * $limit) - 1;


		$paginator = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getByGuide($eguide, $offset, $limit, 0);
		$spotDetails = array();
		foreach($paginator as $obj)
		{
			$spotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneBy(array('spot' => $obj->getSpot(), 'localAuthor' => $localAuthor));

			$spotDetails[] = array('detail' => $spotDetail, 'period_of_day' => $obj->getTime());
		}

		$eguidePhoto = $this->getDoctrine()->getRepository('BugglMainBundle:EGuidePhoto')->findOneBy(array('e_guide' => $eguide, 'type' => EGuidePhoto::LOCAL_SECRET_PHOTO, 'status' => 1));

		// var_dump(count($localSecrets)); exit;
		return $this->render('BugglMainBundle:LocalAuthor/Eguides/Themes/Default:localplaces.html.twig',
			array('page' => $page, 'paginator' => $paginator, 'day' => $day, 'spotDetails' => $spotDetails, 'eguide' => $eguide, 'eguidePhoto' => $eguidePhoto));
	}

	public function localSecretAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('travel_guide_id'));
		$page = $request->get('page');
		$day = 0;

		$eguidePhoto = null;
		$localSecret = null;
		if(1 == $page)
			$eguidePhoto = $this->getDoctrine()->getRepository('BugglMainBundle:EGuidePhoto')->findOneBy(array('e_guide' => $eguide, 'type' => EGuidePhoto::LOCAL_SECRET_PHOTO, 'status' => 1));
		else {
			$limit = 1;
			$offset = $page - 2;
			$localSecret = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->getByEGuide($eguide, $limit, $offset);
		}


		return $this->render('BugglMainBundle:LocalAuthor/Eguides/Themes/Default:localSecret.html.twig',
			array('page' => $page, 'localSecret' => (!is_null($localSecret)) ? $localSecret[0] : null, 'day' => $day, 'eguide' => $eguide, 'eguidePhoto' => $eguidePhoto));
	}

	public function addNewDayAction(Request $request)
	{
		$slug = $request->get('guide_slug');
		$day_num = $request->get('day_num');
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($slug);

		$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $day_num));
		if(!$itinerary)
		{
			$itinerary = new Itinerary();
			$itinerary->setEGuide($eguide);
			$itinerary->setDayNum($day_num);

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($itinerary);
			$em->flush();

			// update eguide real duration
			$currentDuration = $eguide->getRealDuration();
			$realDuration = $currentDuration + 1;
			$eguide->setRealDuration($realDuration);
			$em->persist($eguide);
			$em->flush();
		}

		$this->get('session')->setFlash('success','You have successfully added a new day to your travel guide itinerary!');
		return new RedirectResponse($this->generateUrl('travel_guide_cover_page', array('travel_guide_id' => $eguide->getId(),'page_name' => 'itinerary','page' => 1, 'day' => $day_num)));
	}

	public function removeDayAction(Request $request)
	{
		$slug = $request->get('guide_slug');
		$day_num = $request->get('day_num');
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($slug);

		$em = $this->getDoctrine()->getEntityManager();

		// find and remove itinerary
		$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $day_num));
		if($itinerary)
		{
			$itsd = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->findBy(array('itinerary' => $itinerary));
			foreach($itsd as $iObj)
			{
				$em->remove($iObj);
				$em->flush();
			}

			$em->remove($itinerary);
			$em->flush();

			// update eguide real duration
			$currentDuration = $eguide->getRealDuration();
			$realDuration = (0 == $currentDuration - 1) ? 0 : $currentDuration - 1;
			$eguide->setRealDuration($realDuration);
			$em->persist($eguide);
			$em->flush();
		}

		// update preceding itinerary days
		$nextSchedules = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->getNextSchedule($eguide, $day_num);
		foreach($nextSchedules as $nextObj)
		{
			$nextDayNum = $nextObj->getDayNum();
			$newDayNum = $nextDayNum - 1;
			$nextEgts = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->findBy(array('eGuide' => $eguide, 'dayNum' => $nextDayNum));
			foreach($nextEgts as $iObj)
			{
				$iObj->setDayNum($newDayNum);
				$em->persist($iObj);
				$em->flush();

			}

			$nextObj->setDayNum($newDayNum);
			$em->persist($nextObj);
			$em->flush();
		}



		$this->get('session')->setFlash('success','You have successfully removed a day from your itinerary!');

		$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findBy(array('e_guide' => $eguide));

		$redirectLink = (count($itinerary) == 0) ? 
								$this->generateUrl('travel_guide_cover_page', array('travel_guide_id' => $eguide->getId(),'page_name' => 'overview')) :
								$this->generateUrl('travel_guide_cover_page', array('travel_guide_id' => $eguide->getId(),'page_name' => 'itinerary'));

		return new RedirectResponse($redirectLink);

	}

	/**
	 * START: step 3
	 */
	public function finishTravelGuideAction(Request $request)
	{ //die('1223');

	$localAuthor = $this->get('security.context')->getToken()->getUser();
	$newEGuideRequestCount = $this->getDoctrine()
			                     ->getEntityManager()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countRequestByStatus($localAuthor,array('0'=>'0'));

	$guide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('travel_guide_id'));
	$this->eguide = (!$guide) ? new EGuide() : $guide;
			
		if($localAuthor->getId() !== $guide->getLocalAuthor()->getId())
		{
			$this->get('session')->setFlash('error','Cannot open page!');
			return new RedirectResponse($this->generateUrl('local_author_dashboard'));

		}
		$request_id=$guide->getisRequestId();
		if($request_id != 0 && ($this->eguide->getStatus() !=10 ||$this->eguide->getStatus()!=2))
		{ $this->get('session')->setFlash('success','Your Eguide is completed you can now published!');
			$buyer = $this->getDoctrine()->getRepository('BugglMainBundle:LocalAuthor')->findOneById($request_id);
			$purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($buyer,$guide);
			if(empty($purchaseInfo)){
			$paymentsService = $this->get('buggl_main.buggl_payment_service');
			$paymentsService->saveFreePurchase($guide,$buyer);
			$this->eguide->setStatus(10);
			$em = $this->getDoctrine()->getEntityManager();

		$em->persist($this->eguide);
		$em->flush();
		}
		
		
	}
		
		
		if($request->getMethod() == 'POST')
		{

			$eguide = $this->finishTravelGuide($request);
		
		
		
			//$this->get('session')->setFlash('success','Congratulations! You have completed creating your guide!');

			/* START: generate pdf should be done here */
			$result = $this->get('buggl_main.pdf_creator')->generate($eguide);
			
			// set eguide pdf and html filenames
			$eguide->setPdfFilename($result['pdf']);
			$eguide->setHtmlFilename($result['html']);
			$eguide->setPdfPageCount($result['pageCount']);

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($eguide);
			$em->flush();
			/* end: generate pdf should be done here */
			
			/**
			 * updates free search page
			 *
			 * @author Farly Taboada <farly.taboada@goabroad.com>
			 */
			$event = new EguideEvent($eguide,0);
    		$this->get('event_dispatcher')->dispatch('buggl.update_free_search',$event);


			if($eguide->getStatus() == $this->get('buggl_main.constants')->get('published')){
				return new RedirectResponse($this->generateUrl('e_guide_share', array('slug'=>$eguide->getSlug())));
			}

			return new RedirectResponse($this->generateUrl('local_author_eguides', array('status'=>'unpublished')));

		}
		$this->prepareFinishingPage($request);
		if(!count($this->egtsd))
		{
			$this->get('session')->setFlash('error','It seems that you have not completed your guide yet. Please complete your guide before going to step 3.');
			return new RedirectResponse($this->generateUrl('travel_guide_cover_page', array('travel_guide_id' => $this->eguide->getId())));
		}


		return $this->render('BugglMainBundle:LocalAuthor/Eguides/Themes/Default:finishPage.html.twig', array('eguide' => $this->eguide, 'page' => $request->get('page', 1), 'egtsd' => $this->egtsd,'newRequestCount'=>$newEGuideRequestCount, 'moreCount' => $this->moreCount));
	}

	public function finishTravelGuideSpotListAction(Request $request)
	{
		$this->prepareFinishingPage($request);
		$data = array(
			'moreCount' => $this->moreCount,
			'html' => $this->renderView('BugglMainBundle:LocalAuthor/Eguides/Themes/Default:finishPageSpotList.html.twig', array('eguide' => $this->eguide, 'egtsd' => $this->egtsd))
			);
		return new JsonResponse($data,200);
	}

	private function prepareFinishingPage(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$page = $request->get('page', 1);

		$limit = 8;
		$offset = (1 == $page) ? 0 : ($page - 1) * $limit ;

		$this->eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('travel_guide_id'));
		// $paginator = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getByGuide($this->eguide, $offset, $limit);
		$this->egtsd = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->getByEGuide($this->eguide, $limit, $offset, true);
		// echo count($this->egtsd);
		// exit;
		$totalRecord = count($this->egtsd);

		$this->moreCount = 0;
		if(($limit * $page) > $totalRecord)
			$this->moreCount = 0;
		else if($totalRecord - ($limit * $page) > $limit)
			$this->moreCount = $limit;
		else
			$this->moreCount = $totalRecord - ($limit * $page);

		// $this->spotDetails = array();
		// foreach($paginator as $obj)
		// {
		// 	$spotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneBy(array('spot' => $obj->getSpot(), 'localAuthor' => $localAuthor));
		// 	$this->spotDetails[] = $spotDetail;
		// }
	}

	private function finishTravelGuide(Request $request)
	{
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('travel_guide_id'));
		$constants = $this->get('buggl_main.constants');
		$form = $request->request->all();

		$em = $this->getDoctrine()->getEntityManager();

		// ================= PRICE CHANGE LOG ==================== 
		$priceChangeLog = null;
		if( !is_null($eguide->getPrice()) AND $form['price'] != $eguide->getPrice() AND $eguide->getStatus() == $constants->get('published') ){
			$priceChangeLog = $this->getDoctrine()->getRepository('BugglMainBundle:EGuidePriceChangelog')->findOneBy( array( 'guide' => $eguide, 'status' => 1 ) );
			if( $priceChangeLog )
			{
				$priceChangeLog->setStatus( 0 );
				
				$em->persist($priceChangeLog);
				$em->flush();
			}
			$priceChangeLog = new EGuidePriceChangelog();
			$priceChangeLog->setPriceFrom( $eguide->getPrice() );
			$priceChangeLog->setPriceTo( $form['price'] );
			$priceChangeLog->setGuide( $eguide );
			$priceChangeLog->setStatus( 1 );
			$priceChangeLog->setDateAdded( new \DateTime(date('Y-m-d H:i:s')) );

			$em->persist($priceChangeLog);
			$em->flush();
		}
		// ================= PRICE CHANGE LOG ==================== 
		
		if(isset($form['is_free']) && $form['is_free'] == 'on'){
			$eguide->setPrice('0.00');
		}
		else{
			$eguide->setPrice($form['price']);
		}

		$isUpdate = !is_null($eguide->getStatus()) && $eguide->getStatus() != $constants->get('draft');
		
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$status = $constants->get('published');
		if($eguide->getStatus() == $constants->get('draft') || $eguide->getStatus() == $constants->get('archived')){
			$eguide->setStatus($status);
		}

		if($this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countFeaturedInProfile($localAuthor) < $constants->get('FEATURED_IN_PROFILE_LIMIT')){
			$eguide->setIsFeaturedInProfile(1);
		}
		
		$eguide->setDateUpdated(new \DateTime(date('Y-m-d H:i:s')));

		// $em = $this->getDoctrine()->getEntityManager();
		$em->persist($eguide);
		$em->flush();

		$featured = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->findBy(array('eGuide' => $eguide, 'isFeatured' => 1));

		foreach($featured as $iObj)
		{
			$sdID = $iObj->getSpotDetail()->getId();
			$key = array_search($sdID, $form['feature-eguide-spot']);
			if($key)
			{
				unset($form['feature-eguide-spot'][$key]);
				$form['feature-eguide-spot'] = array_values($form['feature-eguide-spot']);
			}
			else {
				$iObj->setIsFeatured(0);
				$em->persist($iObj);
				$em->flush();

			}
		}

		if(count($form['feature-eguide-spot']))
		{
			foreach($form['feature-eguide-spot'] as $spotDetailId)
			{	
				$sd = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneById($spotDetailId);
				$egtsd = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->findOneBy(array('eGuide' => $eguide, 'spotDetail' => $sd));
				$egtsd->setIsFeatured(1);

				$em->persist($egtsd);
				$em->flush();
			}
		}
		
		
		// notify event dispatcher;
		if($isUpdate){
			$activityType = $constants->get('ACTIVITY_UPDATE_GUIDE');
		}
		else{
			$activityType = $constants->get('ACTIVITY_CREATE_GUIDE');
		}
		
		$reciever = null;
		$event = new \Buggl\MainBundle\Event\ActivityEvent($eguide,$localAuthor,$reciever,$activityType);
		$this->notifyActivityEventDispatcher($event);

		// ================= PRICE CHANGE LOG ==================== 
		if( !is_null($priceChangeLog) ){
			$activityType = $constants->get('ACTIVITY_UPDATE_GUIDE_PRICE');
			$event = new \Buggl\MainBundle\Event\ActivityEvent($priceChangeLog,$localAuthor,$reciever,$activityType);
			$this->notifyActivityEventDispatcher($event);
		}
		// ================= PRICE CHANGE LOG ==================== 

		$streetCreditService = $this->get('buggl_main.street_credit');
		$streetCreditService->updateGuideStatus($localAuthor);
		$streetCreditService->updateInviteAuthorStatus($localAuthor);

		return $eguide;
		// create pdf
		// $this->createEGuidePDF($request);
	}

	private function notifyActivityEventDispatcher(\Buggl\MainBundle\Event\ActivityEvent $event)
	{
		$this->get('event_dispatcher')->dispatch('buggl.activity',$event);
	}

	/**
	 * END: step 3
	 */

	/**
	 * START: Eguide Preview
	 */
	public function eguidePreviewAction(Request $request)
	{
		// $localAuthor = $this->get('security.context')->getToken()->getUser();
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($request->get('slug'));
		$localAuthor = $eguide->getLocalAuthor();
		$eguidePhotos = $this->getDoctrine()->getRepository('BugglMainBundle:EGuidePhoto')->findBy(array('e_guide' => $eguide, 'status' => 1));
		$photos = array();
		foreach($eguidePhotos as $ePhoto)
		{
			$photos[$ePhoto->getType()] = $ePhoto;
		}

		$eguideContents = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideContent')->findBy(array('e_guide' => $eguide));
		$contents = array();
		foreach($eguideContents as $eContent)
		{
			$contents[$eContent->getType()] = $eContent;
		}

		$params = array(
			'localAuthor' => $localAuthor,
			'eguide' => $eguide,
			'photos' => $photos,
			'contents' => $contents
			);
		return $this->render('BugglMainBundle:LocalAuthor\Eguides:eguidePreview.html.twig', $params);
	}
	
	public function pdfPreviewAction(Request $request)
	{
		$isAdmin = $this->getRequest()->getSession()->has('has_admin_access');
		$user = $this->get('security.context')->getToken()->getUser();
		$filename = $request->get('filename');
		if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') && !$isAdmin)
        {
            $response = new Response(); 
            $response->setStatusCode(404); 
            return $response; 
        }

        if(!$isAdmin)
        {
        	$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBy(array("pdfFilename" => $filename));
            $purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($user,$eguide);
            $purchased = !is_null($purchaseInfo) || ($user != 'anon.' && $eguide->getLocalAuthor()->getId() == $user->getId());

            if(!$purchased){
                $response = new Response(); 
                $response->setStatusCode(404); 
                return $response; 
            }
        }

		
		// echo $filename; exit;
		$request = $this->get('request');
	    $path = $this->get('kernel')->getRootDir(). "/../web/uploads/eguide_pdf/";
	    $content = file_get_contents($path.$filename);

	    $response = new Response();

	    //set headers
	    $response->headers->set('Content-Type', 'application/pdf');
	    // $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);
		
	    $response->setContent($content);
	    return $response;
	}
	/**
	 * END: Eguide Preview
	 */

	public function updateItineraryIntroAction(Request $request)
	{
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($request->get('guide_slug'));
		$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $request->get('day_num')));
		if(!$itinerary)
		{
			$itinerary = new Itinerary();
		}

		$itinerary->setEGuide($eguide);
		$itinerary->setDayNum($request->get('day_num'));
		$itinerary->setTitle($request->get('title'));
		$itinerary->setDescription($request->get('description'));

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($itinerary);
		$em->flush();

		$response = array("title" => $itinerary->getTitle(),"content" => $itinerary->getDescription());
		return new JsonResponse($response,200);
	}

	public function updateGuideFieldAction(Request $request)
	{
		
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($request->get('guide_slug'));
		$field = $request->get('field_name');
		$value = stripslashes($request->get('field_value'));
		$method = 'set'.ucfirst($field);
		$eguide->{$method}($value);
		if($field == "title")
		{
			$plainTitle = $this->getPlainTitle($value);
			$eguide->setPlainTitle($plainTitle);
			if( $eguide->getStatus() == $this->get('buggl_main.constants')->get('draft_guide') )
			{
				$slug = $this->get('buggl_main.slugifier')->format( $plainTitle )->getSlug()."-".$eguide->getId();
				$eguide->setSlug($slug);
			}
		}

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($eguide);
		$em->flush();

		/**
		 * updates free search field
		 *
		 * @author Farly Taboada <farly.taboada@goabroad.com>
		 */
		$event = new EguideEvent($eguide,0);
    	$this->get('event_dispatcher')->dispatch('buggl.update_free_search',$event);

		$response = array("content" => $value, "field" => $field);
		return new JsonResponse($response,200);
	}

	public function updateGuideContentAction(Request $request)
	{
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($request->get('guide_slug'));
		$contentId = $request->get('content_id',0);
		$type = $request->get('content_type',0);

		// $overviewId = $request->get('overview_id',0);
		$order = 1;
		if($contentId){
			$content = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideContent')->findOneById($contentId);
			$order = $content->getOrder();
			// $overview = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideOverview')->findOneById($overviewId);
			// $order = $overview->getOrder();
		}
		else {
			$content = new EGuideContent();
			// $overview = new EGuideOverview();

		}

		$value = $request->get('field_value');
		$dateAdded = new \DateTime(date('Y-m-d H:i:s'));
		$content->setEGuide($eguide);
		$content->setContent($value);
		$content->setDateAdded($dateAdded);
		$content->setType($type);
		$content->setOrder($order);

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($content);
		$em->flush();

		$response = array("content" => $value, "id" => $content->getId());
		return new JsonResponse($response,200);
	}

	public function updateBeforeYouGoPageAction(Request $request)
	{
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($request->get('guide_slug'));
		$beforeYouGo = $this->getDoctrine()->getRepository('BugglMainBundle:BeforeYouGo')->findOneBy(array('e_guide' => $eguide));
		if(!$beforeYouGo)
		{
			$beforeYouGo = new BeforeYouGo();
			$beforeYouGo->setEGuide($eguide);
		}

		$field = $request->get('field_name');
		$value = $request->get('field_value');
		$method = 'set'.ucfirst($field);
		$beforeYouGo->{$method}($value);

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($beforeYouGo);
		$em->flush();

		$response = array("content" => $value);
		return new JsonResponse($response,200);
	}

	/**
	 * START: version 2 process for the guide info form
	 */
	private function processStepOneForm_v2(Request $request)
	{
		$infoForm 	 = $request->request->all();
		//print_r($infoForm); die;
		if(!$this->validateStepOne($infoForm)){
			return false;
		}

		$localAuthor = $this->get('security.context')->getToken()->getUser();
		 //$duration 	 = $this->getDoctrine()->getRepository('BugglMainBundle:Duration')->findOneById($infoForm['duration']);
		$tripTheme 	 = $this->getDoctrine()->getRepository('BugglMainBundle:TripTheme')->findOneById($infoForm['trip-theme']);
		$dateAdded 	 = new \DateTime(date('Y-m-d H:i:s'));
		$status 	 = $this->get('buggl_main.constants')->get('draft_guide');

		$country 	 = $this->verifyCountry($infoForm['country']);
		//echo $infoForm['requestUser']; die;
		$this->eguide->setLocalAuthor($localAuthor);
		$this->eguide->setCountry($country);
		$this->eguide->setTitle($infoForm['title']);
		$this->eguide->setExcerpts($infoForm['eguide-teaser']);
		$this->eguide->setBudget($infoForm['budget']);
		$this->eguide->setisRequestId($infoForm['requestUser']);
		if(!empty($infoForm['price'])){
		$this->eguide->setPrice($infoForm['price']);}

		if(is_null($this->eguide->getDateCreated())){
			$this->eguide->setDateCreated($dateAdded);
		}
		
		$this->eguide->setDateUpdated($dateAdded);

		if(is_null($this->eguide->getId()))
			$this->eguide->setStatus($status);

		// $this->eguide->setDuration(0);
		$this->eguide->setRealDuration(0); // no itinerary upon creation so real duration is 0
		$this->eguide->setTripTheme($tripTheme);
		$this->eguide->setCategory(null);
		// $this->eguide->setGoodFor(null);
		$this->eguide->setBestTimeToGo(implode(',', $infoForm['best-time-to-go']));
		$plainTitle = $this->getPlainTitle($infoForm['title']);
		$this->eguide->setPlainTitle( $plainTitle );


		// save guide
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($this->eguide);
		 if($infoForm['requestUser'] !=0)
			 	{  //echo $infoForm['requestGuide']; die;
			 
			 		$messagerequest = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuideRequest')->findOneById($infoForm['requestGuide']);
			 		$messagerequest->setStatus(1);
			 		
			 		$em->persist($messagerequest);
			 	}	
			 	
		$em->flush();

		// save/update slug if no slug yet or if guide is still a draft
		if( is_null($this->eguide->getSlug()) || 0 == strlen( trim($this->eguide->getSlug()) ) || $this->eguide->getStatus() == $this->get('buggl_main.constants')->get('draft_guide') )
		{
			$slug = $this->get('buggl_main.slugifier')->format( $plainTitle )->getSlug()."-".$this->eguide->getId();
			$this->eguide->setSlug($slug);
			$em->persist($this->eguide);
			$em->flush();
		}

		$this->saveLocations($infoForm['locations']);
		$interests = (isset($infoForm['interests'])) ? $infoForm['interests'] : array();
		$this->saveInterests($interests);
		$this->saveGoodFor($infoForm['good_for']);

		/**
		 * updates free search field
		 *
		 * @author Farly Taboada <farly.taboada@goabroad.com>
		 */
		$event = new EguideEvent($this->eguide,0);
    	$this->get('event_dispatcher')->dispatch('buggl.update_free_search',$event);

		// echo 'done'; exit;
		return true;
	}

	private function validateStepOne($formData)
	{
		$isValid = true;
		// removed field
		// if(!isset($formData['duration']) || empty($formData['duration'])) {
		// 	$isValid = false;
		// }
		
		if(!isset($formData['trip-theme']) || empty($formData['trip-theme'])){
			$isValid = false;
		}
		else if(!isset($formData['title']) || empty($formData['title'])){
			$isValid = false;
		}
		else if(!isset($formData['eguide-teaser']) || empty($formData['eguide-teaser'])){
			$isValid = false;
		}
		else if(!isset($formData['best-time-to-go']) || empty($formData['best-time-to-go'])){
			$isValid = false;
		}
		else if(!isset($formData['locations']) || empty($formData['locations'])){
			$isValid = false;
		}
		else if(!isset($formData['interests']) || empty($formData['interests'])){
			$isValid = false;
		}
		else if(!isset($formData['good_for']) || empty($formData['good_for'])){
			$isValid = false;
		}

		if(!$isValid){
			$this->get('session')->getFlashBag()->add('error', "There are some errors on the form. Please complete the required fields.");
		}

		return $isValid;
	}

	private function verifyCountry($name)
	{
		$country = $this->getDoctrine()->getRepository('BugglMainBundle:Country')->findOneByName($name);
		if(!$country)
		{
			$country = new Country();
			$country->setName($name);
			$country->setStatus(1);
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($country);
			$em->flush();
		}

		return $country;
	}

	private function saveLocations($locations)
	{
		//
		$egLocations = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideLocation')->findBy(array('e_guide' => $this->eguide));

		$em = $this->getDoctrine()->getEntityManager();
		if($egLocations){
			foreach($egLocations as $location)
			{
				$em->remove($location);
				$em->flush();
			}
		}

		foreach($locations as $newLocation)
		{
			$obj = new EGuideLocation();
			$obj->setEGuide($this->eguide);
			$obj->setAddress($newLocation);

			$em->persist($obj);
			$em->flush();
		}

	}

	private function saveInterests($interests)
	{
		$egInterests = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToCategory')->findBy(array('e_guide' => $this->eguide));
		$em = $this->getDoctrine()->getEntityManager();
		$categories = array();
		if($egInterests){
			foreach($egInterests as $interest)
			{
				$em->remove($interest);
				$em->flush();
			}
		}

		foreach($interests as $newInterest)
		{
			$category = $this->getDoctrine()->getRepository('BugglMainBundle:Category')->findOneByName($newInterest);
			if(!$category)
			{
				$category = new Category();
				$category->setName($newInterest);
				$category->setIsPublished(1);
				$category->setIsDefault(1);

				$em->persist($category);
				$em->flush();
			}

			$obj = new EGuideToCategory();
			$obj->setEGuide($this->eguide);
			$obj->setCategory($category);

			$em->persist($obj);
			$em->flush();

			/**
			 * @author vfgtaboada <farly.taboada@goabroad.com>
			 */

			$categories[] = $obj->getCategory()->getName();
		}

		/**
		 * @author vfgtaboada <farly.taboada@goabroad.com>
		 */
		$categories = implode(', ', $categories);
		$this->eguide->setCategoryNames($categories);
		$em->persist($this->eguide);
		$em->flush();
		/********************/
	}

	private function saveGoodFor($goodFor)
	{
		$egGoodFor = $this->getDoctrine()->getRepository('BugglMainBundle:EguideToGoodFor')->findBy(array('e_guide' => $this->eguide));
		$em = $this->getDoctrine()->getEntityManager();
		if($egGoodFor){
			foreach($egGoodFor as $iObj)
			{
				$em->remove($iObj);
				$em->flush();
			}
		}

		foreach($goodFor as $newGoodFor)
		{
			$goodForObj = $this->getDoctrine()->getRepository('BugglMainBundle:GoodFor')->findOneById($newGoodFor);
			if($goodForObj)
			{
				$obj = new EGuideToGoodFor();
				$obj->setEGuide($this->eguide);
				$obj->setGoodFor($goodForObj);

				$em->persist($obj);
				$em->flush();
			}

		}
	}

	/**
	 * END: version 2 process for the guide info form
	 */

	/**
	 * DEPRECATED PROCESS
	 */
	private function newProcessStepOneForm(Request $request)
	{
		$infoForm = $request->request->all();
		// echo "<pre>";
		// var_dump($infoForm);
		// exit;

		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$duration 	 = $this->getDoctrine()->getRepository('BugglMainBundle:Duration')->findOneById($infoForm['duration']);
		$country 	 = $this->getDoctrine()->getRepository('BugglMainBundle:Country')->findOneById($infoForm['country']);
		$tripTheme 	 = $this->getDoctrine()->getRepository('BugglMainBundle:TripTheme')->findOneById($infoForm['trip-theme']);
		$dateAdded 	 = new \DateTime(date('Y-m-d H:i:s'));
		$status 	 = $this->get('buggl_main.constants')->get('draft_guide');


		$this->eguide->setLocalAuthor($localAuthor);
		$this->eguide->setCountry($country);
		$this->eguide->setTitle($infoForm['title']);
		$this->eguide->setExcerpts($infoForm['eguide-teaser']);
		$this->eguide->setDateCreated($dateAdded);
		$this->eguide->setBudget($infoForm['budget']);
		$this->eguide->setStatus($status);
		$this->eguide->setDuration($duration);
		$this->eguide->setTripTheme($tripTheme);
		$this->eguide->setCategory(null);
		// $this->eguide->setGoodFor(null);
		$this->eguide->setBestTimeToGo(implode(',', $infoForm['best-time-to-go']));
		$plainTitle = $this->getPlainTitle($infoForm['title']);
		$this->eguide->setPlainTitle($plainTitle);

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($this->eguide);
		$em->flush();

		// update slug
		$slug = $this->get('buggl_main.slugifier')->format($plainTitle)->getSlug()."-".$this->eguide->getId();
		$this->eguide->setSlug($slug);
		$em->persist($this->eguide);
		$em->flush();

		// save categories
		$this->saveEGuideCategories($infoForm['categories']);

		// save good for
		$this->saveEGuideGoodFor($infoForm['good_for']);

		// save cities
		$this->saveEGuideCities($infoForm['cities'], $infoForm['coords']);
	}

	private function saveEGuideCategories($categories = array())
	{
		// var_dump($categories);
		$_categories = $categories;
		$guideToCats = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToCategory')->findBy(array('e_guide' => $this->eguide));
		// var_dump(count($guideToCats));
		if(count($guideToCats))
		{
			foreach($guideToCats as $iObj)
			{
				$key = array_search($iObj->getCategory()->getName(), $categories);
				// var_dump($key);
				// echo $iObj->getCategory()->getName();
				if(!$key)
				{
					$em = $this->getDoctrine()->getEntityManager();

					$em->remove($iObj);
					$em->flush();
				}
				else {
					unset($categories[$key]);
					$categories = array_values($categories);
				}
			}
		}

		// var_dump($categories);
		// exit;

		// save categories
		$categoryObjects = array();
		if(count($categories))
		{
			foreach($categories as $cat)
			{
				$category = $this->getDoctrine()->getRepository('BugglMainBundle:Category')->findOneByName($cat);
				if($category)
				{
					$egtc = new EGuideToCategory();
					$egtc->setEGuide($this->eguide);
					$egtc->setCategory($category);

					$em = $this->getDoctrine()->getEntityManager();
					$em->persist($egtc);
					$em->flush();
				}
			}
		}


		// update eguide category names
		$this->eguide->setCategoryNames(implode(",", $_categories));
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($this->eguide);
		$em->flush();
	}

	private function saveEGuideGoodFor($gfs = array())
	{
		// var_dump($gfs);
		// exit;
		$guideToGoodFor = $this->getDoctrine()->getRepository('BugglMainBundle:EguideToGoodFor')->findBy(array('e_guide' => $this->eguide));
		if(count($guideToGoodFor))
		{
			foreach($guideToGoodFor as $iObj)
			{
				$key = array_search($iObj->getGoodFor()->getId(), $gfs);
				if(!$key)
				{
					$em = $this->getDoctrine()->getEntityManager();
					$em->remove($iObj);
					$em->flush();
				}
				else {
					unset($gfs[$key]);
					$gfs = array_values($gfs);
				}
			}
		}

		if(count($gfs))
		{
			foreach($gfs as $gf)
			{
				$goodFor = $this->getDoctrine()->getRepository('BugglMainBundle:GoodFor')->findOneById($gf);
				if($goodFor)
				{
					$egtgf = new EGuideToGoodFor();
					$egtgf->setEGuide($this->eguide);
					$egtgf->setGoodFor($goodFor);

					$em = $this->getDoctrine()->getEntityManager();
					$em->persist($egtgf);
					$em->flush();
				}
			}
		}

	}

	private function saveEGuideCities($cities = array(), $coords = array())
	{
		// var_dump($cities);
		$guideToCities = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToCity')->findBy(array('e_guide' => $this->eguide));
		if(count($guideToCities))
		{
			foreach($guideToCities as $guideToCity)
			{
				$key = array_search($guideToCity->getCity()->getName(), $cities);
				if(!$key)
				{
					$em = $this->getDoctrine()->getEntityManager();
					$em->remove($guideToCity);
					$em->flush();
				}
				else {
					unset($cities[$key]);
					$cities = array_values($cities);
				}
			}
		}
		// exit;
		$country = $this->eguide->getCountry();
		if(count($cities))
		{
			foreach($cities as $cName)
			{
				$em = $this->getDoctrine()->getEntityManager();
				$city = $this->getDoctrine()->getRepository('BugglMainBundle:City')->findOneBy(array('country' => $country, 'name' => $cName));
				if(!$city)
				{
					$city = new City();
					$city->setName($cName);
					$city->setCountry($country);
					$city->setLat($coords[$cName]['lat']);
					$city->setLong($coords[$cName]['lng']);
					$em->persist($city);
					$em->flush();

				}

				$egtc = new EGuideToCity();
				$egtc->setEGuide($this->eguide);
				$egtc->setCity($city);


				$em->persist($egtc);
				$em->flush();
			}
		}

	}

	/**
	 * END: new process for the guide info form
	 */

	private function processStepOneForm(Request $request)
	{
		$infoForm = $request->request->all();
		// echo "<pre>";
		// var_dump($infoForm);
		// exit;

		$duration = $this->getDoctrine()->getRepository('BugglMainBundle:Duration')->findOneById($infoForm['duration']);
		$country = $this->getDoctrine()->getRepository('BugglMainBundle:Country')->findOneById($infoForm['country']);
		$tripTheme = $this->getDoctrine()->getRepository('BugglMainBundle:TripTheme')->findOneById($infoForm['trip-theme']);
		$category = $this->getDoctrine()->getRepository('BugglMainBundle:Category')->findOneById($infoForm['category']);
		$goodFor = $this->getDoctrine()->getRepository('BugglMainBundle:GoodFor')->findOneById($infoForm['good_for']);
		$status = $this->get('buggl_main.constants')->get('draft_guide');
		// var_dump($slug);
		$dateAdded = new \DateTime(date('Y-m-d H:i:s'));
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		// $this->eguide = new EGuide();
		$this->eguide->setLocalAuthor($localAuthor);
		$this->eguide->setCountry($country);
		$this->eguide->setTitle($infoForm['title']);
		$this->eguide->setExcerpts($infoForm['eguide-teaser']);
		$this->eguide->setDateCreated($dateAdded);
		$this->eguide->setBudget($infoForm['budget']);
		$this->eguide->setStatus($status);
		$this->eguide->setDuration($duration);
		$this->eguide->setTripTheme($tripTheme);
		$this->eguide->setCategory($category);
		$this->eguide->setGoodFor($goodFor);

		$this->eguide->setBestTimeToGo(implode(',', $infoForm['best-time-to-go']));
		//added by vft.. default values
		$plainTitle = $this->getPlainTitle($infoForm['title']);
		$this->eguide->setPlainTitle($plainTitle);
		$this->eguide->setCategoryNames($category->getName());

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($this->eguide);
		$em->flush();

		// update slug
		$slug = $this->get('buggl_main.slugifier')->format($plainTitle)->getSlug()."-".$this->eguide->getId();
		$this->eguide->setSlug($slug);
		$em->persist($this->eguide);
		$em->flush();

		$guideToCities = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToCity')->findBy(array('e_guide' =>$this->eguide));
		if(count($guideToCities))
		{
			foreach($guideToCities as $guideToCity)
			{
				$key = array_search($guideToCity->getCity()->getId(), $infoForm['cities']);
				if(!$key)
				{
					$em->remove($guideToCity);
					$em->flush();
				}
				else {
					unset($infoForm['cities'][$key]);
					$infoForm['cities'] = array_values($infoForm['cities']);
				}
			}
		}

		// add cities
		if(count($infoForm['cities']))
		{
			foreach($infoForm['cities'] as $cityID)
			{
				if($cityID)
				{
					$city = $this->getDoctrine()->getRepository('BugglMainBundle:City')->findOneById($cityID);

					$eguideToCity = new EGuideToCity();
					$eguideToCity->setEGuide($this->eguide);
					$eguideToCity->setCity($city);

					$em->persist($eguideToCity);
					$em->flush();
				}
			}
		}
	}

	/**
	 * END: DEPRECATED PROCESS
	 */


	private function prepareItineraryData(Request $request)
	{
		$page = $request->get('page', 1);
		$day = $request->get('day', 1);

		$noOfDays = $this->eguide->getDuration()->getNoOfDays();
		// $paginator = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getByEguide($this->eguide, $offset, $limit);
		$eguideToSpot = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->findBy(array('eGuide' => $this->eguide, 'type' => 1), array('dayNum' => 'ASC', 'periodOfDay' => 'ASC'));
		$dailyPaginator = array();
		foreach($eguideToSpot as $obj)
		{
			$dailyPaginator[$obj->getDayNum()][] = $obj->getId();
		}

		// var_dump($dailyPaginator);
		// exit;

		$params = array('noOfDays' => $noOfDays, 'day' => $day, 'page' => $page, 'dailyPaginator' => $dailyPaginator);

		$this->params = array_merge($this->params, $params);
	}

	private function prepareLocalplacesData(Request $request)
	{

	}

	public function uploadPhotoAction(Request $request)
	{
		$photo = $request->files->get('travel-guide-photo');
		
		if(is_null($photo))
		{
			$response = array('errorMsg' => 'Could not continue upload. Something went wrong!');
			return new JsonResponse($response,200, array("Content-Type" => "text/plain"));
		}
		else if ( $photo->getClientSize() >= $photo->getMaxFilesize() )
		{
			$response = array('errorMsg' => 'The file you want to upload exceeds the maximum file upload limit of 10MB!');
			return new JsonResponse($response,200, array("Content-Type" => "text/plain"));
		}

		
		$filename = sha1(uniqid(mt_rand(), true)).'.'.$photo->guessExtension();
		
    	$uploadDir = 'uploads/travel_guide_temp';
        $uploadRootDir = $this->container->get('kernel')->getRootdir().'/../web/'.$uploadDir;
        if(!is_writable($uploadRootDir)){
            mkdir("$uploadRootDir",0755);
        }
        $photo->move($uploadRootDir, $filename);

        $webPath = 'http://'.$request->getHost().'/'.$uploadDir.'/'.$filename;

        $src = $uploadRootDir . "/" . $filename;
        $size = getimagesize($src);
		$width = $size[0];
		$height = $size[1];

        $response = array('url' => $webPath, 'filename' => $filename, 'width' => $width, 'height' => $height);
        return new JsonResponse($response,200, array("Content-Type" => "text/plain"));
	}

	public function getPhotoFromWebAction(Request $request)
	{
		$image_url = $request->get('image_url');
		// $url  = 'http://www.example.com/a-large-file.zip';
	    // $path = '/path/to/a-large-file.zip';
	 	$uploadDir = 'uploads/travel_guide_temp';
        $uploadRootDir = $this->container->get('kernel')->getRootdir().'/../web/'.$uploadDir;
        if(!is_writable($uploadRootDir)){
            mkdir("$uploadRootDir",0755);
        }
        
        // remove excess parameters at it is causing an error
        $image_url = strtok($image_url, "?");
        $pathInfo = pathinfo($image_url);
        
        $filename = sha1(uniqid(mt_rand(), true)).'.'.$pathInfo['extension'];

	    $ch = curl_init($image_url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	    $data = curl_exec($ch);

	    curl_close($ch);

	    file_put_contents($uploadRootDir . '/' . $filename, $data);

	    $webPath = 'http://'.$request->getHost().'/'.$uploadDir.'/'.$filename;

        $src = $uploadRootDir . "/" . $filename;
        $size = getimagesize($src);
		$width = $size[0];
		$height = $size[1];

        $response = array('url' => $webPath, 'filename' => $filename, 'width' => $width, 'height' => $height);

		return new JsonResponse($response,200, array("Content-Type" => "text/plain"));
	}

	public function cropPhotoAction(Request $request)
	{
		$jpeg_quality = 90;
		$filename = $request->get('filename');
		$day_num = $request->get('day_num');
		$x = $request->get('x-coord');
		$y = $request->get('y-coord');
		$targ_w = $request->get('width');
		$targ_h = $request->get('height');

		$width = 515;
		$height = 770;
		$uploadRootDir = $this->container->get('kernel')->getRootdir(). '/../web/uploads/travel_guide_temp';
		$src = $uploadRootDir . '/' . $filename;

		$pathInfo = pathinfo($src);
		if($pathInfo['extension'] == 'png'){
			$img_r = imagecreatefrompng($src);
		}
		else {
			$img_r = imagecreatefromjpeg($src);
		}

		$dst_r = ImageCreateTrueColor( $width, $height );

		// crop
		imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$width,$height,$targ_w,$targ_h);

		$targetDir = $this->container->get('kernel')->getRootdir(). '/../web/uploads/travel_guide_photos';
		if(!is_writable($targetDir)){
            mkdir("$targetDir",0755);
        }
		$target = $targetDir . '/' . $filename;
		imagejpeg($dst_r, $target, $jpeg_quality);

		// upload image to amazon s3
		// @author NRL <nash.lesigon@goabroad.com>
		$sourceFile = $target;
        $key = $this->get('buggl_main.constants')->get('EGUIDE_PHOTOS') . $filename;
        $this->get('buggl_aws.wrapper')->upload($sourceFile, $key);

        // $webPath = 'http://'.$request->getHost().'/uploads/travel_guide_photos/' . $filename;
        $baseUrl = $this->get('buggl_main.constants')->get('S3_BASE_URL');
        $webPath = $baseUrl . $key;

		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('eguide_id'));

		$eguidePhotos = $this->getDoctrine()->getRepository('BugglMainBundle:EGuidePhoto')->findBy(array('e_guide' => $eguide, 'type' => $request->get('photo-type'), 'status' => 1));
		foreach($eguidePhotos as $iObj)
		{
			$iObj->setStatus(2);

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($iObj);
			$em->flush();
		}

		$eguidePhoto = new EGuidePhoto();
		$eguidePhoto->setEGuide($eguide);
		$eguidePhoto->setPhoto($webPath);
		$eguidePhoto->setType($request->get('photo-type'));
		$eguidePhoto->setStatus(1);

		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($eguidePhoto);
		$em->flush();

		$response = array('url' => $webPath, 'filename' => $filename);
		if($day_num > 0)
		{
			$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findOneBy(array('e_guide' => $eguide, 'day_num' => $day_num));
			if(!$itinerary)
			{
				$itinerary = new Itinerary();
				$itinerary->setEGuide($eguide);
				$itinerary->setDayNum($day_num);

			}
			$itinerary->setEGuidePhoto($eguidePhoto);
			$em->persist($itinerary);
			$em->flush();

			$response = array_merge($response, array('itinerary_id' => $itinerary->getId()));

		}

		return new JsonResponse($response,200, array("Content-Type" => "text/plain"));
	}

	public function featureInProfileAction(Request $request)
	{
		$id = $request->get('id',0);
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($id);

		$data['status'] = 'ERROR';
		$data['message'] = 'This guide does not exist anymore.';

		if(!is_null($eguide)){
			$localAuthor = $this->get('security.context')->getToken()->getUser();
			$status = $this->get('buggl_main.constants')->get('featured_in_profile');
			$count = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countFeaturedInProfile($localAuthor,$status);
			if($count< $constants->get('FEATURED_IN_PROFILE_LIMIT') ){
				$eguide->setIsFeaturedInProfile($status);
				$this->getDoctrine()->getEntityManager()->persist($eguide);
				$this->getDoctrine()->getEntityManager()->flush();
				$data['status'] = 'SUCCESS';
				$data['message'] = 'You have featured "'.$eguide->getPlainTitle().'" in your profile.';
			}
			else{
				$data['status'] = 'ERROR';
				$data['message'] = 'You can only feature '.$constants->get('FEATURED_IN_PROFILE_LIMIT').' guides in your profile.';
			}
		}

		return new JsonResponse($data,200);
	}

	public function unFeatureInProfileAction(Request $request)
	{
		$id = $request->get('id',0);
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($id);

		$data['status'] = 'ERROR';
		$data['message'] = 'This guide does not exist anymore.';

		if(!is_null($eguide)){
			$eguide->setIsFeaturedInProfile($this->get('buggl_main.constants')->get('unfeatured_in_profile'));
			$this->getDoctrine()->getEntityManager()->persist($eguide);
			$this->getDoctrine()->getEntityManager()->flush();
			$data['status'] = 'SUCCESS';
			$data['message'] = 'You have unfeatured "'.$eguide->getPlainTitle().'" in your profile.';
		}

		return new JsonResponse($data,200);
	}

	private function scalePhoto($source, $filename, $prefix = "scaled_")
	{
		$source =  $this->container->get('kernel')->getRootdir(). '/../web/uploads/travel_guide_photos/'.$filename;
		$size = getimagesize($source);

		$orig_width = $size[0];
		$orig_height = $size[1];

		$width = 515;
		$height = 770;

		$new_image = imagecreatetruecolor($width, $height);
		imagecopyresampled($new_image, $source, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

		$target = $this->container->get('kernel')->getRootdir(). '/../web/uploads/travel_guide_photos/' . $prefix . $filename;
		imagejpeg($new_image, $target, 100);
		// return 'http://'.$request->getHost().'/uploads/travel_guide_photos/'. $prefix . $filename;
	}

	/*
		TEMP METHODS FOR TESTING EVENTS
	*/
	public function nashphotoAction(Request $request)
	{
		// 0e4ed3afc5a263eacc76c8990ac76c57b9834928.jpeg
		$filename = '0e4ed3afc5a263eacc76c8990ac76c57b9834928.jpeg';

		$src = $this->container->get('kernel')->getRootdir(). '/../web/uploads/travel_guide_temp/'.$filename;
		$size = getimagesize($src);
		$width = $size[0];
		$height = $size[1];
		echo "width: $width";
		echo "<br/>height: $height";


		$x = 363.905325443787;
		// $x2 = $request->get('x2-coord');
		$y = 275.55555555555554;
		// $y2 = $request->get('y2-coord');
		$targ_w = 2440.828402366864;
		$targ_h = 3671.439538066153;


		$img_r = imagecreatefromjpeg($src);
		$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

		// crop
		imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$width,$height,$targ_w,$targ_h);

		$target = $this->container->get('kernel')->getRootdir(). '/../web/uploads/travel_guide_photos/'.$filename;
		imagejpeg($dst_r, $target, 100);

		echo "<br/>scale";
		$source = $dst_r; // fopen($this->container->get('kernel')->getRootdir(). '/../web/uploads/travel_guide_photos/'.$filename, 'r');
		// $source = $target;
		$size = getimagesize($target);

		$orig_width = $size[0];
		$orig_height = $size[1];
		echo "<br/>width: $orig_width";
		echo "<br/>height: $orig_height";

		$nwidth = 515;
		$nheight = 770;

		$new_image = imagecreatetruecolor($nwidth, $nheight);
		imagecopyresampled($new_image, $source, 0, 0, 0, 0, $nwidth, $nheight, $orig_width, $orig_height);

		$xtarget = $this->container->get('kernel')->getRootdir(). '/../web/uploads/travel_guide_photos/scaled_'  . $filename;
		imagejpeg($new_image, $xtarget, 100);

		exit;
	}

	public function deleteGuideAction(Request $request)
	{
		$slug = $request->get('slug');
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($slug);

		if(!is_null($eguide) && $eguide->getStatus() != $this->get('buggl_main.constants')->get('deleted')){
			$eguide->setIsFeaturedInHome(0);
			$eguide->setIsFeaturedInCountry(0);
			$eguide->setIsFeaturedInProfile(0);
			$eguide->setStatus($this->get('buggl_main.constants')->get('deleted'));
			$this->getDoctrine()->getEntityManager()->persist($eguide);
			$this->getDoctrine()->getEntityManager()->flush();
			
			$event = new \Buggl\MainBundle\Event\ActivityEvent($eguide,$eguide->getLocalAuthor(),null,$this->get('buggl_main.constants')->get('ACTIVITY_DELETE_GUIDE'));
			$this->get('event_dispatcher')->dispatch('buggl.activity',$event);
			
			$this->get('session')->getFlashBag()->add('success', 'Guide "'.$eguide->getPlainTitle().'" deleted!');
		}
		else{
			$this->get('session')->getFlashBag()->add('error', 'Guide "'.$eguide->getPlainTitle().'" does not exist!');
		}

		
		return new RedirectResponse($request->headers->get('referer'));
	}

	public function archiveGuideAction(Request $request)
	{
		$slug = $request->get('slug');
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($slug);

		if(!is_null($eguide)){
			$eguide->setIsFeaturedInHome(0);
			$eguide->setIsFeaturedInCountry(0);
			$eguide->setIsFeaturedInProfile(0);
			$eguide->setStatus($this->get('buggl_main.constants')->get('archived'));
			$this->getDoctrine()->getEntityManager()->persist($eguide);
			$this->getDoctrine()->getEntityManager()->flush();
		}

		$this->get('session')->getFlashBag()->add('success', 'Guide "'.$eguide->getPlainTitle().'" archived!');
		return new RedirectResponse($request->headers->get('referer'));
	}

	public function unarchiveGuideAction(Request $request)
	{
		$slug = $request->get('slug');
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($slug);

		if(!is_null($eguide)){
			$eguide->setStatus($this->get('buggl_main.constants')->get('published'));
			$this->getDoctrine()->getEntityManager()->persist($eguide);
			$this->getDoctrine()->getEntityManager()->flush();
		}

		$this->get('session')->getFlashBag()->add('success', 'Guide "'.$eguide->getPlainTitle().'" has been published!');
		return new RedirectResponse($request->headers->get('referer'));
	}

	/** 
	 *
	 * function that creates itinerary
	 */
	public function createItineraryAction(Request $request)
	{
		$slug = $request->get('guide_slug');
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($slug);
		if( !is_null($eguide) ){
			$itinerary = new Itinerary();
			$itinerary->setEGuide($eguide);
			$itinerary->setDayNum(1);
			
			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($itinerary);
			$em->flush();

			// set eguide real_duration
			$eguide->setRealDuration(1);
			$em->persist($eguide);
			$em->flush();

			$response = array("message" => "Itinerary successfully created!", "status" => TRUE);
			return new JsonResponse($response,200); 
		}
		$response = array("message" => "An error was encountered when trying to initialize your itinerary!", "status" => FALSE);
		return new JsonResponse($response,200); 
	}
	
	public function republishAction(Request $request)
	{
		$id = $request->get('id');
		
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($id);
		if( !is_null($eguide) ){
			$event = new RepublishEvent($this->get('buggl_main.mail_message_builder'),$eguide,$this->get('buggl_main.constants')->get('BUGGL_EMAIL'));
	    	$this->get('event_dispatcher')->dispatch('buggl.republish_eguide',$event);

			$this->get('session')->getFlashBag()->add('success', 'Guide was submitted for approval. Awaiting verification by admin.');
		}
		
		return new RedirectResponse($request->headers->get('referer'));
	}

	

      

	 
}