<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

// use Buggl\MainBundle\Entity\EGuide;

class EGuideListController extends Controller
{
	public function showAction(Request $request)
	{
		$slug = $request->get('slug');
		$localAuthor = $this->get('buggl_main.entity_repository')
		                    ->getRepository('BugglMainBundle:LocalAuthor')
		                    ->findOneBy(array('slug' => $slug));
		
		$page = $request->get('page');
		$limit = 8;
		$guides = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findByLocalAuthorPaginator($localAuthor, 2, $limit, $page);
		
		$data = array(
			"localAuthor" => $localAuthor,
			"guides" => $guides,
			"limit" => $limit,
			"page" => $page
		);
		
		if( $request->isXmlHttpRequest() ){
			$totalPages = ceil($guides->count()/$limit);
			$data = array(
				'totalPages' => $totalPages,
				'nextPage' => $page + 1,
				'html' => $this->renderView('BugglMainBundle:Frontend/LocalAuthor:publishedGuideList.html.twig', $data)
				);
			return new JsonResponse($data,200);
		}
			
		
		return $this->render('BugglMainBundle:Frontend\LocalAuthor:authorGuideList.html.twig', $data);
	}
}