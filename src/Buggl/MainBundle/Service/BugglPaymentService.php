<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\PaypalPurchaseInfo;
use Buggl\MainBundle\Entity\PaypalTransactionInfo;
use Buggl\MainBundle\Entity\PaypalFailedTransactionInfo;

class BugglPaymentService
{
	private $bugglPercentage;
	private $bugglPaypalEmail;

	private $paypalService;
	private $entityManager;
	private $constants;
	private $environmentVars;
	private $router;

	public function __construct($paypalService,$entityManager,$constants,$environmentVars,$router,$purchaseMailerService)
	{
		$this->paypalService = $paypalService;
		$this->entityManager = $entityManager;
		$this->constants = $constants;
		$this->environmentVars = $environmentVars;
		$this->router = $router;
		$this->purchaseMailerService = $purchaseMailerService;
		$this->bugglPercentage = $this->constants->get('buggl_payment_share');

		$this->bugglPaypalEmail = $this->environmentVars->getVariable('paypal_account_email');
	}

	public function getPaypalPaymentLink($eguide, $buyer, $cancelUrl)
	{
		$trackingId = $eguide->getId().'-'.$buyer->getId().'-'.$eguide->getLocalAuthor()->getId().'-'.strtotime(date('Y-m-d H:i:s'));
		
		$credit = $this->getCreditValue($eguide->getLocalAuthor());
		
		$this->bugglPercentage = $this->bugglPercentage - ($credit / 100);
		$bugglShare = number_format($eguide->getPrice()*$this->bugglPercentage,2);
		$authorShare = number_format($eguide->getPrice() - ($eguide->getPrice()*$this->bugglPercentage),2);
		$sellerPaypalInfo = $this->entityManager->getRepository('BugglMainBundle:PaypalInfo')->findByLocalAuthor($eguide->getLocalAuthor());

		if(is_null($sellerPaypalInfo) || $this->bugglPaypalEmail == null){
			return null;
		}

		$recievers = array(
			// seller's info
			0 => array(
				'amount' => $authorShare,
				'email' => $sellerPaypalInfo->getEmail(),
				'primary' => false
			),
			// buggl's info
			1 => array(
				'amount' => number_format($eguide->getPrice(),2),
				'email' => $this->bugglPaypalEmail,
				'primary' => true
			)
		);
		
		//var_dump($recievers); exit;

		$transactionData = array(
			'trackingId' => $trackingId,
			'cancelUrl' => $cancelUrl,
			'returnUrl' => $this->router->generate('buggl_buy_guide_return_url',array('eguideId' => $eguide->getId(),'buyerId' => $buyer->getId(), 'trackingId' => $trackingId),true),
			'ipnNotificationUrl' => $this->router->generate('buggl_paypal_ipn_listener',array('eguideId' => $eguide->getId(),'buyerId' => $buyer->getId()),true),
			'receivers' => $recievers,
			// add other info, currently hardcoded in PaypalService::createChainedPayment()
		);

		$response = $this->paypalService->createChainedPayment($transactionData);
		if(!isset($response->payKey)){
			return null;
		}

		$transactionInfo = new PaypalTransactionInfo();
		$transactionInfo->setPaypalTrackingId($trackingId);
		$transactionInfo->setPaypalPayKey($response->payKey);
		$transactionInfo->setAmount(number_format($eguide->getPrice(),2));
		$transactionInfo->setBugglFee($bugglShare);
		$transactionInfo->setPrimaryReceiverEmail($sellerPaypalInfo->getEmail());
		$transactionInfo->setDateOftransaction(new \DateTime(date('y-m-d H:i:s')));
		$transactionInfo->setPaypalTransactionDetails(json_encode($response));

		$this->entityManager->persist($transactionInfo);
		$this->entityManager->flush();

		$this->paypalService->setPaymentOptions($response->payKey,$this->router->generate('buggl_homepage',array(),true).'/bundles/bugglmain/images/buggl-paypal-header.jpg');
		$paypalLink = $this->paypalService->getRedirectToPaypalLink($response->payKey);

		return $paypalLink;
	}

