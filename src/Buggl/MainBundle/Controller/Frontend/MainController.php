<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainController extends Controller
{
    public function homepageAction(Request $request)
    {
		/*
		NOTE: removed for now
		$token = $request->get('ref','');
		if($token != ''){
			$this->checkForShare($token);
			return new RedirectResponse($this->generateUrl('buggl_homepage'));
		}
		*/

		/**
		 * search variables
		 */
		$session = $request->getSession();
		$session->remove('page');
		$session->remove('parameter');

        $status = $this->get('buggl_main.constants')->get('APPROVED_COUNTRY');
        $countries = $this->get('buggl_main.entity_repository')
                          ->getRepository('BugglMainBundle:Country')
                          // ->findAllByStatus($status);
                          ->findCountryWithTravelGuides($this->get('buggl_main.constants')->get('published'));

        $status = $this->get('buggl_main.constants')->get('published_category');
        $categories = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:Category')
                           ->findByStatus($status);

        $status = $this->get('buggl_main.constants')->get('published');

        $repository = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide');
        $guides = $repository->findAllFeatured($status);
		$rotatingFeatures = $this->getDoctrine()->getRepository('BugglMainBundle:RotatingFeature')->findAll();

        $spotlight = array();
        $lists = array();
        foreach($guides as $guide){
            if( $guide->getIsSpotlight() ){
                $spotlight[] = $guide;
            }
            else{
                $lists[] = $guide;
            }
        }

        $metas = $this->get('buggl_seo.static_page')->buildMetaAttributes('homepage');

        return $this->render('BugglMainBundle:Frontend/Main:homepage.html.twig',
            array(
                'countries' => $countries,
                'guides' => $lists,
                'categories' => $categories,
                'spotlight' => $spotlight,
				'rotatingFeatures' => $rotatingFeatures,
				'metas' => $metas
            ));
    }

    public function beAguideAction()
    {
        return $this->render('BugglMainBundle:Frontend/Main:beAGuide.html.twig');
    }

	public function loginAction()
	{
		$request = $this->getRequest();
        $session = $request->getSession();

        $securityContext = $this->get('security.context');

        if(!is_null($securityContext->getToken()->getUser()) && $securityContext->isGranted('IS_AUTHENTICATED_FULLY') ){
            return new RedirectResponse($this->generateUrl('local_author_dashboard'));
        }

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('BugglMainBundle:Frontend:login.html.twig');
	}

    public function headerAction($active = '')
    {
        $securityContext = $this->get('security.context');

        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            // return $this->render('BugglMainBundle:LocalAuthor:navigation.html.twig',array('active'=>$active));
            return $this->render('BugglMainBundle::headerEmbed.html.twig',array('active'=>$active));
        }
        return $this->render('BugglMainBundle:Frontend/Main:navigation.html.twig');
    }

	public function loginViaFacebookAction()
	{
		$facebookService = $this->get('buggl_main.facebook_service');

        $securityContext = $this->get('security.context');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            return new RedirectResponse($this->generateUrl('buggl_homepage'));
        }

		$request = $this->getRequest();

		$session = $this->getRequest()->getSession();
		if(!$session->has('redirect')){
			$session->set('redirect', $request->headers->get('referer'));
		}

		$facebookRegistrationUrl = $facebookService->getFBConnectUrl($this->generateUrl('registration_facebook_redirect'));
		return new RedirectResponse($facebookRegistrationUrl);
	}

	public function loginViaTwitterAction()
	{
        $securityContext = $this->get('security.context');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            return new RedirectResponse($this->generateUrl('buggl_homepage'));
        }

		$oauthService = $this->get('buggl_main.twitter_service');
		$redirectUri = 'http://'.$_SERVER['HTTP_HOST'].$this->generateUrl('registration_twitter_redirect');
		$retArray = $oauthService->getRequestToken($redirectUri, false, true);
		if(!empty($retArray)){
			list($info, $headers, $body, $bodyParsed) = $retArray;
			if ($info['http_code'] == 200 && !empty($body)) {
		    	$session = $this->getRequest()->getSession();
				$session->set('twitter_token_secret', $bodyParsed['oauth_token_secret']);
				return new RedirectResponse($oauthService->getAuthorizationUrl($bodyParsed['oauth_token']));
		  	}
		}

		return new RedirectResponse($this->generateUrl('buggl_homepage'));
	}

	public function loginViaGooglePlusAction()
	{
        $securityContext = $this->get('security.context');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            return new RedirectResponse($this->generateUrl('buggl_homepage'));
        }

		$authService = $this->get('buggl_main.oauth2_service');
		$redirectUri = 'http://'.$_SERVER['HTTP_HOST'].$this->generateUrl('registration_google_plus_redirect');
		$authUrl = $authService->getAuthenticationUrl($redirectUri, 'https://www.googleapis.com/auth/plus.login', 'offline');

		return new RedirectResponse($authUrl);
	}

	public function revokeStripeAccessAction(Request $request)
	{
		// NOTE: Can't proceed, needs live url
		return new Response('ok',200);
		if($request->isMethod('POST')){
			$data = $request->request->all();
			$dataString = print_r($data, true);

			$message = new \Buggl\MainBundle\Entity\Message();
			$message->setContent($dataString);
			$message->setDateCreated(new \DateTime(date('Y-m-d H:i:s')));

			$this->getDoctrine()->getEntityManager()->persist($message);
			$this->getDoctrine()->getEntityManager()->flush();

			return new Response('ok',200);
		}

		throw $this->createNotFoundException('Requests not allowed through non-POST request');
	}

	public function renderPaymentFormAction(Request $request)
	{	$requestguide=0;
		$eguide = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('eguideId',''));
		$sellerStripeInfo = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:StripeInfo')->findbyLocalAuthor($eguide->getLocalAuthor());
		$buyer = null;
        $securityContext = $this->get('security.context');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $buyer = $securityContext->getToken()->getUser();
        }
        if($eguide->getisRequestId !=0)
        {
        	$requestguide=$eguide->getisRequestId(); 
        }
		if(!is_null($buyer) && !is_null($sellerStripeInfo)){
			$purchased = $this->getDoctrine()->getRepository('BugglMainBundle:PurchaseInfo')->findOneByBuyerAndEguide($buyer,$eguide);
			$data = array(
				'isrequestid'=>$requestguide,
				'okToRender' => true,
				'purchased' => !is_null($purchased),
				'ownGuide' => $buyer->getId() == $eguide->getLocalAuthor()->getId(),
				'amount' => $eguide->getPrice(),
				'description' => $eguide->getPlainTitle(),
				'paymentDescription' => $buyer->getEmail(),
				'pubKey' => $sellerStripeInfo->getStripePubKey(),
				'eguideId' => $eguide->getId(),
				'eguide' => $eguide
			);
		}
		else{
			$data = array(
				'okToRender' => false,
				'amount' => $eguide->getPrice()
			);
		}

		return $this->render('BugglMainBundle:Frontend\Main:paymentForm.html.twig', $data);
	}

	public function renderPaypalPaymentButtonAction(Request $request)
	{	
		$requestguide=0;
		$eguide = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('eguideId',''));
		$requestguide=$eguide->getisRequestId();
	///	print_r($eguide->getisRequestId()); die;
		$forWishlist = $request->get('forWishlist',false);
		$buyer = null;
        $securityContext = $this->get('security.context');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $buyer = $securityContext->getToken()->getUser();
        }
       // echo $requestguide; die;
		if($this->getRequest()->getSession()->has('has_admin_access')){
			$data = array(
				'requestguide'=>$requestguide,
				'okToRender' => true,
				'purchased' => true,
				'eguide' => $eguide,
				'forWishlist' => $forWishlist
			);
		}
		else if(!is_null($buyer)){
			// NOTE: for stripe
			// $purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($buyer,$eguide);

			$purchased = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($buyer,$eguide);
			$data = array(
				'okToRender' => true,
				'purchased' => $purchased,
				'ownGuide' => $buyer->getId() == $eguide->getLocalAuthor()->getId(),
				'eguide' => $eguide,
				'forWishlist' => $forWishlist
			);
		}
		else{
			$data = array(
				'okToRender' => false,
				'eguide' => $eguide,
				'forWishlist' => $forWishlist
			);
		}

		return $this->render('BugglMainBundle:Frontend\Main:paymentForm.html.twig', $data);
	}

	// NOTE: this is not the actual action, see in PaymentsController, this is kept for reference
	public function buyGuideAction(Request $request)
	{
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('eguideId',''));
		$buyer = $this->get('security.context')->getToken()->getUser();

		if($eguide->getPrice() == 0){
			$paymentsService = $this->get('buggl_main.buggl_payment_service');
			$paymentsService->saveFreePurchase($eguide,$buyer);
			return new RedirectResponse($this->generateUrl('buggl_eguide_overview',array('slug'=>$eguide->getSlug())));
		}
		else{
			//check for null
			//check if already purhcased
			$cancelUrl = $request->headers->get('referer');

			$paymentService = $this->get('buggl_main.buggl_payment_service');
			$paypalLink = $paymentService->getPaypalPaymentLink($eguide,$buyer,$cancelUrl,$request->getClientIp());

			if(is_null($paypalLink)){
				$this->get('session')->getFlashBag()->add('error', "There seems to be a problem with ".$eguide->getLocalAuthor()->getName()."'s paypal account. Please check back later while we resolve this issue.");
				return new RedirectResponse($cancelUrl);
			}

			return new RedirectResponse($paypalLink);
		}
	}

	public function processPaymentAction(Request $request)
	{
		$trackingId = $request->get('trackingId');
		$eguideId = $request->get('eguideId');
		$buyerId = $request->get('buyerId');

		$paymentService = $this->get('buggl_main.buggl_payment_service');
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($eguideId);
		$buyer = $this->getDoctrine()->getRepository('BugglMainBundle:LocalAuthor')->findOneById($buyerId);

		// check for purchase info with existing $trackingId to avoid duplicating record, or should we?
		$paypalPurchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByPaypalTrackingId($trackingId);
		if(is_null($paypalPurchaseInfo) || $paypalPurchaseInfo->getPaypalPaymentStatus() != $this->get('buggl_main.constants')->get('PAYPAL_PAYMENT_COMPLETED')  ){
			$paypalPurchaseInfo = $paymentService->processPayment($trackingId,$eguide,$buyer);

			//check purchase info status and set appropriate flash message here
			if(!is_null($paypalPurchaseInfo)){
				$this->get('session')->getFlashBag()->add('success', "Transaction successful!");

				/*
					remove this beacause DL count is different from guides purchased?
					$eguide->setDlCount($eguide->getDlCount() + 1);
					$this->getDoctrine()->getEntityManager()->persist($eguide);
					$this->getDoctrine()->getEntityManager()->flush();
				*/

				$constant = $this->get('buggl_main.constants');
				$event = new \Buggl\MainBundle\Event\ActivityEvent($paypalPurchaseInfo,$buyer,$eguide->getLocalAuthor(),$constant->get('ACTIVITY_PURCHASE_GUIDE'));
				$this->get('event_dispatcher')->dispatch('buggl.activity',$event);
			}
			else{
				$this->get('session')->getFlashBag()->add('error', "There was an error with the transaction!");
			}
		}
		
		$this->get('session')->set('buy-guide-status', true);
		return new RedirectResponse($this->generateUrl('buggl_eguide_secrets',array('slug'=>$eguide->getSlug())));
	}

	public function paypalIPNListenerAction()
	{
        $request = $this->get('request');
        if ($request->getMethod() != 'POST') {
            throw new \Exception("Invalid HTTP request method.");
        }

        $postData = $request->request->all();

		$eguideId = $request->get('eguideId');
		$buyerId = $request->get('buyerId');

		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($eguideId);
		$buyer = $this->getDoctrine()->getRepository('BugglMainBundle:LocalAuthor')->findOneById($buyerId);

		$paymentService = $this->get('buggl_main.buggl_payment_service');
		$response = $paymentService->verifyIPNTransaction($postData);
		// after verifying apply checks
		// maybe transfer verfification to BugglPaymentService and incorporate checks there
		// so we only get a boolean whether to process the message or not

		// if message is valid continue with processing ....
		// check for purchase info with existing $trackingId to avoid duplicating record, or should we?


		if($response){
			/*
			 too much drag to create a new table just for this
			 this will just save a temporary record if the verification succeeds
			 for now, we just bypass verification because as af now in sandbox, verification alwasy return invalid
			 we'll see what happens in prod
			*/
			$content = new \Buggl\MainBundle\Entity\StaticContent();
			$content->setContent($response);
			$content->setTitle('PAYPAL IPN4');
			$content->setUrl('paypalipn verification response');
			$this->getDoctrine()->getEntityManager()->persist($content);
			$this->getDoctrine()->getEntityManager()->flush();
		}

		if(!isset($postData['status']) || $postData['status'] != 'COMPLETED' || !isset($postData['tracking_id'])){
			// ignore IPN message if data is not in expected format or status is not yet complete, e.g. Pending
			exit;
		}

		$trackingId = $postData['tracking_id'];
		$paypalPurchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByPaypalTrackingId($trackingId);
		if(is_null($paypalPurchaseInfo) || $paypalPurchaseInfo->getPaypalPaymentStatus() != $this->get('buggl_main.constants')->get('PAYPAL_PAYMENT_COMPLETED')){
			$paypalPurchaseInfo = $paymentService->processPayment($trackingId,$eguide,$buyer);

			if(!is_null($paypalPurchaseInfo)){
				$constant = $this->get('buggl_main.constants');
				$event = new \Buggl\MainBundle\Event\ActivityEvent($paypalPurchaseInfo,$buyer,$eguide->getLocalAuthor(),$constant->get('ACTIVITY_PURCHASE_GUIDE'));
				$this->get('event_dispatcher')->dispatch('buggl.activity',$event);
			}
		}

		exit;
	}

	public function searchBarAction(Request $request)
	{
		if (!strpos($request->getUri(), '/search')) {
			return $this->render('BugglMainBundle:Frontend\Main:searchBar.html.twig');
		}
		return new Response(null);
	}

	public function freeSearchAction(Request $request)
	{
		$limit = 5;
		$session  = $request->getSession();

		if ($request->isMethod('POST') || $session->has('keyword_search')) {

			if ($request->isMethod('POST')) {
				$session->remove('keyword_search');
			}

			$status = $this->get('buggl_main.constants')->get('published');
			$keyword = $session->has('keyword_search') ? $session->get('keyword_search') : $request->get('keyword');
			$keyword = strtolower(trim($keyword));
			// $count = $this->get('buggl_main.guide_free_search')->count($keyword, $status);
			$page = (int)$request->get('page',1);
			$results = $this->get('buggl_main.guide_free_search')->search($keyword, $status, $limit, $page);
			$count = count($results);

			$totalPages = ceil($count/$limit);

			if ($page < 1) {
				$page = 1;
			} else if ($page > $totalPages){
				$page = $totalPages;
			}

			$data = array(
				'results' => $results,
				'keyword' => $keyword,
				'count' => count($results),
				'totalCount' => $count,
				'page' => $page,
				'limit' => $limit
			);

			if (!$session->has('keyword_search')) {
				$session->set('keyword_search',$keyword);
			}

			return $this->render('BugglMainBundle:Frontend\Main:search.html.twig',$data);
		}

		return new RedirectResponse($this->generateUrl('buggl_homepage'));
	}

	public function footerAction()
	{
		/*
		$service = $this->get('buggl_main.word_press_service');
    	$posts = $service->getPosts();
		*/

		$guides = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findRecentGuides();

		$posts = array();
		foreach($guides as $guide){
			$posts[] = array(
				'title' => $guide->getPlainTitle(),
				'link' => $this->generateUrl('buggl_eguide_overview',array('slug'=>$guide->getSlug()))
			);
		}

    	return $this->render('BugglMainBundle:Frontend\Main:footer.html.twig', array('posts' => $posts));
	}

	private function checkForShare($token)
	{
		$share = $this->getDoctrine()->getRepository('BugglMainBundle:BugglShare')->findOneByToken($token);
		if(!is_null($share) && $share->getStatus() == $this->get('buggl_main.constants')->get('SHARE_SENT')){
			$share->setStatus($this->get('buggl_main.constants')->get('SHARE_SUCCESS'));
			$this->getDoctrine()->getEntityManager()->persist($share);
			$this->getDoctrine()->getEntityManager()->flush();
		}
	}
}