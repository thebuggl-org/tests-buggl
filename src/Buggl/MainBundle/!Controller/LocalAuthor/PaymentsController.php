<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Buggl\MainBundle\Form\Type\PaypalInfoType;

class PaymentsController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{

	public function indexAction(Request $request)
	{
		$currentPage = $request->get('page',1);
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));
		return $this->render('BugglMainBundle:LocalAuthor\Payments:payments.html.twig',array('currentPage'=>$currentPage,'newRequestCount' => $newEGuideRequestCount,));
	}

	public function purchaseHistoryListAction(Request $request)
	{
		$constants = $this->get('buggl_main.constants');

		$limit = $constants->get('payments_pagination');
		$currentPage = $request->get('currentPage',1);

		$offset =  $currentPage > 0 ? ($currentPage-1) * $limit : 0;
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		// $payments = $this->getDoctrine()->getRepository('BugglMainBundle:PurchaseInfo')->findBySeller($localAuthor,$offset,$limit);
		// NOTE: for stripe
		// $payments = $this->getDoctrine()->getRepository('BugglMainBundle:PurchaseInfo')->findByUser($localAuthor,$offset,$limit);
		$payments = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findByUser($localAuthor,$offset,$limit);
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));
                                          
		$softPageLimit = 8;
		$hardPageLimit = 12;

		$data = array(
			'payments' => $payments,
			'itemLimit' => $limit,
			'currentPage' => $currentPage,
			'softPageLimit' => $softPageLimit,
			'newRequestCount' => $newEGuideRequestCount,
			'hardPageLimit' => $hardPageLimit,
			'dataUrl' => $this->generateUrl('local_author_purchase_history_pagination')
		);

		return $this->render('BugglMainBundle:LocalAuthor\Payments:purchaseHistoryList.html.twig',$data);
	}

	public function purchasedGuidesAction(Request $request)
	{
		$currentPage = $request->get('page',1);
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));
		$counts = array(
			'published' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('published_guide')),
			'featured' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countFeaturedInProfile($localAuthor),
			'unpublished' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('unpublished_guide')),
			'archived' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('archived_guide')),
			'draft' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('draft_guide')),
			'denied' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('denied'))
		);
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));

		$data = array(
			'currentPage' => $currentPage,
			'newRequestCount' => $newEGuideRequestCount,
			'counts' => $counts
		);
		return $this->render('BugglMainBundle:LocalAuthor\Payments:purchasedGuides.html.twig',$data);
	}

	public function purchasedGuidesListAction(Request $request)
	{
		$constants = $this->get('buggl_main.constants');

		$limit = $constants->get('payments_pagination');
		$currentPage = $request->get('currentPage',1);

		$offset =  $currentPage > 0 ? ($currentPage-1) * $limit : 0;
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));
		// NOTE: for stripe
		// $payments = $this->getDoctrine()->getRepository('BugglMainBundle:PurchaseInfo')->findByBuyer($localAuthor,$offset,$limit);
		$payments = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findByBuyer($localAuthor,$offset,$limit);
		$softPageLimit = 8;
		$hardPageLimit = 12;

		$data = array(
			'payments' => $payments,
			'itemLimit' => $limit,
			'currentPage' => $currentPage,
			'softPageLimit' => $softPageLimit,
			'hardPageLimit' => $hardPageLimit,
			'dataUrl' => $this->generateUrl('local_author_purchased_guides_pagination'),
			'default' => 'No purchased guides yet.'
		);

		return $this->render('BugglMainBundle:LocalAuthor\Payments:purchasedGuidesList-v2.html.twig',$data);
	}

	/**
	 *
	 * @param  Symfony\Component\HttpFoundation\Request $request
	 *
	 * @author Vincent Farly G. Taboada  <farly.taboadaa@goabroad.com> May 30, 2013
	 */
	public function soldGuidesAction(Request $request)
	{
		$currentPage = $request->get('page',1);
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$counts = array(
			'published' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('published_guide')),
			'featured' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countFeaturedInProfile($localAuthor),
			'unpublished' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('unpublished_guide')),
			'archived' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('archived_guide')),
			'draft' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('draft_guide')),
			'denied' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countByLocalAuthorAndStatus($localAuthor, $this->get('buggl_main.constants')->get('denied'))
		);
		$data = array(
			'currentPage' => $currentPage,
			'counts' => $counts
		);
		return $this->render('BugglMainBundle:LocalAuthor\Payments:soldGuides.html.twig',$data);
	}

	/**
	 *
	 * @param  Symfony\Component\HttpFoundation\Request $request [description]
	 *
	 * @author Vincent Farly G. Taboada  <farly.taboadaa@goabroad.com> May 30, 2013
	 */
	public function soldGuidesListAction(Request $request)
	{
		$constants = $this->get('buggl_main.constants');

		$limit = $constants->get('payments_pagination');
		$currentPage = $request->get('currentPage',1);

		$offset =  $currentPage > 0 ? ($currentPage-1) * $limit : 0;
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		// NOTE: for stripe
		// $payments = $this->getDoctrine()->getRepository('BugglMainBundle:PurchaseInfo')->findBySeller($localAuthor,$offset,$limit);
		$payments = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findBySeller($localAuthor,$offset,$limit);

		$softPageLimit = 8;
		$hardPageLimit = 12;

		$data = array(
			'payments' => $payments,
			'itemLimit' => $limit,
			'currentPage' => $currentPage,
			'softPageLimit' => $softPageLimit,
			'hardPageLimit' => $hardPageLimit,
			'dataUrl' => $this->generateUrl('local_author_sold_guides_pagination'),
			'default' => 'No sold guides yet'
		);

		return $this->render('BugglMainBundle:LocalAuthor\Payments:purchasedGuidesList-v2.html.twig',$data);
	}
	
	public function paypalSettingsAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$paypalInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalInfo')->findByLocalAuthor($localAuthor,true);
		$hasCurrentAccount = !is_null($paypalInfo) && !is_null($paypalInfo->getId());
		$hasFormErrors = false;
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));
		$paypalEmail = '';
		if($hasCurrentAccount){
			$paypalEmail = $paypalInfo->getEmail();
		}
		
		$form = $this->createForm(new PaypalInfoType(), $paypalInfo);
			
		if($request->isMethod('POST')){
			$form->bind($request);
			if($form->isValid()){
				// check via paypal GetVerifiedStatus Request
				$paypalInfo2 = $form->getData();
				$paypalService = $this->get('buggl_main.paypal_service');
				$verified = $paypalService->verifyAccountStatus($paypalInfo2->getEmail(),$paypalInfo2->getFirstName(),$paypalInfo2->getLastName());
				if($verified){
					if(!$paypalService->verifyEmailConfirmation($paypalInfo2->getEmail(),$this->get('buggl_main.environment_variables')->getVariable('paypal_account_email'))){
						$this->get('session')->getFlashBag()->add('error', "Your email address is not yet confirmed by paypal.");
						$hasFormErrors = true;
						//return new RedirectResponse($this->generateUrl('local_author_paypal_settings'));
					}
					// possibly check currency
					else{
						$paypalInfo->setLocalAuthor($localAuthor);
						$this->getDoctrine()->getEntityManager()->persist($paypalInfo2);
						$this->getDoctrine()->getEntityManager()->flush();
						$this->get('session')->getFlashBag()->add('permanent-success', "Your paypal account information has been saved! Travelers can now buy your guides.");
					
						return new RedirectResponse($this->generateUrl('local_author_paypal_settings'));
					}
				}
				else{
					$this->get('session')->getFlashBag()->add('error', "We did not find an existing or verified paypal account associated with the info you have provided.");
					$hasFormErrors = true;
				}			
			}
			else{
				$hasFormErrors = true;
			}
		}
		
		$data = array(
			'activeTab' => 'settings',
			'form' => $form->createView(),
			'paypalInfo' => $paypalInfo,
			'hasFormErrors' => $hasFormErrors,
			'newRequestCount' => $newEGuideRequestCount,
			'hasCurrentAccount' => $hasCurrentAccount,
			'paypalEmail' => $paypalEmail
		);

		return $this->render('BugglMainBundle:LocalAuthor\Payments:paypalSettings.html.twig', $data);
	}
	
	public function disconnectPaypalAccountAction(Request $request)
	{
		$id = $request->get('id');
		
		$paypalInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalInfo')->findOneById($id);
		
		if(!is_null($paypalInfo)){
			$this->getDoctrine()->getEntityManager()->remove($paypalInfo);
			$this->getDoctrine()->getEntityManager()->flush();
			$this->get('session')->getFlashBag()->add('success', "Paypal account disconnected.");
		}
		else{
			$this->get('session')->getFlashBag()->add('error', "Your paypal account is not found.");
		}
		
		return new RedirectResponse($this->generateUrl('local_author_paypal_settings')); 
	}

	public function settingsAction()
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();

		if(!$localAuthor->getIsLocalAuthor()){
			$this->get('session')->getFlashBag()->add('notice', "You were redirected here because you do not have permission to view the requested page.");
			return new RedirectResponse($this->generateUrl('local_author_payments_purchased'));
		}

		$stripeInfo = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:StripeInfo')->findbyLocalAuthor($localAuthor);

		$data = array(
			'activeTab' => 'settings',
			'stripeInfo' => $stripeInfo
		);

		return $this->render('BugglMainBundle:LocalAuthor\Payments:settings.html.twig', $data);
	}

	public function paymentWithStripeAction(Request $request)
	{
		if($request->isMethod('POST')){
			$prevPage = $request->headers->get('referer');
			$data = $request->request->all();

			$eguide = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuide')->findOneById($data['eguideId']);
			$stripeInfo = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:StripeInfo')->findbyLocalAuthor($eguide->getLocalAuthor());

			$environmentVars = $this->get('buggl_main.environment_variables');
			$bugglPercentage = $environmentVars->getVariable('stripe_application_fee');
			$applicationFee = round(($data['amount'] * ($bugglPercentage / 100)),0,PHP_ROUND_HALF_UP);

			$stripeService = $this->get('buggl_main.stripe_service');
			$result = $stripeService->charge(
										$data['amount'],
										null,
										$data['stripeToken'],
										$data['description'],
										$applicationFee,
										$stripeInfo->getAccessToken()
									);
			if(isset($result->error)){
				$this->get('session')->getFlashBag()->add('error', 'Sorry there seems to be an error with the transaction. '.$result->error->message.'.');
			}
			else{
				$buyer = $this->get('security.context')->getToken()->getUser();

				$fees = array();
				foreach($result->fee_details as $fee)
					$fees[$fee->type] = $fee->amount;

				$stripeData = array(
					'amount' => $result->amount,
					'netAmount' => ($result->amount - $result->fee),
					'fees' => $fees,
					'charge_id' => $result->id
				);

				$purchaseService = $this->get('buggl_main.purchase_service');
				$purchaseInfo = $purchaseService->savePurchaseInfo($stripeData,$buyer,$eguide);

				$this->get('session')->getFlashBag()->add('success', 'Your transaction is complete.');

				$constant = $this->get('buggl_main.constants');
				$event = new \Buggl\MainBundle\Event\ActivityEvent($purchaseInfo,$buyer,$eguide->getLocalAuthor(),$constant->get('ACTIVITY_PURCHASE_GUIDE'));
				$this->get('event_dispatcher')->dispatch('buggl.activity',$event);
			}
			return new RedirectResponse($prevPage);
		}

		throw $this->createNotFoundException('Payment not allowed through non-POST request');
	}

	public function connectWithStripeUrlAction(Request $request)
	{
		$stripeService = $this->get('buggl_main.stripe_service');
		$connectUrl = $stripeService->getAuthenticationUrl();

		return new RedirectResponse($connectUrl);
	}

	public function connectWithStripeRedirectUrlAction(Request $request)
	{
		$code = $request->get('code');
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$stripeInfo = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:StripeInfo')->findbyLocalAuthor($localAuthor);

		if(!is_null($stripeInfo) && !is_null($stripeInfo->getAccessToken())){
			$this->get('session')->getFlashBag()->add('notice', 'You already have a Stripe account connected.');
			return new RedirectResponse($this->generateUrl('local_author_payments_settings'));
		}

		$stripeService = $this->get('buggl_main.stripe_service');
		$stripeInfo = $stripeService->saveStripeInfo($code,$localAuthor);

		if(is_null($stripeInfo)){
			$this->get('session')->getFlashBag()->add('error', 'There was an error connecting your account.');
		}
		else{
			$this->get('session')->getFlashBag()->add('success', 'Your account is successfully connected.');
		}

		return new RedirectResponse($this->generateUrl('local_author_payments_settings'));
	}

	public function disconnectStripeAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();

		$stripeService = $this->get('buggl_main.stripe_service');
		$stripeInfo = $stripeService->disconnectStripeAccount($localAuthor);

		if(is_null($stripeInfo)){
			$this->get('session')->getFlashBag()->add('error', 'There was an error disconnecting your account.');
		}
		else{
			$this->get('session')->getFlashBag()->add('success', 'Your account is successfully disconnected.');
			$this->get('session')->getFlashBag()->add('notice', 'Remember that other users will not be able to purchase your travel guides if you do not have an account connected.');
		}

		return new RedirectResponse($this->generateUrl('local_author_payments_settings'));
	}
	
	public function buyGuideAction(Request $request)
	{
		$eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('eguideId',''));
		$buyer = $this->get('security.context')->getToken()->getUser();
		
		//check for null
		if(is_null($eguide)){
			$this->get('session')->getFlashBag()->add('error', "The guide was not found.");
			return new RedirectResponse($this->generateUrl('buggl_homepage'));
		}
		
		$cancelUrl = $this->generateUrl('buggl_eguide_overview',array('slug' => $eguide->getSlug()),true);
		//$request->headers->get('referer');
		
		//check if already purhcased
		$purchaseInfo = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($buyer,$eguide);
		if(!is_null($purchaseInfo)){
			return new RedirectResponse($cancelUrl);
		}

		if($eguide->getPrice() == 0){
			$paymentsService = $this->get('buggl_main.buggl_payment_service');
			$paymentsService->saveFreePurchase($eguide,$buyer);
			
			$this->get('session')->set('buy-guide-status', true);
			return new RedirectResponse($this->generateUrl('buggl_eguide_secrets',array('slug'=>$eguide->getSlug())));
		}
		else{
			$paymentService = $this->get('buggl_main.buggl_payment_service');
			$paypalLink = $paymentService->getPaypalPaymentLink($eguide,$buyer,$cancelUrl);

			if(is_null($paypalLink)){
				$this->get('session')->getFlashBag()->add('error', "There seems to be a problem with ".$eguide->getLocalAuthor()->getName()."'s paypal account. Please check back later while we resolve this issue.");
				return new RedirectResponse($cancelUrl);
			}

			return new RedirectResponse($paypalLink);
		}
	}
}