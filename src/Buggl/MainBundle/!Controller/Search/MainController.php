<?php

namespace Buggl\MainBundle\Controller\Search;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{

	public function searchResultAction(Request $request)
	{	
		$params = $request->query->all();
		
		$page = isset($params['page']) ? $params['page'] + 1 : 2;

		$location = $params['location'];
		$activity = $params['activity'];

		$params['baseUrl'] = $this->generateUrl('buggl_guide_search') . "?location={$location}&activity={$activity}";

		if(isset($params['sort']))
			$params['loadMoreUrl'] = $this->generateUrl('buggl_guide_search') . "?location={$location}&activity={$activity}&page={$page}&sort=".$params['sort'];
		else
			$params['loadMoreUrl'] = $this->generateUrl('buggl_guide_search') . "?location={$location}&activity={$activity}&page={$page}";

		$params['filters'] = array(
				'relevant' 	=> 'Relevant',
				'recent' 	=> 'Latest',
				'download'	=> 'Most Downloaded'
				);

		return $this->search($params);
	}

	public function browseAllAction(Request $request)
	{
		$params = $request->query->all();
		$params['location'] = "";
		$params['activity'] = "";
		$page = isset($params['page']) ? $params['page'] + 1 : 2;

		$params['baseUrl'] = $this->generateUrl('buggl_guide_browse_all');
		if(isset($params['sort']))
			$params['loadMoreUrl'] = $this->generateUrl('buggl_guide_browse_all') . "?page={$page}&sort=" . $params['sort'];
		else
			$params['loadMoreUrl'] = $this->generateUrl('buggl_guide_browse_all') . "?page={$page}";


		$params['filters'] = array(
				'relevant' 	=> 'Browse',
				'recent' 	=> 'Latest',
				'download'	=> 'Most Downloaded'
				);

		return $this->search($params);
		
	}

	private function search(array $params)
	{
		$this->get('buggl_main.guide_search')->sanitizeParams($params);
		$result = $this->get('buggl_main.guide_search')->execute();
		
		$location = $params['location'];
		$activity = $params['activity'];
		$type = (isset($params['sort']) AND in_array($params['sort'], array('relevant', 'recent', 'download')) ) ? $params['sort'] : 'relevant';
		
		$url = $params['baseUrl'];
		
		if( isset($params['type']) AND 'ajax' == $params['type'] )
		{
			$html = $this->renderView('BugglMainBundle:Frontend\Search:guides.html.twig',array('results'=>$result['objects']));
			$data = array(
				'html' 		=> $html,
				'hasNext' 	=> $result['hasNext'],
				'nextPage'	=> $result['nextPage'],
				'url' 		=> $url,
				'loadMoreUrl' => $params['loadMoreUrl']
				);

			return new JsonResponse($data, 200);
		}

		$metas = $this->get('buggl_seo.search_results')->buildMetaAttributes($params);
		
		$data = array(
			'activity' 	=> $params['activity'],
			'location' 	=> $params['location'],
			'results' 	=> $result['objects'],
			'hasNext' 	=> $result['hasNext'],
			'nextPage'	=> $result['nextPage'],
			'type' 		=> $type,
			'url' 		=> $url,
			'metas'		=> $metas,
			'filters'	=> $params['filters'],
			'loadMoreUrl' => $params['loadMoreUrl']
		);

		return $this->render('BugglMainBundle:Frontend\Search:searchResult.html.twig',$data);
	}

}
