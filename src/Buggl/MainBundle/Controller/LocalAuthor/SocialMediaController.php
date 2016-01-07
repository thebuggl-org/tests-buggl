<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use Buggl\MainBundle\Form\Type\SocialMediaType;

class SocialMediaController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{

	public function indexAction(Request $request)
	{	$localAuthor = $this->get('security.context')->getToken()->getUser();
		$newEGuideRequestCount = $this->getDoctrine()
			                     ->getEntityManager()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countRequestByStatus($localAuthor,array('0'=>'0'));

		return $this->render('BugglMainBundle:LocalAuthor\SocialMedia:socialMedia.html.twig',array('newRequestCount' => $newEGuideRequestCount));
	}
	
	public function socialConnectAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$socialMedia = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor,true);
		$form = $this->createForm(new SocialMediaType(),$socialMedia);

		if($request->isMethod("POST")){
			$form->bindRequest($request);
			if($form->isValid()){
				$socialMediaService = $this->get('buggl_main.socialMedia');
				$socialMediaService->saveSocialMediaSettings($form->getData(),$localAuthor);
				$this->get('session')->setFlash('success','Social media settings updated.');
			}
		}
		$newEGuideRequestCount = $this->getDoctrine()
			                     ->getEntityManager()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countRequestByStatus($localAuthor,array('0'=>'0'));

		
		$data = array(
			'form' => $form->createView(),
			'socialMedia' => $socialMedia,
			'newRequestCount' => $newEGuideRequestCount,
		);
		
		return $this->render('BugglMainBundle:LocalAuthor\SocialMedia:socialConnect.html.twig',$data);
	}

	public function connectWithFacebookUrlAction(Request $request)
	{
		$facebookService = $this->get('buggl_main.facebook_service');
		$facebookRegistrationUrl = $facebookService->getFBConnectUrl($this->generateUrl('connect_with_facebook_redirect_url'));

		return new RedirectResponse($facebookRegistrationUrl);
	}

	public function connectWithFacebookRedirectAction( Request $request )
	{
		$error = $request->query->get('error','');
		if($error != ''){
			$session = $this->getRequest()->getSession();
			$session->setFlash('notice','Facebook account not connected.');
			return new RedirectResponse($this->generateUrl('local_author_social_media'));
		}

		$service = $this->get('buggl_main.facebook_service');
		$code = $request->query->get('code','');
		$accessToken = $service->getFacebookAccessToken($code, $this->generateUrl('connect_with_facebook_redirect_url'));

		if(is_null($accessToken)){
			throw $this->createNotFoundException('An unknown error has occured.');
		}

		return new RedirectResponse($this->generateUrl('connect_with_facebook',array('access_token' => $accessToken)));
	}

	public function connectWithFacebookAction(Request $request)
	{
		$accessToken = $request->query->get('access_token','');
		$session = $this->getRequest()->getSession();
		if (!empty($accessToken)) {
			$localAuthor = $this->get('security.context')->getToken()->getUser();
			$facebookService = $this->get('buggl_main.facebook_service');
			$fbInfo = $facebookService->getFbInfo($accessToken);
			$fbInfo['fbAccessToken'] = $accessToken;
			$fbInfo['fbFriendsCount'] = $facebookService->countFriends($accessToken);

			if($facebookService->checkIfFBAccountInUse($fbInfo['fbId'])){
				$session->setFlash('error','This facebook account is already connected with another Buggl account.');
			}
			else{
				$socialMediaService = $this->get('buggl_main.socialMedia');
				$socialMedia = $socialMediaService->saveFacebookInfo($fbInfo,$localAuthor);
				
				$streetCreditService = $this->get('buggl_main.street_credit');
				$streetCreditService->updateConnectStatus($localAuthor);
				
				$session->setFlash('success','Your facebook account has been successfully connected.');
			}
		}
		else{
			$session->setFlash('error','An error occured.');
		}

		return new RedirectResponse($this->generateUrl('local_author_social_media'));
	}

	public function connectWithTwitterUrlAction(Request $request)
	{
		$oauthService = $this->get('buggl_main.twitter_service');
		$redirectUri = $this->generateUrl('connect_with_twitter_redirect_url',array(),true);
		$retArray = $oauthService->getRequestToken($redirectUri, false, true);
		if(!empty($retArray)){
			list($info, $headers, $body, $bodyParsed) = $retArray;
			if ($info['http_code'] == 200 && !empty($body)) {
		    	$session = $this->getRequest()->getSession();
				$session->set('twitter_token_secret', $bodyParsed['oauth_token_secret']);
				return new RedirectResponse($oauthService->getAuthorizationUrl($bodyParsed['oauth_token']));
		  	}
		}
		else{
			$this->get('session')->setFlash('error','An error occured.');
		}

		return new RedirectResponse($this->generateUrl('local_author_social_media'));
	}

	public function connectWithTwitterRedirectAction( Request $request )
	{
		$session = $this->getRequest()->getSession();
		$twitterService = $this->get('buggl_main.twitter_service');

		$oathToken = $request->get('oauth_token');
		$oathVerifier = $request->get('oauth_verifier');
	    $tokenSecret = $session->get('twitter_token_secret');

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

		return new RedirectResponse($this->generateUrl('connect_with_twitter'));
	}

	public function connectWithTwitterAction(Request $request)
	{
		$session = $this->getRequest()->getSession();
		$accessToken = $session->get('twitter_oauth_token');
		$tokenSecret = $session->get('twitter_oauth_token_secret');
		$localAuthor = null;
		$twitterInfo = array();

		if(is_null($accessToken)){
			$session = $this->getRequest()->getSession();
			$session->setFlash('notice','Twitter account not connected.');
			return new RedirectResponse($this->generateUrl('local_author_social_media'));
		}

		if ($accessToken != false) {
			$twitterService = $this->get('buggl_main.twitter_service');
			$twitterInfo = $twitterService->getTwitterInfo($accessToken,$tokenSecret);
			$twitterInfo['twitterAccessToken'] = $accessToken;
			$twitterInfo['twitterTokenSecret'] = $tokenSecret;
			$twitterInfo['twitterFollowersCount'] = $twitterService->countTwitterFollowers($accessToken,$tokenSecret,$twitterInfo['twitterId']);
			
			if($twitterService->checkIfTwitterAccountInUse($twitterInfo['twitterId'])){
				$this->get('session')->setFlash('error','This twitter account is already connected with another Buggl account.');
		 	}
			else{
			 	$localAuthor = $this->get('security.context')->getToken()->getUser();
				$socialMediaService = $this->get('buggl_main.socialMedia');
				$socialMedia = $socialMediaService->saveTwitterInfo($twitterInfo,$localAuthor);
				
				$streetCreditService = $this->get('buggl_main.street_credit');
				$streetCreditService->updateConnectStatus($localAuthor);
				
				$this->get('session')->setFlash('success','Your twitter account has been successfully connected.');
			}
		}
		else{
			$this->get('session')->setFlash('error','An error occured.');
		}

		return new RedirectResponse($this->generateUrl('local_author_social_media'));
	}

	public function connectWithGooglePlusUrlAction(Request $request)
	{
		$authService = $this->get('buggl_main.oauth2_service');
		$redirectUri = $this->generateUrl('connect_with_google_plus_redirect_url',array(),true);
		$authUrl = $authService->getAuthenticationUrl($redirectUri, 'https://www.googleapis.com/auth/plus.login', 'offline');
		return new RedirectResponse($authUrl);
	}

	public function connectWithGooglePlusRedirectAction( Request $request )
	{
		$code = $request->get('code');
		if(is_null($code)){
			$session = $this->getRequest()->getSession();
			$session->setFlash('notice','Google Plus account not connected.');
			return new RedirectResponse($this->generateUrl('local_author_social_media'));
		}

		$redirectUri = $this->generateUrl('connect_with_google_plus_redirect_url',array(),true);

		$authService = $this->get('buggl_main.oauth2_service');
		$tokens = $authService->getAccessToken($code, 'https://www.googleapis.com/auth/plus.login', $redirectUri, true);
		if(!empty($tokens)){
			$session = $this->getRequest()->getSession();
			$session->set('google_plus_access_token', $tokens['accessToken']);
			if(isset($tokens['refreshToken']))
				$session->set('google_plus_refresh_token', $tokens['refreshToken']);
		}

		return new RedirectResponse($this->generateUrl('connect_with_google_plus'));
	}

	public function connectWithGooglePlusAction(Request $request)
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

		 	if($googlePlusService->checkIfGooglePlusAccountInUse($googleInfo['googlePlusId'])){
				$this->get('session')->setFlash('error','This Google+ account is already connected with another Buggl account.');
			}
			else{
			 	$localAuthor = $this->get('security.context')->getToken()->getUser();
				$socialMediaService = $this->get('buggl_main.socialMedia');
				$socialMedia = $socialMediaService->saveGooglePlusInfo($googleInfo,$localAuthor);
				
				$streetCreditService = $this->get('buggl_main.street_credit');
				$streetCreditService->updateConnectStatus($localAuthor);
				
				$this->get('session')->setFlash('success','Your Google+ account has been successfully connected.');
			}
		}
		else{
			$this->get('session')->setFlash('error','An error occured.');
		}

		return new RedirectResponse($this->generateUrl('local_author_social_media'));
	}

	public function refreshFacebookAccessTokenAction(Request $request)
	{
		$code = $request->query->get('code','');
		$route = $request->get('route');
		$redirectUri = $this->generateUrl($route);
		$facebookService = $this->get('buggl_main.facebook_service');
		if(!empty($code)){
			$accessToken = $facebookService->getFacebookAccessToken($code, $this->generateUrl('refresh_facebook_token',array('route'=>$route)));
			if(is_null($accessToken)){
				throw $this->createNotFoundException('An unknown error has occured.');
			}
			// update db
			$socialMediaService = $this->get('buggl_main.socialMedia');
			$localAuthor = $this->get('security.context')->getToken()->getUser();
			$socialMediaService->saveFacebookInfo(array('fbAccessToken'=>$accessToken),$localAuthor);
			// redirect to $redirectUri
			return new RedirectResponse($redirectUri);
		}
		$facebookRegistrationUrl = $facebookService->getFBConnectUrl($this->generateUrl('refresh_facebook_token',array('route'=>$route)));
		return new RedirectResponse($facebookRegistrationUrl);
	}

	public function refreshGooglePlusAccessTokenAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$socialMedia = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor);

		$authService = $this->get('buggl_main.oauth2_service');
		$accessToken = $authService->refreshAccessToken($socialMedia->getGooglePlusRefreshToken());
		if(is_null($accessToken)){
			throw $this->createNotFoundException('An unknown error has occured.');
		}

		$googlePlusInfo = array(
			'googlePlusAccessToken' => $accessToken
		);

		$route = $request->get('route');
		$redirectUri = $this->generateUrl($route);

		return new RedirectResponse($redirectUri);
	}

	public function disconnectFacebookAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$fbInfo = array(
			'fbId' => null,
			'fbAccessToken' => null,
			'fbUrl' => null,
			'fbFriendsCount' => null
		);

		$socialMediaService = $this->get('buggl_main.socialMedia');
		$socialMedia = $socialMediaService->saveFacebookInfo($fbInfo,$localAuthor);
		$this->get('session')->setFlash('success','Your Facebook account has been successfully disconnected.');
		return new RedirectResponse($this->generateUrl('local_author_social_media'));
	}

	public function disconnectTwitterAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$twitterInfo = array(
			'twitterId' => null,
			'twitterAccessToken' => null,
			'twitterUrl' => null
		);

		$socialMediaService = $this->get('buggl_main.socialMedia');
		$socialMedia = $socialMediaService->saveTwitterInfo($twitterInfo,$localAuthor);
		$this->get('session')->setFlash('success','Your Twitter account has been successfully disconnected.');
		return new RedirectResponse($this->generateUrl('local_author_social_media'));
	}

	public function disconnectGooglePlusAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$socialMedia = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor,true);
		if(is_null($socialMedia) || is_null($socialMedia->getGooglePlusRefreshToken())){
			$this->get('session')->setFlash('error','There was an error while processing your request.');
			return new RedirectResponse($this->generateUrl('local_author_social_media'));
		}

		$authService = $this->get('buggl_main.oauth2_service');
		$status = $authService->revokeAccess($socialMedia->getGooglePlusRefreshToken());

		if(is_null($socialMedia) || is_null($socialMedia->getGooglePlusRefreshToken())){
			$this->get('session')->setFlash('error','There was an error while processing your request.');
			return new RedirectResponse($this->generateUrl('local_author_social_media'));
		}

		$googlePlusInfo = array(
			'googlePlusId' => null,
			'googlePlusAccessToken' => null,
			//'googlePlusRefreshToken' => null,
			'googlePlusUrl' => null
		);

		$socialMediaService = $this->get('buggl_main.socialMedia');
		$socialMedia = $socialMediaService->saveGooglePlusInfo($googlePlusInfo,$localAuthor);

		$this->get('session')->setFlash('success','Your Google+ account has been successfully disconnected.');
		return new RedirectResponse($this->generateUrl('local_author_social_media'));
	}

	public function updateFacebookFriendsCount(Request $request)
	{
		///*
			$facebookService = $this->get('buggl_main.facebook_service');
			$localAuthor = $this->get('security.context')->getToken()->getUser();
			$accessToken = $facebookService->getFacebookAccessTokenFromDB($localAuthor);
			if(!is_null($accessToken)){
				$count = $facebookService->countFriends($accessToken);
				if(is_null($count)){
					// refreshToken
					return new RedirectResponse($this->generateUrl('refresh_facebook_token',array('route'=>'local_author_social_media')));
				}
				else{
					$socialMediaService = $this->get('buggl_main.socialMedia');
					$socialMediaService->saveFacebookInfo(array('fbFriendsCount'=>$count),$localAuthor);
				}
			}
			else{
				// ask to connect
			}
		//*/
	}
	
	public function testFbPostAction(Request $request)
	{
		/*
		$eguide = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide')->findOneById(5);
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$socialMedia = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor);
		$facebookService = $this->get('buggl_main.facebook_service');
		
		$photo = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuidePhoto')->findOneActiveByEguideAndType($eguide,1);
		$postData = array(
			"message"  => "I've published a travel guide called \"".$eguide->getPlainTitle()."\" on Buggl",
			"picture" => $photo->getPhoto(),
			"link" => $this->generateUrl('buggl_eguide_overview',array('slug' => $eguide->getSlug()),true)
		);
		$facebookService->postStatus($postData,$socialMedia->getFbAccessToken());
		exit;
		*/
		$postData = array(
			"message"  => $request->get("message","test")
		);
		
		$accessToken = $request->get('token','');
		if($accessToken != ''){
			$facebookService = $this->get('buggl_main.facebook_service');
			$facebookService->postStatus($postData,$accessToken);
		}
		else{
			echo "Invalid AccessToken";
		}
		
		exit;
	}
	
	public function testTweetAction(Request $request)
	{
		$eguide = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide')->findOneById(5);
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$socialMedia = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor);
		
		$photo = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuidePhoto')->findOneActiveByEguideAndType($eguide,1);
		$photoUrl = $photo->getPhoto();
		
		$tweet = array(
			'media[]' => @file_get_contents($photoUrl),//"{@".$photoUrl."}",
			'status' => "I've published a travel guide called \"".$eguide->getPlainTitle()."\" on Buggl"
		);
		$this->get('buggl_main.twitter_service')->tweet($socialMedia->getTwitterAccessToken(),$socialMedia->getTwitterTokenSecret(),$tweet);
		
		exit;
	}
}