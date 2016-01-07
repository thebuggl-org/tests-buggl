<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
	const SEARCH_LIMIT = 10;

	/**
	 * search first to limit guides
	 * @param  Request $request [description]
	 * @todo PLEASE REFACTOR: controller is bloated
	 */
	// THIS METHOD IS DEPRECATED
	public function searchAction(Request $request)
	{
		$activity = $request->get('activity','');
		$location = $request->get('location','');
		$page = $request->get('page',1);
		$type = $request->get('sort','relevant');

		$session = $request->getSession();

		if (!$session->has('page')) {
			$session->set('page', array(
				'relevant' => $type == 'relevant' ? $page : 1,
				'recent' => $type == 'recent' ? $page : 1,
				'download' => $type == 'download' ? $page : 1
			));
		}
		// if (!$session->has('parameter')) {
		$session->set('parameter',array(
			'activity' => $activity,
			'location' => $location,
			'sort' => $type
		));
		// }

		$pages = $session->get('page');
		$parameters = $session->get('parameter');
		foreach ($pages as $sort => $each ) {
			if ( $sort == $type) {
				$limit = $each * self::SEARCH_LIMIT;
			}
		}

		$parameters['sort'] = $type;
		$session->set('parameter',$parameters);


		// if ($request->isMethod('post')) {
		$status = $this->get('buggl_main.constants')->get('published');

		// $results = $this->get('buggl_main.guide_free_search')->search($activity, $location, $status, $limit, $page);
		$results = $this->get('buggl_main.guide_free_search')->search($activity, $location, $status, $limit, $page, $type);

		$url = $this->generateUrl('buggl_guide_search')."?location={$location}&activity={$activity}";

		$data = array(
			'activity' => $activity,
			'location' => $location,
			'results' => $results,
			'type' => $type,
			'url' => $url,
			'loadMore' => count($results) > $limit ? true : false
		);

		return $this->render('BugglMainBundle:Frontend\Search:result.html.twig',$data);
		// }

		// return new RedirectResponse($this->generateUrl('buggl_homepage'));
	}

	/**
	 * fetch more guides via ajax
	 * @param  Request $request
	 * @return JsonResponse
	 *
	 * @todo  PLEASE REFACTOR: Controller is bloated
	 */
	// THIS METHOD IS DEPRECATED
	public function fetchMoreAction(Request $request)
	{
		$session = $request->getSession();

		if (!$session->has('parameter')) {
			$data = array(
				'reload' => true
			);

			$session->getFlashBag()->add('success', "Page was reloaded");

			return new JsonResponse($data, 200);
		}

		$parameters = $session->get('parameter');
		$pages = $session->get('page');

		foreach ($pages as $type => $page ) {
			if ($parameters['sort'] == $type) {
				$pages[$type] = ++$page;
			}
		}
		$session->set('page',$pages);

		$activity = $parameters['activity'];
		$location = $parameters['location'];
		$status = $this->get('buggl_main.constants')->get('published');
		$type = $parameters['sort'];
		$page = $pages[$type];
		$limit = self::SEARCH_LIMIT;

		$results = $this->get('buggl_main.guide_free_search')->search($activity, $location, $status, $limit, $page, $type);

		if ($page == ceil(count($results)/$limit)) {
			$showLoadMore = false;
		} else {
			$showLoadMore = true;
		}

		$html = $this->renderView('BugglMainBundle:Frontend\Search:guides.html.twig',array('results'=>$results));

		$data = array(
			'page' => $page,
			'count' => count($results),
			'reload' => false,
			'results' => $html,
			'showLoadMore' => $showLoadMore,
		);

		return new JsonResponse($data, 200);
	}

	public function sortAction(Request $request)
 	{
 		$activity = $request->getSession()->get('activity');
 		$location = $request->getSession()->get('location');
 		$type = $request->get('type');
 		$page = $request->get('page',1);
 		$limit = self::SEARCH_LIMIT;
 		$status = $this->get('buggl_main.constants')->get('published');
 		$results = $this->get('buggl_main.guide_free_search')->search($activity, $location, $status, $limit, $page, $type);

 		$template = $this->renderView('BugglMainBundle:Frontend\Search:guideList.html.twig', array('results' => $results));

 		$data = array(
 			'html' => $template
 		);

 		return new JsonResponse($data, 200);
 	}

 	public function countAction(Request $request)
 	{
 		$activity = $request->getSession()->get('activity');
 		$location = $request->getSession()->get('location');
 		$type = $request->get('type');
 		$page = $request->get('page',1);
 		$limit = self::SEARCH_LIMIT;
 		$status = $this->get('buggl_main.constants')->get('published');
 		$results = $this->get('buggl_main.guide_free_search')->search($activity, $location, $status, $limit, $page, $type);

 		if ($page == ceil(count($results) / $limit)) {
 			$loadMore = false;
 		} else {
 			$loadMore = true;
 		}

 		$data = array(
 			'count' => count($results),
 			'loadMore' => $loadMore
 		);

 		return new JsonResponse($data, 200);
 	}

 	public function getEmptySearchResultTemplateAction(Request $request)
 	{
 		$security = $this->get('security.context');
 		if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
 			$countries = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Country')->findAllCountry();
        	$trip_themes = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:TripTheme')->findAllTheme();
        	$categories = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Category')->findAllCategory();
        	$durations = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Duration')->findAll();

        	$good_for = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:GoodFor')->findAll();

        	$data = array(
        		'countries' => $countries,
        		'activities' => $categories,
        		'themes' => $trip_themes,
        		'duration' => $durations,
        		'goodFor' => $good_for,
        		'activityId' => 0,
        		'duration' => 0
        	);

 			// $template =  $this->renderView('BugglMainBundle:Frontend\Guide:requestForm.html.twig',$data);
 			return $this->render('BugglMainBundle:Frontend\Guide:requestForm.html.twig',$data);
 		} else {
 			// $template = $this->renderView('BugglMainBundle:Frontend\LimitedView:loginfully.html.twig',array('action' => 'Login to Request Travel Guide'));
 			return $this->render('BugglMainBundle:Frontend\LimitedView:loginfully.html.twig',array('action' => 'Request Travel Guide'));
 		}
 		// $data = array(
 		// 	'html' => $template
 		// );

 		// return new JsonResponse($data, 200);
 	}

	public function autoSuggestLocationAction(Request $request)
	{
		$qString = $request->get('term');

		$result = $this->get('buggl_main.entity_repository')
                     ->getRepository('BugglMainBundle:EGuideLocation')
                     ->suggestLocation($qString);

        $data = array_map(function($value) {
         	return $value['address'];
         }, $result);

        $result = $this->get('buggl_main.entity_repository')
                       ->getRepository('BugglMainBundle:Country')
                       ->suggestLocation($qString);

        $dataCountry = array_map(function($value) {
         	return $value['name'];
         }, $result);

        $data = array_unique(array_merge($data, $dataCountry));
        sort($data);

		return new JsonResponse($data);
	}

	public function autoSuggestActivityAction(Request $request)
	{
		$qString = $request->get('term');

		$status = $this->get('buggl_main.constants')->get('published_category');

        $result = $this->get('buggl_main.entity_repository')
                       ->getRepository('BugglMainBundle:Category')
                       ->suggestActivity($qString, $status);

        $data = array_map(function($value) {
         	return $value['name'];
         }, $result);
        sort($data);

		return new JsonResponse($data);
	}

	public function recentGuidesAction(Request $request)
	{
		$limit = 8;
        $status = $this->get('buggl_main.constants')->get('published');
        $results = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findRecentGuidesV2($limit,$status);

        return $this->render('BugglMainBundle:Frontend\Search:recentGuides.html.twig',array('guides' => $results));
	}
}