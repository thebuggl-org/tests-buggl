<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Buggl\MainBundle\Entity\LocalAuthor;

class LimitedViewController extends Controller
{
    public function addTravelGuideReviewFormAction($eguide)
    {
        $securityContext = $this->get('security.context');

        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $user = $securityContext->getToken()->getUser();

            $user = $this->get('security.context')->getToken()->getUser();

            $purchaseInfo = $this->get('buggl_main.entity_repository')
                                 ->getRepository('BugglMainBundle:PaypalPurchaseInfo')
                                 ->findOneByBuyerAndEguide($user,$eguide);

            $allowed = is_null($purchaseInfo) ? false: true;

            $data = array(
                'allowed' => $allowed
            );

            return $this->render('BugglMainBundle:Frontend/LimitedView:travelguidereviewform.html.twig',$data);
        }

        return $this->render('BugglMainBundle:Frontend/LimitedView:loginfully.html.twig',array('action'=>'Login to post review'));
    }

    public function addLocalAuthorReviewFormAction()
    {
        $securityContext = $this->get('security.context');

        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->render('BugglMainBundle:Frontend/LimitedView:localauthorreviewform.html.twig');
        }

        return $this->render('BugglMainBundle:Frontend/LimitedView:loginfully.html.twig',array('action'=>'Login to post review'));
    }

    public function addRequestButtonAction($slug)
    {
        $securityContext = $this->get('security.context');

        $isFollowing = 0;
        $loggedIn = false;
        $text = 'Follow';
        $isAdmin = false;

        $user = $securityContext->getToken()->getUser();
        $profileOwner = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalAuthor')->findOneBySlug($slug);

        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){

            if(!is_null($user)){
                $roles = $user->getRoles();
                foreach( $roles as $role ){
                    if( $role === 'ROLE_SUPER_ADMIN' || $role === 'ROLE_ADMIN' ){
                        $isAdmin = ($isAdmin OR true);
                    }
                }
            }

            if(!$isAdmin){
                $isFollowing = $this->get('buggl_main.follow')->isFollowing($securityContext->getToken()->getUser(),$slug) ? 1: 0;
                $loggedIn = true;
                $text = $isFollowing ? 'Following' : 'Follow';

            }
        }

        $data = array(
            'authenticated' => $loggedIn,
            'slug' => $slug,
            'text' => $text,
            'isFollowing' => $isFollowing,
            'user'=> $user,
            'profileOwner' => $profileOwner
            );

        return $this->render('BugglMainBundle:Frontend/LimitedView:actionbutton.html.twig',$data);
    }
}