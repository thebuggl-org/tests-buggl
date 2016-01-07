<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Form\FormError;

use Buggl\MainBundle\Form\Type\LocalAuthorType;
use Buggl\MainBundle\Event\RegistrationEvent;
use Buggl\MainBundle\Entity\LocalAuthor;

class RegistrationController extends Controller
{
	public function registrationModalAction(Request $request)
	{
		$session = $this->getRequest()->getSession();
		if(!$session->has('redirect')){
			$session->set('redirect', $request->headers->get('referer'));
		}
		
		if ($request->getMethod() == 'POST') {
			$email = $request->get('email');
			$firstName = $request->get('first_name');
			$lastName = $request->get('last_name');

			$session = $this->getRequest()->getSession();

			$session->set('via_modal',true);

			$session->set('email',$email);
			$session->set('first_name',$firstName);
			$session->set('last_name',$lastName);
		}

		return $this->redirect($this->generateUrl('registration'));
	}


    public function indexAction( Request $request )
    {
		$session = $this->getRequest()->getSession();
		if(!$session->has('redirect')){
			$session->set('redirect', $request->headers->get('referer'));
		}
		
        $securityContext = $this->get('security.context');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            return new RedirectResponse($this->generateUrl('buggl_homepage'));
        }

        $session = $this->getRequest()->getSession();
		$token = $request->get('ref','');
		if(!empty($token)){
			$session->set('invitation_token', $token);
		}

		$facebookRegistrationUrl = $this->generateUrl('signup_via_facebook_url');
		$twitterRegistrationUrl = $this->generateUrl('signup_via_twitter_url');
		$googlePlusRegistrationUrl = $this->generateUrl('signup_via_google_plus_url');
		$localAuthor = new LocalAuthor();

		if ($session->get('via_modal')) {
			$email = $session->remove('email');
			$firstName = $session->remove('first_name');
			$lastName = $session->remove('last_name');

			$localAuthor->setEmail($email);
			$localAuthor->setFirstName($firstName);
			$localAuthor->setLastName($lastName);

			$session->remove('via_modal');
        }

		$form = $this->createForm(new LocalAuthorType(0), $localAuthor);
		$response = $this->handleRegistration($request, $form);

		if(!is_null($response))
			return $response;

		$slug = substr( strrchr($request->getUri(), "/"), 1 );
		$metas = $this->get('buggl_seo.static_page')->buildMetaAttributes($slug);

		$googleApiKey = $this->get('buggl_main.constants')->get('google_maps_api_key');
		$data = array(
			'form' => $form->createView(),
			'googleApiKey' => $googleApiKey,
			'facebookRegistrationUrl' => $facebookRegistrationUrl,
			'twitterRegistrationUrl' => $twitterRegistrationUrl,
			'googlePlusRegistrationUrl' => $googlePlusRegistrationUrl,
			'requirePassword' => true,
			'metas' => $metas
		);