	public function processPayment($trackingId, $eguide, $buyer)
	{
		$response =  $this->paypalService->getPaymentDetailsByTrackingId($trackingId);
		$bugglShare = $response->paymentInfoList->paymentInfo[1]->receiver->amount - $response->paymentInfoList->paymentInfo[0]->receiver->amount;

		if ($response->status == 'COMPLETED') {
			$purchaseInfo = new PaypalPurchaseInfo();
			$purchaseInfo->setEguide($eguide);
			$purchaseInfo->setBuyer($buyer);
			$purchaseInfo->setSeller($eguide->getLocalAuthor());
			$purchaseInfo->setAmount(number_format($eguide->getPrice(),2));
			$purchaseInfo->setBugglFee(number_format($bugglShare,2));
			$purchaseInfo->setNetAmount(number_format(($eguide->getPrice() - $bugglShare),2));
			$purchaseInfo->setDateOfTransaction(new \DateTime(date('Y-m-d H:i:s')));
			$purchaseInfo->setPaypalPayKey($response->payKey);
			$purchaseInfo->setPaypalTrackingId($trackingId);
			$purchaseInfo->setPaypalCorrelationId($response->responseEnvelope->correlationId);
			$purchaseInfo->setPaypalPaymentStatus($this->constants->get('PAYPAL_PAYMENT_'.$response->status));
			$purchaseInfo->setPaypalTimestamp(new \DateTime($response->responseEnvelope->timestamp));
			$purchaseInfo->setPaypalPaymentDetails(json_encode($response));

			$this->entityManager->persist($purchaseInfo);
			$this->entityManager->flush();

			$this->purchaseMailerService->sendMail($purchaseInfo);
			
			$this->removeFromWishlist($eguide, $buyer);
		}
		// streamline this to actual failed transaction not including processing statuses
		else {
			// save to failed transactions for record purposes
			$this->saveFailedTransaction($response,$eguide,$buyer,$trackingId);
			return null;
		}

		return $purchaseInfo;
	}

	public function saveFreePurchase($eguide, $buyer)
	{
		$purchaseInfo = new PaypalPurchaseInfo();
		$purchaseInfo->setEguide($eguide);
		$purchaseInfo->setBuyer($buyer);
		$purchaseInfo->setSeller($eguide->getLocalAuthor());
		$purchaseInfo->setAmount('0.00');
		$purchaseInfo->setBugglFee('0.00');
		$purchaseInfo->setNetAmount('0.00');
		$purchaseInfo->setDateOfTransaction(new \DateTime(date('Y-m-d H:i:s')));
		$purchaseInfo->setPaypalPayKey(null);
		$purchaseInfo->setPaypalTrackingId(null);
		$purchaseInfo->setPaypalCorrelationId(null);
		$purchaseInfo->setPaypalPaymentStatus(null);
		$purchaseInfo->setPaypalTimestamp(null);
		$purchaseInfo->setPaypalPaymentDetails(null);

		$this->entityManager->persist($purchaseInfo);
		$this->entityManager->flush();

		$this->purchaseMailerService->sendMail($purchaseInfo);
		
		$this->removeFromWishlist($eguide, $buyer);
	}

