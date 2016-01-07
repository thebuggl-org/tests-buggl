<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DashboardController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{

	public function indexAction()
	{
		$constant = $this->get('buggl_main.constants');
		
		$service = $this->get('buggl_main.entity_repository');
		$limit = $constant->get('eguide_activities_dashboard_pagination');

		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$travelGuideReviewCount = $service->getRepository('BugglMainBundle:TravelGuideReview')->countReviewsFilteredByLocalAuthor($localAuthor,$constant->get('unviewed_review'));
        $localAuthorReviewCount = $service->getRepository('BugglMainBundle:LocalAuthorReview')->countReviewsFilteredByLocalAuthor($localAuthor,$constant->get('unviewed_review'));

		$count = $travelGuideReviewCount + $localAuthorReviewCount;

		$service = $this->get('buggl_main.follow')->init($localAuthor);

		$follower = $service->countFollowers();
		$following = $service->countFollowing();
		//print_r($localAuthor->getProfile());die;
		$em = $this->getDoctrine()->getEntityManager();
        $localauthorid = $em->getRepository('BugglMainBundle:LocalAuthor')->findOneById($localAuthor->getId()); 
        $image = $this->getDoctrine()->getRepository('BugglMainBundle:LocalAuthorPhoto')->findByLocalAuthor($localAuthor->getId());
      //  $imagepath=$image->getImageWebPath();
        if($localauthorid->getShortUrl()=='1' || $localauthorid->getShortUrl()=='')
        {
        	$slug=$localauthorid->getSlug();
        $biturl = $this->get('buggl_main.bit_url');
        $url=$biturl->make_bitly_url('http://www.buggl.com/local-author/'.$slug,'kamal52625','R_0ad3320171d348a48ed56215fc75ab89','json');
        //getImageWebPath()
       // $url= "<a target='_blank' href='http://dev.galaxyweblinks.com/buggl/web/local-author/'".$slug."><img src='".$imagepath."' width='60' height='60'></a>";
           $localauthorid->setShortUrl($url);
          
            $em->flush();
        }
		$constants = $this->get('buggl_main.constants');
		$status = array($constants->get('MSG_NEW'));
		$newMessagesCount = $this->getDoctrine()
			                     ->getEntityManager()
								 ->getRepository('BugglMainBundle:MessageToUser')
								 ->countMessagesByStatus($localAuthor,$status);

		$newEGuideRequestCount = $this->getDoctrine()
			                     ->getEntityManager()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countRequestByStatus($localAuthor,$status);


		$data = array(
			'activityLimit' => $limit,
			'count' => $count,
			'localauthor'=>$localauthorid,
			'follower' => $follower,
			'following' => $following,
			'newMessagesCount' => $newMessagesCount,
			'newRequestCount' => $newEGuideRequestCount,
			'currentDate' => date('Y-m-d H:i:s')
		);

		return $this->render('BugglMainBundle:LocalAuthor\Dashboard:dashboard.html.twig',$data);
	}

	public function activitiesAction(Request $request)
	{
		$returnType = $request->get('returnType','normal');
		$endDate = $request->get('endDate',date('Y-m-d H:i:s'));
		$page = $request->get('page',1);
		$limit = $this->get('buggl_main.constants')->get('activities_dashboard');
		$offset = ($page - 1) * $limit;

		$activityService = $this->get('buggl_main.activity_service');
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$activities = $activityService->getActivityFeed($localAuthor,$offset,$limit,$endDate);

		$hasNext = ($offset + $limit) < count($activities);
		$data = array(
			'activities' => $activities,
			'hasNext' => $hasNext
		);

		if($returnType == 'json'){
			$jsonData = array(
				'html' => $this->renderView('BugglMainBundle:LocalAuthor\Dashboard:activities.html.twig',$data)
			);
			return new JsonResponse($jsonData,200);
		}
		else{
			return $this->render('BugglMainBundle:LocalAuthor\Dashboard:activities.html.twig',$data);
		}
	}

	public function eguideActivitiesAction(Request $request)
	{
		$constants = $this->get('buggl_main.constants');
		$limit = $constants->get('eguide_activities_dashboard_pagination');
		$currentPage = $request->get('currentPage',0);
		$offset =  $currentPage > 0 ? ($currentPage-1) * $limit: 0;

		$localAuthor = $this->get('security.context')->getToken()->getUser();
		//$repository = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuideToEGuideActivity');
		$activities = array();//$repository->getActivitiesOfLocalAuthorEguides($localAuthor,$offset,$limit);

		return $this->render('BugglMainBundle:LocalAuthor\Dashboard:eguideActivities.html.twig',array('activities' => $activities));
	}

	public function profileCompletionAction()
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$profile = $localAuthor->getProfile();

		$neededData = array(
			//'getPhone' => 'You did not add your phone.',
			'getLocalSince' => 'Local Since.',
			'getInterestAndActivities' => 'Do you have no interest and activities?',
			'getBirthDate' => 'You did not mention your age.',
			'getProfilePic' => 'You have no Profile Pic.',
			'getAboutYou' => 'You did not share anything about you.',
			'getSelfComment' => 'What do you know about travel?',
			'getAccomplishments' => 'Any accomplishments you would like to share?'
		);
		$incompleteData = array();
		if(!is_null($profile)){
			foreach($neededData as $key => $val){
				$tempVal = $profile->$key();
				if(is_null($tempVal) || empty($tempVal)){
					$incompleteData[$key] = $val;
				}
			}
		}
		else{
			$incompleteData = $neededData;
		}

		$percentage = floor(( (count($neededData) - count($incompleteData)) / count($neededData)) * 100);

		$data = array(
			'incompleteData' => $incompleteData,
			'percentage' => $percentage
		);

		return $this->render('BugglMainBundle:LocalAuthor\Dashboard:profileCompletionPercentage.html.twig', $data);
	}
	
	public function paypalSettingsAlertAction()
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$showAlert = false;
		
		$paypalInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalInfo')->findByLocalAuthor($localAuthor);
		
		if(!$localAuthor->getIsLocalAuthor() || is_null($paypalInfo)){
			$showAlert = true;
		}
		
		$data = array(
			'showAlert' => $showAlert
		);
		
		return $this->render('BugglMainBundle:LocalAuthor\Dashboard:paypalSettingsAlert.html.twig', $data);
	}
	
	public function dashboardStatsAction()
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$filters = array();
		$filters[] = 'pagePath =~ '.$this->generateUrl('local_author_profile',array('slug'=>$localAuthor->getSlug()));
		
		// guide pages
		/*$guides = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findByLocalAuthor($localAuthor);
		foreach($guides as $guide){
			$filters[] = 'pagePath =~ /'.$guide->getSlug().'/';
		}*/
		
		$filter = empty($filters) ? null : implode(' || ',$filters);
		
		$params = array(
			'start_date' => '2012-12-01',
			'end_date' => null,
			'filter' => $filter,
			'aggregation' => 'year',
			'metrics' => array('pageviews')
		);

		$analyticsService = $this->get('buggl_main.googleanalytics_service');
		$results = $analyticsService->getAnalyticsData($params);

		$totalpageViews = 0;
		foreach($results as $result){
			$totalpageViews += $result['pageviews'];
		}

		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$downloadCount = $this->getDoctrine()
							  ->getRepository('BugglMainBundle:EGuide')
							  ->findDLSumByLocalAuthor($localAuthor);
		/*
		NOTE: for stripe divide values by 100
		$earningsInCents = $this->getDoctrine()
						 		->getRepository('BugglMainBundle:PurchaseInfo')
						 		->sumNetAmountForSeller($localAuthor);
		*/
		$earningsInCents = $this->getDoctrine()
						 		->getRepository('BugglMainBundle:PaypalPurchaseInfo')
						 		->sumNetAmountForSeller($localAuthor);
		
		$paymentsService = $this->get('buggl_main.buggl_payment_service');
		$credit = $paymentsService->getCreditValue($localAuthor);
		
		$bugglShare = $this->get('buggl_main.constants')->get('buggl_payment_share') * 100;
		$commission = 100 - ($bugglShare - $credit);
		
		$dashBoardStats = array(
			'pageViews' => $totalpageViews,
			'downloads' => $downloadCount,
			'earnings' => $earningsInCents,
			'commission' => $commission
		);

		return $this->render('BugglMainBundle:LocalAuthor\Dashboard:dashboardStats.html.twig', array('stats' => $dashBoardStats));
	}

	public function getNewAccessTokenAction()
	{
		return $this->render('BugglMainBundle:LocalAuthor\Dashboard:refreshToken.html.twig');
	}
	public function eguideRequestStatusAction(Request $request)
	{
		$status     = $request->get('status');
		
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getEntityManager();
        $localauthorid = $em->getRepository('BugglMainBundle:LocalAuthor')->findOneById($localAuthor->getId()); 
        	
        	if($status=='on')
        	{
        	$localauthorid->setEguideRequest(1);	
        	}
        	if($status=='off')
        	{
        	$localauthorid->setEguideRequest(0);	
        	}
           //$localauthorid->setShortUrl($url);
          
            $em->flush();
        $this->get('session')->getFlashBag()->add('Update', "Your Eguide request is updated"); 
		return new RedirectResponse($this->generateUrl('local_author_dashboard'));
	}
	public function eguideRequestPriceAction(Request $request)
	{
		$params     = $request->request->all();
		print_r($params);die;
		$price     = $request->post('pricecharge');
		
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$em = $this->getDoctrine()->getEntityManager();
        $localauthorid = $em->getRepository('BugglMainBundle:LocalAuthor')->findOneById($localAuthor->getId()); 

        	$localauthorid->setPriceCharge($price);	
     
           //$localauthorid->setShortUrl($url);
          
            $em->flush();
        $this->get('session')->getFlashBag()->add('Update', "Your Eguide request is updated"); 
		return new RedirectResponse($this->generateUrl('local_author_dashboard'));
	}
}