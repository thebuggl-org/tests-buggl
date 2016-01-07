<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Buggl\MainBundle\Form\Type\LocalReferenceType;
use Buggl\MainBundle\Event\LocalReferenceEvent;

class ReferencesFrontendController extends Controller
{

	public function indexAction(Request $request)
	{
		$token = urldecode($request->get('token'));
		$localReferenceService = $this->get('buggl_main.local_reference_service');

		$localAuthorToLocalReference = $localReferenceService->validateToken($token);
		if(!$localAuthorToLocalReference){
			return new RedirectResponse($this->generateUrl('reference_request_invalid'));
		}

		$form = $this->createForm(new LocalReferenceType(), $localAuthorToLocalReference->getLocalReference());

		if($request->getMethod() == 'POST'){
			$form->bindRequest($request);
	        if ($form->isValid()) {
				$localReference = $localReferenceService->updateLocalReference($form->getData());

				$event = new LocalReferenceEvent($localReference);
                $this->get('event_dispatcher')->dispatch('buggl.local_reference_response',$event);
				
				$localReferenceService->sendLocalReferenceResponseNotification($localAuthorToLocalReference);

				return new RedirectResponse($this->generateUrl('reference_saved'));
			}
		}

		$data = array( 'form' => $form->createView() );

		return $this->render('BugglMainBundle:Frontend\References:index.html.twig', $data);
	}

	public function invalidTokenAction(Request $request)
	{
		return $this->render('BugglMainBundle:Frontend\References:invalidToken.html.twig');
	}

	public function referenceSavedAction(Request $request)
	{
		return $this->render('BugglMainBundle:Frontend\References:referenceSaved.html.twig');
	}

	public function localAuthorReferencesAction(Request $request)
	{
		$localAuthor = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalAuthor')->findOneBy(array('id' => $request->get('id')));
		$references = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalAuthorToLocalReference')->retrieveAllFeatureByLocalAuthor($localAuthor);
		$ownPage = $request->get('ownPage',false);
		
		return $this->render('BugglMainBundle:Frontend\References:lists.html.twig',array('references' => $references,'ownPage'=>$ownPage));
	}
}