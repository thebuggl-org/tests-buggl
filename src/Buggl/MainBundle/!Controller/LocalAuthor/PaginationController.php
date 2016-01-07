<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PaginationController extends Controller
{

	public function paginationAction(Request $request)
	{
		$limit = $request->get('itemLimit',10);
		$currentPage = $request->get('currentPage',1);
		$softPageLimit = $request->get('softPageLimit',8);
		$hardPageLimit = $request->get('hardPageLimit',12);
		$itemCount = $request->get('itemCount',0);
		$dataUrl = $request->get('dataUrl','');

		$totalPages = floor($itemCount/$limit) + ($itemCount%$limit > 0 ? 1 : 0);
		if($totalPages < $currentPage)
			$currentPage = 1;

		$curPageLimit = $totalPages > $hardPageLimit ? $softPageLimit : $hardPageLimit;
		$pageGroup = floor($currentPage/$curPageLimit) + ($currentPage%$curPageLimit > 0 ? 1 : 0);
		$startPage = ($pageGroup - 1) * $curPageLimit + 1;

		if($totalPages > $hardPageLimit){
			$endPage = $startPage + $softPageLimit - 1;
			$endPage = $endPage > $totalPages ? $totalPages : $endPage;
		}
		else{
			$endPage = $totalPages;
		}

		$data = array(
			'totalPages' => $totalPages,
			'startPage' => $startPage,
			'endPage' => $endPage,
			'currentPage' => $currentPage,
			'pageLimit' => $curPageLimit,
			'dataUrl' => $dataUrl,
		);

		return $this->render('BugglMainBundle:LocalAuthor:pagination.html.twig',$data);
	}

	public function paginationViaPageLoadAction(Request $request)
	{
		$limit = $request->get('itemLimit',1);
		$currentPage = $request->get('page',1);
		$softPageLimit = $request->get('softPageLimit',8);
		$hardPageLimit = $request->get('hardPageLimit',12);
		$itemCount = $request->get('itemCount',0);
		$url = $request->get('url');

		$totalPages = floor($itemCount/$limit) + ($itemCount%$limit > 0 ? 1 : 0);
		$curPageLimit = $totalPages > $hardPageLimit ? $softPageLimit : $hardPageLimit;
		$pageGroup = floor($currentPage/$curPageLimit) + ($currentPage%$curPageLimit > 0 ? 1 : 0);
		$startPage = ($pageGroup - 1) * $curPageLimit + 1;

		if($totalPages > $hardPageLimit){
			$endPage = $startPage + $softPageLimit - 1;
		}
		else{
			$endPage = $totalPages;
		}

		$data = array(
			'totalPages' => $totalPages,
			'startPage' => $startPage,
			'endPage' => $endPage,
			'currentPage' => $currentPage,
			'pageLimit' => $curPageLimit,
			'url' => $url
		);

		// service maybe used to determine which template.

		return $this->render('BugglMainBundle:LocalAuthor/Review:pagination.html.twig',$data);
	}

	public function paginationMessageAction(Request $request)
	{
		$limit = $request->get('itemLimit',10);
		$currentPage = $request->get('currentPage',1);
		$softPageLimit = $request->get('softPageLimit',8);
		$hardPageLimit = $request->get('hardPageLimit',12);
		$itemCount = $request->get('itemCount',0);
		$dataUrl = $request->get('dataUrl','');

		$totalPages = floor($itemCount/$limit) + ($itemCount%$limit > 0 ? 1 : 0);
		$curPageLimit = $totalPages > $hardPageLimit ? $softPageLimit : $hardPageLimit;
		$pageGroup = floor($currentPage/$curPageLimit) + ($currentPage%$curPageLimit > 0 ? 1 : 0);
		$startPage = ($pageGroup - 1) * $curPageLimit + 1;

		if($totalPages > ($startPage + $softPageLimit - 1)){
			$endPage = $startPage + $softPageLimit - 1;
		}
		else{
			$endPage = $totalPages;
		}

		$data = array(
			'totalPages' => $totalPages,
			'startPage' => $startPage,
			'endPage' => $endPage,
			'currentPage' => $currentPage,
			'pageLimit' => $curPageLimit,
			'dataUrl' => $dataUrl,
		);

		return $this->render('BugglMainBundle:Admin/Messages:pagination.html.twig',$data);
	}
}