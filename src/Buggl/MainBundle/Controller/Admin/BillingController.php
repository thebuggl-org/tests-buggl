<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class BillingController extends Controller{

    const LIMIT = 10;
    const GUIDE = 0;
    const SELLER = 1;
    const BUYER = 2;

    public function indexAction(Request $request)
    {
		/*
		NOTE: for stripe
        $sum = $this->get('buggl_main.entity_repository')
                    ->getRepository('BugglMainBundle:PurchaseInfo')
                    ->sumNetAmountForBuggl();
		*/
		
        $sum = $this->get('buggl_main.entity_repository')
                    ->getRepository('BugglMainBundle:PaypalPurchaseInfo')
                    ->sumNetAmountForBuggl();
		
		$currentPage = $request->get('page',1);
		
		// NOTE: for stripe divide amounts by 100
        // $repository = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:PurchaseInfo');
		$repository = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:PaypalPurchaseInfo');
        $objects = $repository->findRecent(self::LIMIT,$currentPage);
		if(count($objects->getIterator()) == 0){
			return new RedirectResponse($this->generateUrl('admin_billing'));
		}
		
		$softPageLimit = 8;
		$hardPageLimit = 12;
		
        $data = array(
            'sum' => number_format($sum,2),
            'limit' => self::LIMIT,
            'lists' => $objects,
            'totalCount' => count($objects),
			'currentPage' => $currentPage,
			'softPageLimit' => $softPageLimit,
			'hardPageLimit' => $hardPageLimit,
        );

        return $this->render('BugglMainBundle:Admin/Billing:index.html.twig',$data);
    }

    public function searchAction(Request $request)
    {

        $filter = $request->get('filter',self::GUIDE);
        $key = $request->get('key','');
        $page = $request->get('page',1);
		$filterFreeGuides = $request->get('filterFreeGuides',false);
        $limit = self::LIMIT;

		// NOTE: for stripe divide amounts by 100
        // $repository = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:PurchaseInfo');
		$repository = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:PaypalPurchaseInfo');
		
        if( $filter == self::GUIDE ){
            $objects = $repository->findBySearchFilterGuide($key,$limit,$page);
        }
        else if( $filter == self::SELLER ){
            $objects = $repository->findBySearchFilterSeller($key,$limit,$page);
        }
        else{
            $objects = $repository->findBySearchFilterBuyer($key,$limit,$page);
        }

        $data = array();
        foreach($objects as $object){
            $data[] = array(
                'guide_title' => $object->getEguide()->getPlainTitle(),
                'author' => $object->getSeller()->getName(),
                'buyer' => $object->getBuyer()->getName(),
                'price' => $object->getAmount(),
                'fee' => $object->getBugglFee(),
                'net_amount' => $object->getNetAmount(),
				'date_of_transaction' => $object->getDateOfTransaction()->format('M d, Y')
            );
        }

        $response = array(
            'count' => count($objects),
            'data' => $data
        );

        return new JsonResponse($response,200);
    }
}