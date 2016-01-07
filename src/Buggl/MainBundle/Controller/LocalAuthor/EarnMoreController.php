<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Buggl\MainBundle\Entity\StreetCredit;

class EarnMoreController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{

	public function earnMoreAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$streetCredit = $this->getDoctrine()->getRepository("BugglMainBundle:StreetCredit")->findOneByLocalAuthor($localAuthor);
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));

		if(is_null($streetCredit)){
			$streetCredit = new StreetCredit();
			$streetCredit->setLocalAuthor($localAuthor);
			
			$entityManager = $this->getDoctrine()->getEntityManager();
			$entityManager->persist($streetCredit);
			$entityManager->flush();
		}
		
		$paymentsService = $this->get('buggl_main.buggl_payment_service');
		$credit = $paymentsService->getCreditValue($localAuthor);
		
		$bugglShare = $this->get('buggl_main.constants')->get('buggl_payment_share') * 100;
		$commission = 100 - ($bugglShare - $credit);
		
		$data = array(
			'streetCredit' => $streetCredit,
			'newRequestCount' => $newEGuideRequestCount,
			'commission' => $commission
		);
		
		return $this->render('BugglMainBundle:LocalAuthor\EarnMore:earnMore.html.twig',$data);
	}
	
	public function shareBugglAction(Request $request)
	{
		$prevEmails = '';
		$invalidMessage = '';
		if($request->isMethod('POST')){
			$invalidEmails = array();
			$validEmails = array();
			$prevEmails = $request->request->get('emails','');

			$localReferenceService = $this->get('buggl_main.local_reference_service');
			$validationData = $localReferenceService->validateEmails($prevEmails);
			$invalidMessage = $validationData['invalidMessage']	;
			if(empty($invalidMessage)){
				$shareService = $this->get('buggl_main.share');
				$user = $this->get('security.context')->getToken()->getUser();
				foreach($validationData['validEmails'] as $key => $email){
					$share = $shareService->saveShareInfo($email,$user);
				}
				$prevEmails = '';
				exec('../app/console buggl:email_shares > /dev/null 2>&1 &');
				
				$streetCreditService = $this->get('buggl_main.street_credit');
				$streetCreditService->updateShareStatus($user);
				
				$this->get('session')->getFlashBag()->add('success', "Emails sent!");
			}
		}
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		//$streetCredit = $this->getDoctrine()->getRepository("BugglMainBundle:StreetCredit")->findOneByLocalAuthor($localAuthor);
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));

		$data = array(
			'invalidMessage' => $invalidMessage,
			'prevEmails' => $prevEmails,
			'newRequestCount' => $newEGuideRequestCount,
		);

		return $this->render('BugglMainBundle:LocalAuthor\EarnMore:shareBuggl.html.twig', $data);
	}
}