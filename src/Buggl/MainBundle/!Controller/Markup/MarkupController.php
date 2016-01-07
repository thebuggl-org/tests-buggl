<?php

namespace Buggl\MainBundle\Controller\Markup;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MarkupController extends Controller
{

    private $markups;

    public function indexAction()
    {
        $this->markups = array(
            array('route'=>'result_page_mark_up','name'=>'frontend results page'),
            array('route'=>'homepage_mark_up','name'=>'frontend homepage'),
            array('route'=>'admin_mark_up','name'=>'admin view'),
            array('route'=>'admin_login_mark_up','name'=>'admin login'),
            array('route'=>'full_page_mark_up','name'=>'full page'),
            array('route'=>'guide_overview_mark_up','name'=>'guide overview'),
            array('route'=>'guide_before_leaving_mark_up','name'=>'guide before leaving'),
            array('route'=>'profile_mark_up','name'=>'local expert page')
        );

        return $this->render('BugglMainBundle:Markup/Markup:index.html.twig',array('markups'=>$this->markups));
    }


    public function homepageAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:homepage.html.twig');
    }

    public function adminAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:admin.html.twig');   
    }

    public function adminLoginAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:adminLogin.html.twig');   
    }

    public function resultPageAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:resultPage.html.twig');
    }

    public function fullpageAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:fullpage.html.twig');
    }

    public function guideOverviewAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:guideOverview.html.twig');
    }

    public function localExpertProfileAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:profile.html.twig');
    }

    public function guideBeforeLeavingAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:guideBefore.html.twig');   
    }

    public function guideItineraryAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:guideItinerary.html.twig');      
    }

    public function contactUsAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:contactUs.html.twig');    
    }

    public function aboutUsAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:aboutUs.html.twig');    
    }

    public function ourStoryAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:ourStory.html.twig');
    }

    public function ourMissionAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:ourMission.html.twig');
    }

    public function pressReleaseAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:pressRelease.html.twig');
    }

    public function localPlacesAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:localPlaces.html.twig');   
    }

    public function becomeAguideAction()
    {
        return $this->render('BugglMainBundle:Markup/Markup:becomeaguide.html.twig');
    }
}