	public function saveRequestPurchase($eguide, $buyer)
	{
		$purchaseInfo = new PaypalPurchaseInfo();
		$purchaseInfo->setEguide($eguide);
		$purchaseInfo->setBuyer($buyer);
		$purchaseInfo->setSeller($eguide->getLocalAuthor());
		$purchaseInfo->setAmount(number_format($eguide->getPrice(),2));
		$purchaseInfo->setBugglFee('0.00');
		$purchaseInfo->setNetAmount(number_format(($eguide->getPrice()),2));
		$purchaseInfo->setDateOfTransaction(new \DateTime(date('Y-m-d H:i:s')));
		$purchaseInfo->setPaypalPayKey(null);
		$purchaseInfo->setPaypalTrackingId(null);
		$purchaseInfo->setPaypalCorrelationId(null);
		$purchaseInfo->setPaypalPaymentStatus(null);
		$purchaseInfo->setPaypalTimestamp(null);
		$purchaseInfo->setPaypalPaymentDetails(null);

		$this->entityManager->persist($purchaseInfo);
		$this->entityManager->flush();

		$this->purchaseMailerService->sendItineraryMail($purchaseInfo);
		$this->purchaseMailerService->sendItineraryMailToAdmin($purchaseInfo);
		$this->removeFromWishlist($eguide, $buyer);
	}
	/*
	NOTES:

	SAMPLE IPN MESSAGE contents (json)
	We note here that there is no info about the transaction made by a buyer to our primary receiver which is the author,
	the info given here are relevant only to the payment automatically made by the author to buggl through
	the chained payments implementation, with this in mind do we consider the status of this info to mirror that of between the buyer and the author?

	UPDATE:
	this IPN message seems to be just a by-product of using chained payments which may explain why it only has info about the secondary receiver (buggl)
	because the sandbox account used for buggl has set an IPN url in its profile

	{
		"transaction_subject":"",
		"payment_date":"22:22:45 Jul 03, 2013 PDT",
		"txn_type":"web_accept",
		"last_name":"Bacarisas",
		"residence_country":"US",
		"item_name":"",
		"payment_gross":"1.50",
		"mc_currency":"USD",
		"business":"sampler08-facilitator@gmail.com",
		"payment_type":"instant",
		"protection_eligibility":"Ineligible",
		"verify_sign":"An5ns1Kso7MWUdW4ErQKJJJ4qi4-AuThwzlUIuImPL9spz.O2HwFRjzA",
		"payer_status":"verified",
		"test_ipn":"1",
		"tax":"0.00",
		"payer_email":"seller@buggl.com",
		"txn_id":"80265036SW441703S",
		"quantity":"0",
		"receiver_email":"sampler08-facilitator@gmail.com",
		"first_name":"Noel",
		"payer_id":"W9FPKJG9TW57Q",
		"receiver_id":"N5L8JXCDDXS2E",
		"item_number":"",
		"payment_status":"Completed",
		"payment_fee":"0.34",
		"mc_fee":"0.34",
		"mc_gross":"1.50",
		"custom":"",
		"charset":"windows-1252",
		"notify_version":"3.7",
		"ipn_track_id":"4670bf4bd6407"
	}

	ACTUAL IPN message sent through api implementation, note that the IPN message above is sent due to the settings in paypal profile
	regardless of whether we set an INP url in our api request or not

	{
		"transaction":["NONE","Completed"],
		"log_default_shipping_address_in_transaction":"false",
		"action_type":"CREATE",
		"ipn_notification_url":"http:\/\/peepal-beta.buggl.com\/app_dev.php\/paypal-ipn-listener2\/24\/9",
		"charset":"windows-1252",
		"transaction_type":"Adaptive Payment PAY",
		"notify_version":"UNVERSIONED",
		"cancel_url":"http:\/\/beta.buggl.com\/app_dev.php\/africa-wildlife-24\/overview",
		"verify_sign":"AmFTdmlXAlQ89J7AN33ubwv-ZqE6AGJpsc7zN4ncFevbe6CdhI1rHqvy",
		"sender_email":"buyer@buggl.com",
		"fees_payer":"EACHRECEIVER",
		"return_url":"http:\/\/beta.buggl.com\/app_dev.php\/process-payment-result\/24\/9\/24-9-8-1373264317",
		"reverse_all_parallel_payments_on_error":"false",
		"tracking_id":"24-9-8-1373264317",
		"pay_key":"AP-24A76600RB075994W",
		"status":"COMPLETED",
		"test_ipn":"1",
		"payment_request_date":"Sun Jul 07 23:18:38 PDT 2013"
	}
	*/
	public function verifyIPNTransaction($ipnMessage)
	{
		$response = $this->paypalService->verifyIPNMessage($ipnMessage);

		if($response == 'VERIFIED'){
			/*
			 we are supposed to do further checks (recipient, amount, description, etc...) here
			 but the info returned through IPN seems to be incomplete so such checks may not be possible
			 we'll try to check if this is same case in prod
			*/
			return true;
		}

		return false;
	}
	
	public function getCreditValue($localAuthor)
	{
		$streetCredit = $this->entityManager->getRepository('BugglMainBundle:StreetCredit')->findOneByLocalAuthor($localAuthor);
		$credit = 0;
		if(!is_null($streetCredit)){
			$credits = array(
				'getGuideStatus' => 2,
				'getVouchStatus' => 2,
				'getProfileStatus' => 2,
				'getInviteAuthorStatus' => 2,
				'getShareStatus' => 1,
				'getConnectStatus' => 1
			);
		
			foreach($credits as $key => $val){
				$tempVal = $streetCredit->$key();
				if(!is_null($tempVal) && $tempVal == 1){
					$credit += $val;
				}
			}
		}
		
		return $credit;
	}

	private function saveFailedTransaction($paymentDetails,$eguide,$buyer,$trackingId)
	{
		$purchaseInfo = new PaypalFailedTransactionInfo();
		$purchaseInfo->setEguide($eguide);
		$purchaseInfo->setBuyer($buyer);
		$purchaseInfo->setPaypalTrackingId($trackingId);
		$purchaseInfo->setDateOfTransaction(new \DateTime(date('Y-m-d H:i:s')));
		$purchaseInfo->setPaypalPaymentDetails(json_encode($response));

		$this->entityManager->persist($purchaseInfo);
		$this->entityManager->flush();

		return $purchaseInfo;
	}
	
	private function removeFromWishlist($eguide, $buyer)
	{
		$wish = $this->entityManager->getRepository('BugglMainBundle:Wishlist')->findOneBy(array('e_guide' => $eguide,'localAuthor' => $buyer));
		
		if(!is_null($wish)){
			$this->entityManager->remove($wish);
			$this->entityManager->flush();
		}
	}
}