        return $this->render('BugglMainBundle:Frontend/Registration:registration.html.twig', $data);
    }

	public function registrationViaFacebookAction( Request $request )
	{
		$localAuthor = null;
		$fbInfo = array();

		$accessToken = $request->query->get('access_token','');
		if (!empty($accessToken)) {
			$facebookService = $this->get('buggl_main.facebook_service');
			$fbInfo = $facebookService->getFbInfo($accessToken);
			$fbInfo['fbAccessToken'] = $accessToken;

		 	$localAuthor = $facebookService->getFBPrefilledLocalAuthor( $fbInfo );

			if(is_null($localAuthor))
		 		throw $this->createNotFoundException('Valid Access Token Not Found');
		 	else if($facebookService->checkIfFBAccountInUse($fbInfo['fbId'])){
		 		$localAuthor = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:SocialMedia')->findOneBy(array('fbId' => $fbInfo['fbId']))->getLocalAuthor();
		 		$referer = $this->manuallyAuthenticateUser($localAuthor);
				return new RedirectResponse($referer);
		 	}
		}
		else{
			throw $this->createNotFoundException('Valid Access Token Not Found');
		}
		
		/*
		 * WTF!!! Too many ifs, refactor this please (note to self haha...)
		 */
		$form = $this->createForm(new LocalAuthorType(5,$fbInfo), $localAuthor);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			$params = $request->request->get('LocalAuthor');
			
			$valid  = $this->validateCountryAndCity($params,$form);
	        if ($form->isValid() && $valid) {				
				$registrationService = $this->get('buggl_main.registration_service');
				$friendCount = $facebookService->countFriends($accessToken);
				$params['fbId'] = $fbInfo['fbId'];
				$params['fbUrl'] = $fbInfo['fbUrl'];
				$params['fbWork'] = $fbInfo['fbWork'];
				$params['fbBirthday'] = $fbInfo['fbBirthday'];
				$params['fbAccessToken'] = $accessToken;
				$params['fbFriendsCount'] = $friendCount;
				
		 		$localAuthor = $registrationService->registerLocalAuthor($params);
				
		        $session = $this->getRequest()->getSession();
				$session->get('invitation_token');
				$betaInviteService = $this->get('buggl_main.beta_invite_service');
				$betaInviteService->saveSuccessFullInvite($localAuthor,$session->get('invitation_token'));
				$session->remove('invitation_token');
				
				$session = $this->getRequest()->getSession();
				$redirect = $session->get('redirect',null);
				
				$event = new RegistrationEvent($localAuthor,$redirect);
                $this->get('event_dispatcher')->dispatch('buggl.local_author_registration',$event);
				
				$streetCreditService = $this->get('buggl_main.street_credit');
				$streetCreditService->updateConnectStatus($localAuthor);

				/*if($params['email'] == $params['fbEmail']){
			 		$this->manuallyAuthenticateUser($localAuthor);
					/*
					if($localAuthor->getIsLocalAuthor() == 1){
						$this->get('session')->getFlashBag()->add('success', "Congratulations you are now a Local Author. You can now create your very own guide.");
						
						// decide redirect here
						// new RedirectResponse($referer); 
						// $this->generateUrl('add_travel_guide_info');
				 		return $this->generateUrl('add_travel_guide_info');
					}
					else{
						$this->get('session')->getFlashBag()->add('success', "Congratulations you are now registered.");
				 		return new RedirectResponse($referer);
					}
					
					
			 		return new RedirectResponse($this->generateUrl('registration_confirmed_success',array('referer' => urlencode($redirect))));
				}
				else{
					return new RedirectResponse($this->generateUrl('registration_success',array('email' => $localAuthor->getEmail())));
				}*/
				
				$redirect = $this->manuallyAuthenticateUser($localAuthor, true);
					
				return new RedirectResponse($redirect);
	        }
	    }
		
		$slug = substr( strrchr($request->getUri(), "/"), 1 );
		$metas = $this->get('buggl_seo.static_page')->buildMetaAttributes($slug);
		$googleApiKey = $this->get('buggl_main.constants')->get('google_maps_api_key');
        return $this->render('BugglMainBundle:Frontend/Registration:registration.html.twig', array('form' => $form->createView(),'registerViaFacebook' => true,'metas' => $metas, 'googleApiKey' => $googleApiKey));
	}

	public function registrationViaTwitterAction( Request $request )
	{
		$session = $this->getRequest()->getSession();
		$accessToken = $session->get('twitter_oauth_token');
		$tokenSecret = $session->get('twitter_oauth_token_secret');
		$localAuthor = null;
		$twitterInfo = array();

		if ($accessToken != false) {
			$twitterService = $this->get('buggl_main.twitter_service');
			$twitterInfo = $twitterService->getTwitterInfo($accessToken,$tokenSecret);
			$twitterInfo['twitterAccessToken'] = $accessToken;
			$twitterInfo['twitterTokenSecret'] = $tokenSecret;

		 	$localAuthor = $twitterService->getTwitterPrefilledLocalAuthor( $twitterInfo );
			if(is_null($localAuthor))
		 		throw $this->createNotFoundException('Valid Access Token Not Found');
		 	else if($twitterService->checkIfTwitterAccountInUse($twitterInfo['twitterId'])){
		 		$localAuthor = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:SocialMedia')->findOneBy(array('twitterId' => $twitterInfo['twitterId']))->getLocalAuthor();
		 		$referer = $this->manuallyAuthenticateUser($localAuthor);

		 		return new RedirectResponse($referer);
		 	}
		}
		else{
			throw $this->createNotFoundException('Valid Access Token Not Found');
		}

		$form = $this->createForm(new LocalAuthorType(6,$twitterInfo), $localAuthor);
		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
			$params = $request->request->get('LocalAuthor');
			$valid = $this->validateCountryAndCity($params,$form);
	        if ($form->isValid() && $valid) {				
				$params['twitterFollowersCount'] = $twitterService->countTwitterFollowers($accessToken,$tokenSecret,$twitterInfo['twitterId']);

				$registrationService = $this->get('buggl_main.registration_service');
		 		$localAuthor = $registrationService->registerLocalAuthor($params);
				
		        $session = $this->getRequest()->getSession();
				$session->get('invitation_token');
				$betaInviteService = $this->get('buggl_main.beta_invite_service');
				$betaInviteService->saveSuccessFullInvite($localAuthor,$session->get('invitation_token'));
				$session->remove('invitation_token');
				
				$streetCreditService = $this->get('buggl_main.street_credit');
				$streetCreditService->updateConnectStatus($localAuthor);

				$event = new RegistrationEvent($localAuthor);
                $this->get('event_dispatcher')->dispatch('buggl.local_author_registration',$event);

		 		return new RedirectResponse($this->generateUrl('registration_success',array('email' => $localAuthor->getEmail())));
	        }
	    }

        return $this->render('BugglMainBundle:Frontend/Registration:registration.html.twig', array('form' => $form->createView(),'registerViaTwitter' => true));
	}

	public function registrationViaGooglePlusAction( Request $request )
	{
		$session = $this->getRequest()->getSession();
		$accessToken = $session->get('google_plus_access_token');
		$refreshToken = $session->get('google_plus_refresh_token');
		$localAuthor = null;
		$googleInfo = array();

		if ($accessToken != false) {
			$googlePlusService = $this->get('buggl_main.google_plus_service');
			$googleInfo = $googlePlusService->getGooglePlusInfo($accessToken);
			$googleInfo['googlePlusAccessToken'] = $accessToken;
			$googleInfo['googlePlusRefreshToken'] = $refreshToken;

		 	$localAuthor = $googlePlusService->getGooglePlusPrefilledLocalAuthor( $googleInfo );

			if(is_null($localAuthor))
		 		throw $this->createNotFoundException('Valid Access Token Not Found');
		 	else if($googlePlusService->checkIfGooglePlusAccountInUse($googleInfo['googlePlusId'])){
				$session->remove('google_plus_refresh_token');
		 		$localAuthor = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:SocialMedia')->findOneBy(array('googlePlusId' => $googleInfo['googlePlusId']))->getLocalAuthor();
		 		$referer = $this->manuallyAuthenticateUser($localAuthor);
		 		return new RedirectResponse($referer);
		 	}
		}
		else{
			throw $this->createNotFoundException('Valid Access Token Not Found');
		}

		$form = $this->createForm(new LocalAuthorType(7,$googleInfo), $localAuthor);
		$response = $this->handleRegistration($request, $form);

		if(!is_null($response))
			return $response;

        return $this->render('BugglMainBundle:Frontend/Registration:registration.html.twig', array('form' => $form->createView(),'registerViaGoogle' => true));
	}

	private function handleRegistration(Request $request, $form)
	{
		if ($request->getMethod() == 'POST') {
			//print_r($form); die;
			$form->bindRequest($request);
			$params = $request->request->get('LocalAuthor');
			$valid = $this->validateCountryAndCity($params,$form);
			
	        if ($form->isValid() && $valid) {
				$registrationService = $this->get('buggl_main.registration_service');
		 		$localAuthor = $registrationService->registerLocalAuthor($params);
				
		        $session = $this->getRequest()->getSession();
				$betaInviteService = $this->get('buggl_main.beta_invite_service');
				$betaInviteService->saveSuccessFullInvite($localAuthor,$session->get('invitation_token'));
				$session->remove('invitation_token');
				
				$streetCreditService = $this->get('buggl_main.street_credit');
				$streetCreditService->updateConnectStatus($localAuthor);

				$session = $this->getRequest()->getSession();
				$referer = $session->get('redirect',null);
				
				$event = new RegistrationEvent($localAuthor,$referer);
                $this->get('event_dispatcher')->dispatch('buggl.local_author_registration',$event);

				$referer = $this->manuallyAuthenticateUser($localAuthor, true);
				$this->get('session')->getFlashBag()->add('success', "Thank you for registering!");
				return new RedirectResponse($referer);
		 		///return new RedirectResponse($this->generateUrl('registration_success',array('email' => $localAuthor->getEmail())));
	        }
	    }

		return null;
	}

	public function registrationViaFacebookRedirectAction( Request $request )
	{
		$error = $request->query->get('error','');
		if($error != ''){
			return new RedirectResponse($this->generateUrl('buggl_homepage'));
		}

		$service = $this->get('buggl_main.facebook_service');
		$code = $request->query->get('code','');
		$accessToken = $service->getFacebookAccessToken($code, $this->generateUrl('registration_facebook_redirect'));

		if(is_null($accessToken)){
			throw $this->createNotFoundException('An unknown error has occured.');
		}

		return new RedirectResponse($this->generateUrl('registration_facebook',array('access_token' => $accessToken)));
	}

	public function registrationViaTwitterRedirectAction( Request $request )
	{
		$session = $this->getRequest()->getSession();
		$twitterService = $this->get('buggl_main.twitter_service');

		$oathToken = $request->get('oauth_token');
		$oathVerifier = $request->get('oauth_verifier');
	    $tokenSecret = $session->get('twitter_token_secret');

		if(is_null($oathToken)){
			return new RedirectResponse($this->generateUrl('buggl_homepage'));
		}

		$retArray = $twitterService->getAccessToken($oathToken, $tokenSecret, $oathVerifier, true, true);
		if (!empty($retArray)) {
			list($info, $headers, $body, $bodyParsed) = $retArray;
			if ($info['http_code'] == 200 && !empty($body)) {
			  $accessToken = $twitterService->rfc3986Decode($bodyParsed['oauth_token']);
			  $session->remove('twitter_token_secret');
			  $session->set('twitter_oauth_token',$accessToken);
			  $session->set('twitter_oauth_token_secret',$bodyParsed['oauth_token_secret']);
			}
		}

		return new RedirectResponse($this->generateUrl('registration_twitter'));
	}

	public function registrationViaGooglePlusRedirectAction( Request $request )
	{
		$code = $request->get('code');
		if(is_null($code)){
			return new RedirectResponse($this->generateUrl('buggl_homepage'));
		}
		$redirectUri = 'http://'.$_SERVER['HTTP_HOST'].$this->generateUrl('registration_google_plus_redirect');

		$authService = $this->get('buggl_main.oauth2_service');
		$tokens = $authService->getAccessToken($code, 'https://www.googleapis.com/auth/plus.login', $redirectUri, true);
		if(!empty($tokens)){
			$session = $this->getRequest()->getSession();
			$session->set('google_plus_access_token', $tokens['accessToken']);
			if(isset($tokens['refreshToken']))
				$session->set('google_plus_refresh_token', $tokens['refreshToken']);
		}

		return new RedirectResponse($this->generateUrl('registration_google_plus'));
	}

	public function registrationSuccessAction( Request $request )
	{
		$email = $request->get('email','');

		return $this->render('BugglMainBundle:Frontend/Registration:registrationSuccess.html.twig',array('email' => $email));
	}
	
	public function registrationConfirmedSuccessAction( Request $request )
	{
		$referer = urldecode($request->get('referer',null));
		$securityContext = $this->get('security.context');
		
		if(!$securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
			return new RedirectResponse($this->generateUrl('buggl_homepage'));
		}

		return $this->render('BugglMainBundle:Frontend/Registration:registrationConfirmedSuccess.html.twig',array('referer' => $referer));
	}

	public function registrationConfirmationAction( Request $request )
	{
		$token = urldecode($request->get('token',''));
		$email = $request->get('email','');
		$referer = urldecode($request->get('referer',null));
		
		$verifiedFlag = false;

		$registrationService = $this->get('buggl_main.registration_service');
		$localAuthor = $this->getDoctrine()->getRepository('BugglMainBundle:LocalAuthor')->findOneBy(array('email'=>$email));
		if(!is_null($localAuthor)){
			if($localAuthor->getEmailVerified()){
				$this->get('session')->getFlashBag()->add('notice', "This email is already verified.");
				return new RedirectResponse($this->generateUrl('buggl_homepage'));
			}

			$localAuthor = $registrationService->verifyLocalAuthor($localAuthor, $token);
			if($localAuthor){
				$event = new RegistrationEvent($localAuthor);
                $this->get('event_dispatcher')->dispatch('buggl.verify_email',$event);

				$this->manuallyAuthenticateUser($localAuthor, true);
				/*if($localAuthor->getIsLocalAuthor() == 1 && $localAuthor->getIsApproved() == 0){
					$this->get('session')->getFlashBag()->add('success', "Your email has been verified. You can now create your first guide.");
			 		return new RedirectResponse($this->generateUrl('add_travel_guide_info'));
				}
				else{
					$this->get('session')->getFlashBag()->add('success', "Your email has been verified.");
					return new RedirectResponse($referer);
				}*/
				return new RedirectResponse($this->generateUrl('registration_confirmed_success',array('referer' => urlencode($referer))));
			}
		}
		return new RedirectResponse($this->generateUrl('registration_confirm_fail'));
	}

	public function registrationConfirmationSuccessAction( Request $request )
	{
		return $this->render('BugglMainBundle:Frontend/Registration:confirmationSuccess.html.twig');
	}

	public function registrationConfirmationFailAction( Request $request )
	{
		return $this->render('BugglMainBundle:Frontend/Registration:confirmationFail.html.twig');
	}

	public function registrationConfirmationAlreadyVerifiedAction( Request $request )
	{
		return $this->render('BugglMainBundle:Frontend/Registration:confirmationAlreadyVerified.html.twig');
	}

	public function resendConfirmationAction( Request $request )
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();

		$registrationService = $this->get('buggl_main.registration_service');
		$registrationService->sendConfirmationEmail($localAuthor);

		return new RedirectResponse($this->generateUrl('local_author_login_details'));
	}
	
	/*
	 * temporary, quickfix
	 */
	private function validateCountryAndCity($params, $form)
	{
		$valid = true;
		return $valid; // bypass location check
		if($params['type'] == 0){
			return $valid;
		}
		
		$country = null;
		if($params['country'] != ''){
			$country = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Country')->findOneByName($params['country']);
		}
		
		if(is_null($country)){
			$form->get('country')->addError(new FormError('Please enter a valid country'));	
			$valid = false;
		}
		
		if($params['city'] == ''){
			$form->get('city')->addError(new FormError('Required'));	
			$valid = false;
		}
		
		return $valid;
	}

	private function manuallyAuthenticateUser( $localAuthor, $newRegistration = false )
	{
		$token = new UsernamePasswordToken($localAuthor, null, $localAuthor->getFirewall(), $localAuthor->getRoles());
		$this->get('security.context')->setToken($token);
		
		$session = $this->getRequest()->getSession();
		$referer = $session->get('redirect');
		
		if(is_null($referer) || $referer == $this->generateUrl('buggl_homepage',array(),true)){
			$referer = $this->generateUrl('local_author_dashboard');
		}
		else{
			$session->remove('redirect');
		}
		
		// very quick and lazy fix until
		// no plan on fixing this until rules are finalized
		// this will redirect users directly to paypal page if they are not logged in before they buy guide
		// but will redirect users back to overview page if the have not signed up before they buy guide
		if(strpos($referer,'buy-guide') && $newRegistration){
			$parts = explode('/',$referer);
			$guideId = $parts[count($parts)-1];
			$guide = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuide')->findOneById($guideId);
			if(!is_null($guide))
				$referer = $this->generateUrl('buggl_eguide_overview',array('slug' => $guide->getSlug()));
		}
		// end of fixing
		
		try {
	        $request = $this->container->get('request')->getSession()->set('_security_secured_area', serialize($token));
	    } catch(InactiveScopeException $e) {
			return $referer;
	    }
		
		return $referer;
	}
}