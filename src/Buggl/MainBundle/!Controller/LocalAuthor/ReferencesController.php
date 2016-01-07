<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;

class ReferencesController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{

	public function indexAction(Request $request)
	{
		$active = $request->get('type');
		$currentPage = $request->get('page',1);
		$constants = $this->get('buggl_main.constants');
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));
		$pendingStatuses = array($constants->get('LOCAL_REF_PENDING'),$constants->get('LOCAL_REF_SENT'));
		$pendingRequestCount = $this->getDoctrine()->getEntityManager()
							        ->getRepository('BugglMainBundle:LocalAuthorToLocalReference')
									->countRequestsByStatus($pendingStatuses,$localAuthor);

		$data = array(
			'pendingRequestCount' => $pendingRequestCount,
			'activeTab' => $active,
			'newRequestCount' => $newEGuideRequestCount,
			'currentPage' => $currentPage
		);

		return $this->render('BugglMainBundle:LocalAuthor\References:references.html.twig', $data);
	}

	public function referencesListAction(Request $request)
	{
		$active = $request->get('type');
		$constants = $this->get('buggl_main.constants');

		$limit = $constants->get('local_references_pagination');
		$currentPage = $request->get('currentPage',1);

		$offset =  $currentPage > 0 ? ($currentPage-1) * $limit : 0;
		$localAuthor = $this->get('security.context')->getToken()->getUser();

		$pendingStatuses = array($constants->get('LOCAL_REF_PENDING'),$constants->get('LOCAL_REF_SENT'));
		$status = strtoupper($active) == 'PENDING' ? $pendingStatuses : $constants->get('LOCAL_REF_LIST');

		$references = $this->getDoctrine()->getEntityManager()
										  ->getRepository('BugglMainBundle:LocalAuthorToLocalReference')
										  ->retrieveRequestsByStatus($status,$localAuthor,true,$offset,$limit);

		$softPageLimit = 8;
		$hardPageLimit = 12;

		/*$itemCount = count($references);
		$totalPages = floor($itemCount/$limit) + ($itemCount%$limit > 0 ? 1 : 0);

		$curPageLimit = $totalPages > $hardPageLimit ? $softPageLimit : $hardPageLimit;
		$pageGroup = floor($currentPage/$curPageLimit) + ($currentPage%$curPageLimit > 0 ? 1 : 0);
		$startPage = ($pageGroup - 1) * $curPageLimit + 1;

		if($totalPages > $hardPageLimit){
			$endPage = $startPage + $softPageLimit - 1;
		}
		else{
			$endPage = $totalPages;
		}*/

		$data = array(
			'activeTab' => $active,
			'references' => $references,
			'itemLimit' => $limit,
			'currentPage' => $currentPage,
			'softPageLimit' => $softPageLimit,
			'hardPageLimit' => $hardPageLimit,
			'dataUrl' => $this->generateUrl('references_pagination',array('type'=>$active))
		);

		return $this->render('BugglMainBundle:LocalAuthor\References:referencesList.html.twig', $data);
	}

	public function getAccessTokenAction(Request $request)
	{
		$code = $request->get('code');
		$redirectUri = 'http://'.$_SERVER['HTTP_HOST'].$this->generateUrl('get_oauth2_access_token');

		$authService = $this->get('buggl_main.oauth2_service');
		$accessToken = $authService->getAccessToken($code, 'https://www.google.com/m8/feeds', $redirectUri);
		if(!empty($accessToken)){
			$session = $this->getRequest()->getSession();
			$session->set('gmail_contacts_access_token', $accessToken);
		}

		return new RedirectResponse($this->generateUrl('request_gmail_references'));
	}

	public function requestGmailReferencesAction(Request $request)
	{
		/*
		// if we're going to use refreshToken
		// check for access first
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$socialMedia = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor);
		$hasGmailAccess = !is_null($socialMedia) && !is_null($socialMedia->getGmailRefreshToken()) && $socialMedia->getGmailRefreshToken() != "" ;
		*/

		$session = $this->getRequest()->getSession();
		$accessToken = $session->get('gmail_contacts_access_token');
		$hasAccess = $accessToken != false;
		$authUrl = '';
		$contacts = array();

		if($hasAccess){
			$gmailService = $this->get('buggl_main.gmail_contacts_service');
			$contacts = $gmailService->getGmailContacts($accessToken);
			if(isset($contacts['error']) && $contacts['error'] == 401){
				$hasAccess = false;
				$authService = $this->get('buggl_main.oauth2_service');
				$redirectUri = 'http://'.$_SERVER['HTTP_HOST'].$this->generateUrl('get_oauth2_access_token');
				$authUrl = $authService->getAuthenticationUrl($redirectUri, 'https://www.google.com/m8/feeds');
			}
			else{
				$localReferenceService = $this->get('buggl_main.local_reference_service');
				$contacts = $localReferenceService->truncateEmailsWithRequest($contacts,$this->get('security.context')->getToken()->getUser());
			}
		}
		else{
			$authService = $this->get('buggl_main.oauth2_service');
			$redirectUri = 'http://'.$_SERVER['HTTP_HOST'].$this->generateUrl('get_oauth2_access_token');
			$authUrl = $authService->getAuthenticationUrl($redirectUri, 'https://www.google.com/m8/feeds');
		}

		$data = array(
					'type' => 'gmail',
					'authUrl' => $authUrl,
					'hasAccess' => $hasAccess,
					'contacts' => $contacts
				);

		return $this->render('BugglMainBundle:LocalAuthor\References:requestReferences.html.twig', $data);
	}

	public function requestYahooMailReferencesAction(Request $request)
	{
		$session = $this->getRequest()->getSession();
		//$session->remove('yahoomail_oauth_token');
	    $accessToken = $session->get('yahoomail_oauth_token');
	    $accessTokenSecret = $session->get('yahoomail_oauth_token_secret');
	    $guid = $session->get('yahoomail_xoauth_yahoo_guid');

		$hasAccess = $accessToken != false;
		$authUrl = '';
		$contacts = array();
		$oauthService = $this->get('buggl_main.yahoomail_contacts_service');
		if($hasAccess){
			// retrieve contacts
			$contacts = $oauthService->retrieveContacts($guid,$accessToken,$accessTokenSecret);
			if(isset($contacts['error']) && $contacts['error'] == 401){
			    $accessToken = $session->remove('yahoomail_oauth_token');
			    $accessTokenSecret = $session->remove('yahoomail_oauth_token_secret');
			    $guid = $session->remove('yahoomail_xoauth_yahoo_guid');
				$hasAccess = false;
			}
			else{
				$localReferenceService = $this->get('buggl_main.local_reference_service');
				$contacts = $localReferenceService->truncateEmailsWithRequest($contacts,$this->get('security.context')->getToken()->getUser());
			}

		}
		else{
			// Authentication here
			$redirectUri = 'http://'.$_SERVER['HTTP_HOST'].$this->generateUrl('get_yahoomail_access_token');
			$retArray = $oauthService->getRequestToken($redirectUri, false);
			if(!empty($retArray)){
				list($info, $headers, $body, $bodyParsed) = $retArray;
				if ($info['http_code'] == 200 && !empty($body)) {
			    	$authUrl = $oauthService->rfc3986Decode($bodyParsed['xoauth_request_auth_url']);
					$session->set('yahoo_mail_token_secret', $bodyParsed['oauth_token_secret']);
			  	}
				else{
					return new RedirectResponse($this->generateUrl('request_yahoomail_references'));
				}
			}
		}

		$data = array(
					'type' => 'yahoomail',
					'authUrl' => $authUrl,
					'hasAccess' => $hasAccess,
					'contacts' => $contacts
				);

		return $this->render('BugglMainBundle:LocalAuthor\References:requestReferences.html.twig', $data);
	}

	public function getYahooMailAccessTokenAction(Request $request)
	{
		$session = $this->getRequest()->getSession();
		$tokenSecret = $session->get('yahoo_mail_token_secret');

		$oathVerifier = $request->get('oauth_verifier');
		$oathToken = $request->get('oauth_token');

		$oauthService = $this->get('buggl_main.yahoomail_contacts_service');
		$retarr = $oauthService->getAccessToken($oathToken, $tokenSecret, $oathVerifier, true);
		if (!empty($retarr)) {
			list($info, $headers, $body, $bodyParsed) = $retarr;
			if ($info['http_code'] == 200 && !empty($body)) {
				$session->remove('yahoo_mail_token_secret');
				$session->set('yahoomail_oauth_token',$oauthService->rfc3986Decode($bodyParsed['oauth_token']));
				$session->set('yahoomail_oauth_token_secret',$bodyParsed['oauth_token_secret']);
				$session->set('yahoomail_xoauth_yahoo_guid',$bodyParsed['xoauth_yahoo_guid']);
			}
		}

		return new RedirectResponse($this->generateUrl('request_yahoomail_references'));
	}

	public function sendLocalReferenceRequestEmailAction(Request $request)
	{
		if($request->isMethod('POST')){
			$localReferenceService = $this->get('buggl_main.local_reference_service');
			// save emails first
			$contacts = $request->request->get('contacts');
			$localAuthor = $this->get('security.context')->getToken()->getUser();

			foreach($contacts as $key => $contact){
				$contactParts = explode('|',$contact);
				if($localAuthor->getEmail() == $contactParts[0])
					continue;
				$localReferenceService->saveLocalReference($contactParts[0],$localAuthor,$key,$contactParts[1]);
			}

			//$localReferenceService->executeBatchEmailCommand($emails, $localAuthor->getId());
			exec('../app/console buggl:email_local_reference > /dev/null 2>&1 &');
		}

		/*$data = array(
			'status' => 'success'
		);
		return new JsonResponse($data,200);*/
		$this->get('session')->getFlashBag()->add('success', "Reference request sent! Send More?");
		if($request->get('type','gmail') == 'gmail'){
			return new RedirectResponse($this->generateUrl('request_gmail_references'));
		}
		else{
			return new RedirectResponse($this->generateUrl('request_yahoomail_references'));
		}

	}

	public function featureAction(Request $request)
	{
		if($request->isMethod('POST')){
			$localReferenceService = $this->get('buggl_main.local_reference_service');
			$references = $request->request->get('references');
			foreach(explode(' ',trim($references)) as $id){
				$reference = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalReference')->findOneBy(array('id' => $id));
				$localReferenceService->updateFeatureStatus($reference,1);
			}
		}

		return new RedirectResponse($this->generateUrl('local_author_references'));
	}

	public function featureAjaxAction(Request $request)
	{
		$id = $request->get('id',0);
		$reference = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalReference')->findOneBy(array('id' => $id));

		$data = array(
			'status' => 'ERROR',
			'message' => 'There was an error.'
		);

		if(!is_null($reference)){
			$localReferenceService = $this->get('buggl_main.local_reference_service');
			$localReferenceService->updateFeatureStatus($reference,1);
			
			$localAuthor = $this->get('security.context')->getToken()->getUser();
			$streetCreditService = $this->get('buggl_main.street_credit');
			$streetCreditService->updateVouchStatus($localAuthor);
			
			$data = array(
				'status' => 'SUCCESS',
				'message' => 'You have featured a reference.'
			);
		}

		return new JsonResponse($data,200);
	}

	public function unFeatureAjaxAction(Request $request)
	{
		$id = $request->get('id',0);
		$reference = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalReference')->findOneBy(array('id' => $id));

		$data = array(
			'status' => 'ERROR',
			'message' => 'There was an error!'
		);

		if(!is_null($reference)){
			$localReferenceService = $this->get('buggl_main.local_reference_service');
			$localReferenceService->updateFeatureStatus($reference,0);
			$data = array(
				'status' => 'SUCCESS',
				'message' => 'You have unfeatured a reference.'
			);
		}

		return new JsonResponse($data,200);
	}
	
	public function vouchAction(Request $request)
	{
		$prevEmails = '';
		$invalidMessage = '';
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		if($request->isMethod('POST')){
			$invalidEmails = array();
			$validEmails = array();
			$prevEmails = $request->request->get('emails','');

			$localReferenceService = $this->get('buggl_main.local_reference_service');
			$validationData = $localReferenceService->validateEmails($prevEmails);
			$invalidMessage = $validationData['invalidMessage']	;

			if(empty($invalidMessage)){
				
				$repo = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalAuthorToLocalReference');
				foreach($validationData['validEmails'] as $key => $email){
					$existing = $repo->retrieveOneByLocalAuthorAndEmail($localAuthor,$email,true);
					if(is_null($existing) || $localAuthor->getEmail() == $email)
						$localReferenceService->saveLocalReference($email,$localAuthor,$key);
				}
				$prevEmails = '';
				exec('../app/console buggl:email_local_reference > /dev/null 2>&1 &');
				$this->get('session')->getFlashBag()->add('success', "Local Reference Requests sent!");
			}
		}
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));

		$data = array(
			'invalidMessage' => $invalidMessage,
			'prevEmails' => $prevEmails,
			'newRequestCount' => $newEGuideRequestCount,
		);

		return $this->render('BugglMainBundle:LocalAuthor\References:vouch.html.twig', $data);
	}
}