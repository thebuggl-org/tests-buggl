<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StaticController extends Controller
{

	public function indexAction(Request $request)
	{
		$slug = $request->get('slug','');
		$staticContent = $this->getDoctrine()->getRepository('BugglMainBundle:StaticContent')->findOneByUrl($slug);

		if(is_null($staticContent)){
			throw $this->createNotFoundException('Page not found');
		}

		$data = array(
			'activeNav' => $slug,
			'staticContent' => $staticContent
		);

		return $this->render('BugglMainBundle:Frontend/Static:index.html.twig',$data);
	}

	public function aboutUsAction()
	{
		return $this->render('BugglMainBundle:Frontend/Static:aboutUs.html.twig');
	}

    public function contactUsAction(Request $request)
    {
    	$metas = $this->prepareMetaAttributes($request);
        return $this->render('BugglMainBundle:Frontend/Static:contactUs.html.twig', array('metas' => $metas) );
    }

    public function pressReleaseAction()
    {
        return $this->render('BugglMainBundle:Frontend/Static:pressRelease.html.twig');
    }

    public function ourMissionAction(Request $request)
    {
    	$metas = $this->prepareMetaAttributes($request);
        return $this->render('BugglMainBundle:Frontend/Static:ourMission.html.twig', array('metas' => $metas) );
    }

    public function ourTribeAction(Request $request)
    {
    	$metas = $this->prepareMetaAttributes($request);
        return $this->render('BugglMainBundle:Frontend/Static:ourStory.html.twig', array('metas' => $metas) );
    }

    public function writeAGuideAction(Request $request)
    {
    	$metas = $this->prepareMetaAttributes($request);
        $googleApiKey = $this->get('buggl_main.constants')->get('google_maps_api_key');
        return $this->render('BugglMainBundle:Frontend/Static:writeAGuide.html.twig', array('metas' => $metas, 'googleApiKey' => $googleApiKey) );
    }

    public function howItWorksAction(Request $request)
    {
    	$metas = $this->prepareMetaAttributes($request);
        return $this->render('BugglMainBundle:Frontend/Static:howItWorks.html.twig', array('metas' => $metas) );
    }
	
    public function faqAction(Request $request)
    {
    	$metas = $this->prepareMetaAttributes($request);
        return $this->render('BugglMainBundle:Frontend/Static:faq.html.twig', array('metas' => $metas) );
    }
	
	public function termsOfUseAction(Request $request)
	{
		$auth = $request->getSession()->has('buggl_beta_authenticated');

		$metas = $this->prepareMetaAttributes($request);
		$data = array(
			'showFooter' => $auth ? 'show' : null,
			'metas' => $metas
		);
		
		return $this->render('BugglMainBundle:Frontend/Static:termsOfUse.html.twig',$data);
	}
	
	public function pressAction(Request $request)
	{
		$metas = $this->prepareMetaAttributes($request);
		return $this->render('BugglMainBundle:Frontend/Static:press.html.twig', array('metas' => $metas) );
	}
	
	public function jobsAction(Request $request)
	{
		$metas = $this->prepareMetaAttributes($request);
		return $this->render('BugglMainBundle:Frontend/Static:jobs.html.twig', array('metas' => $metas) );
	}
	
    public function privacyPolicyAction(Request $request)
    {
    	$metas = $this->prepareMetaAttributes($request);
        return $this->render('BugglMainBundle:Frontend/Static:privacyPolicy.html.twig', array('metas' => $metas) );
    }

    public function contestAction()
    {
        return $this->render('BugglMainBundle:Frontend/Static:contestLandingPage.html.twig');
    }

    private function prepareMetaAttributes(Request $request)
    {
    	$slug = substr( strrchr($request->getUri(), "/"), 1 );
		return $this->get('buggl_seo.static_page')->buildMetaAttributes($slug);
    }
}