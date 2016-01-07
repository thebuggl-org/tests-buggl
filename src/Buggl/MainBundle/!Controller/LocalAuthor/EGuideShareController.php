<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EGuideShareController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
	
	public function guideShareAction(Request $request)
	{
		$slug = $request->get('slug');
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($slug);
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		
		if(is_null($eguide) || $eguide->getLocalAuthor()->getId() != $localAuthor->getId()){
			$this->get('session')->setFlash('error','The guide does not exist or is not yours!');
			return new RedirectResponse($this->generateUrl('local_author_dashboard'));
		}
		
		$socialMedia = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor);
		
		$prevEmails = '';
		$invalidMessage = '';
		if($request->isMethod('POST')){
			$invalidEmails = array();
			$validEmails = array();
			$prevEmails = $request->request->get('emails','');

			// TODO: maybe refactor email validation in a separate service
			$localReferenceService = $this->get('buggl_main.local_reference_service');
			$validationData = $localReferenceService->validateEmails($prevEmails);
			$invalidMessage = $validationData['invalidMessage'];
			if(empty($invalidMessage)){
				$prevEmails = '';
				exec('../app/console buggl:guide_share_email '.implode(',',$validationData['validEmails']).' '.$eguide->getId().' > /dev/null 2>&1 &');
				$this->get('session')->getFlashBag()->add('success', "Guide has been shared!");
				return new RedirectResponse($this->generateUrl('e_guide_share', array('slug'=>$eguide->getSlug())));
			}
		}
		
		$data = array(
			'eguide' => $eguide,
			'socialMedia' => $socialMedia,
			'invalidMessage' => $invalidMessage,
			'prevEmails' => $prevEmails,
		);
		
		return $this->render('BugglMainBundle:LocalAuthor/EGuideShare:share.html.twig', $data);
	}
	
	public function shareInFacebookAction(Request $request)
	{
		$slug = $request->get('slug');
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($slug);
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));
		
		if(!is_null($eguide) && $eguide->getLocalAuthor()->getId() == $localAuthor->getId()){
			$socialMedia = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor);
			$facebookService = $this->get('buggl_main.facebook_service');
		
			$photo = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuidePhoto')->findOneActiveByEguideAndType($eguide,1);
			$postData = array(
				"message"  => "I've published a travel guide called \"".$eguide->getPlainTitle()."\" on Buggl",
				"picture" => $photo->getPhoto(),
				"link" => $this->generateUrl('buggl_eguide_overview',array('slug' => $eguide->getSlug()),true)
			);
			$response = $facebookService->postStatus($postData,$socialMedia->getFbAccessToken());
			
			if(is_null($response)){
				$this->get('session')->setFlash('error','Your facebook account is not connected to Buggl!');
			}
			else{
				$this->get('session')->setFlash('success','Your guide has been shared in facebook!');
			}
		}
		else{
			$this->get('session')->setFlash('error','The guide does not exist or is not yours!');
		}
		
		
		return new RedirectResponse($this->generateUrl('e_guide_share', array('slug'=>$eguide->getSlug())));
	}
	
	public function shareInTwitterAction(Request $request)
	{
		$slug = $request->get('slug');
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($slug);
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));
		if(!is_null($eguide)  && $eguide->getLocalAuthor()->getId() == $localAuthor->getId()){
			$socialMedia = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor);
		
			$photo = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuidePhoto')->findOneActiveByEguideAndType($eguide,1);
			$photoUrl = $photo->getPhoto();
		
			$tweet = array(
				'media[]' => @file_get_contents($photoUrl),//"{@".$photoUrl."}",
				'status' => "I've published a travel guide called \"".$eguide->getPlainTitle()."\" on @Buggl.",
			);
			$this->get('buggl_main.twitter_service')->tweet($socialMedia->getTwitterAccessToken(),$socialMedia->getTwitterTokenSecret(),$tweet);
			$this->get('session')->setFlash('success','Your guide has been shared in twitter!');
		}
		else{
			$this->get('session')->setFlash('error','The guide does not exist or is not yours!');
		}
		
		return new RedirectResponse($this->generateUrl('e_guide_share', array('slug'=>$eguide->getSlug())));
	}
}