<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ReviewController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
    public function indexAction()
    {
        $securityContext = $this->get('security.context');
        $constants = $this->get('buggl_main.constants');

        $localAuthor = $securityContext->getToken()->getUser();

        $service = $this->get('buggl_main.entity_repository');

        $status = $constants->get('approved_review');
        $nativeQueryService = $this->get('buggl_main.review_native_query')
                                   ->setObjects(array('local_author' => $localAuthor));

        $totalCount = $nativeQueryService->countReviewsByStatus($status);
        $page = $this->getRequest()->get('page',1);
        $limit = 10;

        if($page < 1 || ($page > ceil($totalCount / $limit) && $totalCount > 0)){
            return new RedirectResponse($this->generateUrl('local_author_reviews'));
        }

        $ids = $nativeQueryService->findReviewIdsRelatedToLocalAuthor($status,$page-1,$limit);
        $results = $service->getRepository('BugglMainBundle:Review')->findReviewsByPKs($ids);

        $data = array(
            'lists'=>$results,
            'tabTitle'=>'Approved Reviews',
			'activeTab' => 'approved',
            'limit' => $limit,
            'totalCount' => $totalCount,
            'currentPage' => $page,
            'url' => 'local_author_reviews'
        );

        return $this->render('BugglMainBundle:LocalAuthor\Review:index.html.twig',$data);
    }

    public function deniedReviewsAction()
    {

        $securityContext = $this->get('security.context');
        $constants = $this->get('buggl_main.constants');

        $localAuthor = $securityContext->getToken()->getUser();

        $service = $this->get('buggl_main.entity_repository');

        $status = $constants->get('denied_review');
        $nativeQueryService = $this->get('buggl_main.review_native_query')
                                   ->setObjects(array('local_author' => $localAuthor));

        $totalCount = $nativeQueryService->countReviewsByStatus($status);
        $page = $this->getRequest()->get('page',1);
        $limit = 10;

        if($page < 1 || ($page > ceil($totalCount / $limit) && $totalCount > 0)){
            return new RedirectResponse($this->generateUrl('local_author_denied_reviews'));
        }

        $ids = $nativeQueryService->findReviewIdsRelatedToLocalAuthor($status,$page-1,$limit);
        $results = $service->getRepository('BugglMainBundle:Review')->findReviewsByPKs($ids);

        $data = array(
            'lists'=>$results,
            'tabTitle'=>'Denied Reviews',
			'activeTab' => 'denied',
            'limit' => $limit,
            'totalCount' => $totalCount,
            'currentPage' => $page,
            'url' => 'local_author_denied_reviews'
        );

        return $this->render('BugglMainBundle:LocalAuthor\Review:index.html.twig',$data);
    }

    public function navigationAction()
    {
		$request = $this->getRequest();
        $constants = $this->get('buggl_main.constants');
        $securityContext = $this->get('security.context');

        $service = $this->get('buggl_main.entity_repository');
        $localAuthor = $securityContext->getToken()->getUser();

        $travelGuideReviewCount = $service->getRepository('BugglMainBundle:TravelGuideReview')->countReviewsFilteredByLocalAuthor($localAuthor,$constants->get('unviewed_review'));
        $localAuthorReviewCount = $service->getRepository('BugglMainBundle:LocalAuthorReview')->countReviewsFilteredByLocalAuthor($localAuthor,$constants->get('unviewed_review'));

        $data = array(
                'travelGuideReviewCount' => $travelGuideReviewCount,
                'localAuthorReviewCount' => $localAuthorReviewCount,
				'active' => $request->get('activeTab')
            );

        return $this->render('BugglMainBundle:LocalAuthor\Review:navigation.html.twig',$data);
    }

    public function newTravelGuideReviewsAction()
    {
        $securityContext = $this->get('security.context');
        $constants = $this->get('buggl_main.constants');

        $localAuthor = $securityContext->getToken()->getUser();

        $status = $constants->get('unviewed_review');

        $repository = $this->get('buggl_main.entity_repository')
                           ->getRepository('BugglMainBundle:TravelGuideReview');

        $totalCount = $repository->countReviewsFilteredByLocalAuthor($localAuthor,$status);
        $page = $this->getRequest()->get('page',1);
        $limit = 10;

        if($page < 1 || ($page > ceil($totalCount / $limit) && $totalCount > 0)){
            return new RedirectResponse($this->generateUrl('local_author_new_travel_guides_reviews'));
        }

        $travelGuideReview = $repository->findAllReviewByLocalAuthor($localAuthor,$status,$page-1,$limit);

        $data = array(
            'lists'=>$travelGuideReview,
            'tabTitle'=>'For Approval Travel Guide Reviews',
			'activeTab' => 'new_guide_review',
            'limit' => $limit,
            'totalCount' => $totalCount,
            'currentPage' => $page,
            'url' => 'local_author_new_travel_guides_reviews'
        );

        return $this->render('BugglMainBundle:LocalAuthor\Review:index.html.twig',$data);
    }

    public function newLocalAuthorReviewsAction()
    {
        $securityContext = $this->get('security.context');
        $constants = $this->get('buggl_main.constants');

        $localAuthor = $securityContext->getToken()->getUser();

        $status = $constants->get('unviewed_review');

        $repository = $this->get('buggl_main.entity_repository')
                           ->getRepository('BugglMainBundle:LocalAuthorReview');

        $totalCount = $repository->countReviewsFilteredByLocalAuthor($localAuthor,$status);
        $page = $this->getRequest()->get('page',1);
        $limit = 10;

        if($page < 1 || ($page > ceil($totalCount / $limit) && $totalCount > 0)){
            return new RedirectResponse($this->generateUrl('local_author_new_reviews'));
        }

        $localAuthorReview = $repository->findAllReviewByLocalAuthor($localAuthor,$status,$page-1,$limit);

        $data = array(
            'lists'=>$localAuthorReview,
            'tabTitle'=>'For Approval Reviews of You',
			'activeTab' => 'new_author_review',
            'limit' => $limit,
            'totalCount' => $totalCount,
            'currentPage' => $page,
            'url' => 'local_author_new_reviews'
        );

        return $this->render('BugglMainBundle:LocalAuthor\Review:index.html.twig',$data);
    }

    public function changeStatusAction()
    {
        $request = $this->getRequest();

        $params = explode('-', $request->get('qString'));

        $reviewId = isset($params[1]) ? $params[1] : 0;
        $status = isset($params[0]) ? $params[0] : false;

        $securityContext = $this->get('security.context');
        $constants = $this->get('buggl_main.constants');

        $review = $this->get('doctrine.orm.entity_manager')->find('BugglMainBundle:Review',$reviewId);

        if(is_null($review)){
            return new \Symfony\Component\HttpFoundation\JsonResponse(array('success' => false),200);
        }

        $localAuthor = $securityContext->getToken()->getUser();

        if($review instanceof \Buggl\MainBundle\Entity\TravelGuideReview){
            $owner = $review->getEguide()->getLocalAuthor();
        }
        else{
            $owner = $review->getLocalAuthor();
        }

        if($owner->getId() == $localAuthor->getId()){
            if($status == 'true'){
                $review->setStatus($constants->get('approved_review'));
            }
            else{
                $review->setStatus($constants->get('denied_review'));
            }
        }

        $entityManager = $this->getDoctrine()->getEntityManager();

        $entityManager->persist($review);
        $entityManager->flush();

        return  new \Symfony\Component\HttpFoundation\JsonResponse(array('success' => true),200);
    }
}