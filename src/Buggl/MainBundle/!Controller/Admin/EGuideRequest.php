<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controllers related to eguide request
 *
 * @author    Scott Amor, Cris Casas (interns), Noel (mentor) <noel.bacarisas@goabroad.com>
 *
 * @copyright 2013 May (c) Buggl.com
 */
class EGuideRequest extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{


    /**
     * default controller
     *
     * @return Response html page
     */
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
        $page = $this->getRequest()->get('page', 1);
        $limit = 10;

        if ($page < 1 || ($page > ceil($totalCount / $limit) && $totalCount > 0)) {
            return new RedirectResponse($this->generateUrl('local_author_reviews'));
        }

        $ids = $nativeQueryService->findReviewIdsRelatedToLocalAuthor($status, $page-1, $limit);
        $results = $service->getRepository('BugglMainBundle:Review')->findReviewsByPKs($ids);

        $data = array(
            'lists'=>$results,
            'tabTitle'=>'Approved Reviews',
            'limit' => $limit,
            'totalCount' => $totalCount,
            'currentPage' => $page,
            'url' => 'local_author_reviews'
        );

        return $this->render('BugglMainBundle:LocalAuthor\Review:index.html.twig', $data);
    }
}
