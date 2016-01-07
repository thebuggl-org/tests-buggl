<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardController extends Controller
{
    public function indexAction(Request $request)
    {
        $constants = $this->get('buggl_main.constants');

        $localRankOption = $constants->get('revenue_rank_location');
        $eguideRankOption = $constants->get('revenue_rank_eguide');
        $status = $constants->get('APPROVED_COUNTRY');

        $countries = $this->get('buggl_main.entity_repository')
                          ->getRepository('BugglMainBundle:Country')
                          ->findAllByStatus($status);

        $service = $this->get('buggl_main.admin_stats');

        $guideCounts = json_encode($service->getMontlyBuiltGuides(),JSON_NUMERIC_CHECK);
        $authorsCount = json_encode($service->getMonthlyJoinedAuthors(), JSON_NUMERIC_CHECK);
        $revenue = json_encode($service->getMonthlyBugglRevenue(),JSON_NUMERIC_CHECK);
        $locationCount = $this->get('buggl_main.entity_repository')
                              ->getRepository('BugglMainBundle:Country')
                              ->countAllByStatus($constants->get('REQUESTED_COUNTRY'));

        $options = array(
                'eguideRankOption' => $eguideRankOption,
                'localRankOption' => $localRankOption,
                'countries' => $countries,
                'guideCounts' => $guideCounts,
                'authorsCount' => $authorsCount,
                'revenue' => $revenue,
                // 'requestCount' => $requestCount,
                'locationCount' => $locationCount
            );

        return $this->render('BugglMainBundle:Admin/Dashboard:dashboard.html.twig',$options);
    }

    public function dashboardStatsAction()
    {
        $params = array(
            'start_date' => '2012-12-01',
            'end_date' => null,
            'filter' => 'pagePath !@ /admin/ && pagePath !@ /app_dev.php/',
            'aggregation' => 'year',
            'metrics' => array('pageviews','newVisits')
        );

        $analyticsService = $this->get('buggl_main.googleanalytics_service');
        $results = $analyticsService->getAnalyticsData($params);
        $totalpageViews = 0;
        foreach($results as $result){
            $totalpageViews += $result['pageviews'];
        }

        $results = $analyticsService->getAnalyticsData($params);
        $uniqueVisits = 0;
        foreach($results as $result){
            $uniqueVisits += $result['newVisits'];
        }

        $localAuthor = $this->get('security.context')->getToken()->getUser();

		/*
		NOTE: for stripe
        $earningsInCents = $this->getDoctrine()
                                ->getEntityManager()
                                ->getRepository('BugglMainBundle:PurchaseInfo')
                                ->sumNetAmountForBuggl();
		*/
        $earningsInCents = $this->getDoctrine()
                                ->getEntityManager()
                                ->getRepository('BugglMainBundle:PaypalPurchaseInfo')
                                ->sumNetAmountForBuggl();

        $totalPublishedGuides = $this->getDoctrine()
                                ->getEntityManager()
                                ->getRepository('BugglMainBundle:EGuide')
                                ->countPublishedGuides();
        $totalDownloadCount = $this->getDoctrine()
                                ->getEntityManager()
                                ->getRepository('BugglMainBundle:EGuide')
                                ->sumDownloadCount();
        $dashBoardStats = array(
            'totalPublishedGuides' => $totalPublishedGuides,
            'totalDownloadCount' => $totalDownloadCount,
            'earnings' => $earningsInCents
        );

        return $this->render('BugglMainBundle:Admin\Dashboard:dashboardStats.html.twig', array('stats' => $dashBoardStats));
    }

    public function eguideRankAction(Request $request)
    {
        $default = $this->get('buggl_main.constants')->get('revenue_rank_eguide');
        $filter = $request->get('filter',$default);

		// NOTE: for stripe divide values by 100
        // $repo = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:PurchaseInfo');
		$repo = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:PaypalPurchaseInfo');
        if($filter == $default){
            $objects = $repo->findEguideWithHighestNetAmount();

            foreach($objects as $object){

                $id = $object[0]->getEguide()->getId();
                $name = $object[0]->getEguide()->getPlainTitle();
                $amount = $object[1];

                $responseObjects[] = array(
                    'id' => $id,
                    'name' => $name,
                    'amount' => number_format($amount,2)
                );

            }
        }
        else{
            $objects = $repo->findEguideWithHighestNetAmountFilteredByCountry();

            foreach($objects as $object){

                $id = $object[0]->getEguide()->getId();
                $name = $object[0]->getEguide()->getCountry()->getName();
                $amount = $object[1];

                $responseObjects[] = array(
                    'id' => $id,
                    'name' => $name,
                    'amount' => number_format($amount,2)
                );

            }
        }

        // $response = array(
        //     'data' => $responseObjects,
        // );

        return new JsonResponse( $responseObjects, 200 );
    }

    public function userRankAction(Request $request)
    {
        $country = $request->get('filter',0);
		
		// NOTE: for stripe
        // $repo = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:PurchaseInfo');
		$repo = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:PaypalPurchaseInfo');
        $objects = $repo->findUserRank( $country );

        $responseObjects = array();
        foreach($objects as $object){

            $localAuthor = $object[0]->getBuyer();
            $id = $localAuthor->getId();
            $name = $localAuthor->getName();
            $amount = $object[1];

            $responseObjects[] = array(
                'id' => $id,
                'name' => $name,
                'amount' => $amount
            );
        }

        if(!count($responseObjects)){
            $responseObjects = null;
        }

        return new JsonResponse( $responseObjects, 200 );
    }
}