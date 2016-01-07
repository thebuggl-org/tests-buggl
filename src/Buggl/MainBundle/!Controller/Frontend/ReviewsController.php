<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Buggl\MainBundle\Event\Mail\ReviewEvent;

class ReviewsController extends Controller
{

    /*
     * Improve or refactor if necessary
     *
     */

    public function saveTravelGuideReviewAction()
    {
        $securityContext = $this->get('security.context');

        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){

            $request = $this->getRequest();
            $params = $request->query->all();

            $data = $this->get('buggl_main.review')->init($params)->saveTravelGuideReview();
            $event = new ReviewEvent($this->get('buggl_main.mail_message_builder'),$data['review']);
            $this->get('event_dispatcher')->dispatch('buggl.review',$event);
        }
        else{
            $data = array('success'=>false);
        }
        $response = new \Symfony\Component\HttpFoundation\JsonResponse($data,200);
        return $response;
    }

    public function saveLocalAuthorReviewAction()
    {
        $securityContext = $this->get('security.context');

        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){

            $request = $this->getRequest();
            $params = $request->query->all();

            $data = $this->get('buggl_main.review')->init($params)->saveLocalAuthorReview();
            $event = new ReviewEvent($this->get('buggl_main.mail_message_builder'),$data['review']);
            $this->get('event_dispatcher')->dispatch('buggl.review',$event);
        }
        else{
            $data = array('success'=>false);
        }
        $response = new \Symfony\Component\HttpFoundation\JsonResponse($data,200);
        return $response;
    }

    public function travelGuideReviewsAction($travelGuide)
    {
        $status = $this->get('buggl_main.constants')->get('approved_review');

        $lists = $this->get('buggl_main.entity_repository')
                      ->getRepository('BugglMainBundle:TravelGuideReview')
                      ->findAllReviewByTravelGuide($travelGuide,$status);

        return $this->render('BugglMainBundle:Frontend\Reviews:lists.html.twig',array('lists' =>$lists,'isTravelGuide' => true));
    }

    public function localAuthorGuideReviewsAction($localAuthor)
    {
        $status = $this->get('buggl_main.constants')->get('approved_review');

        $lists = $this->get('buggl_main.entity_repository')
                      ->getRepository('BugglMainBundle:LocalAuthorReview')
                      ->findAllReviewByLocalAuthor($localAuthor,$status);


        return $this->render('BugglMainBundle:Frontend\Reviews:lists.html.twig',array('lists' =>$lists,'isTravelGuide' => false));
    }
}