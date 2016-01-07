<?php

namespace Buggl\MainBundle\Service;

class PaypalService
{
	protected $baseApiUrl;
	protected $paymentUrl;
	protected $ipnVerificationUrl;
	protected $userId;
	protected $password;
	protected $signature;
	protected $appId;
	protected $useSandbox;

	protected $environmentVars;
	protected $serviceContainer;
	protected $ipAddress;	

	public function __construct($environmentVars,$serviceContainer)
	{
		$this->environmentVars = $environmentVars;
		$this->userId = $this->environmentVars->getVariable('paypal_security_user_id');
		$this->password = $this->environmentVars->getVariable('paypal_security_paswword');
		$this->signature = $this->environmentVars->getVariable('paypal_security_signature');
		$this->appId = $this->environmentVars->getVariable('paypal_app_id');
		$useSandbox = $this->environmentVars->getVariable('use_paypal_sandbox');
		
		//NOTE: find better way to set ipaddress
		$this->ipAddress = $serviceContainer->get('request')->getClientIp();
		
		if($useSandbox){
			$this->baseApiUrl = 'https://svcs.sandbox.paypal.com';
			$this->paymentUrl = 'https://www.sandbox.paypal.com';
			$this->ipnVerificationUrl = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
		}
		else{
			// set live urls here
			$this->baseApiUrl = 'https://svcs.paypal.com';
			$this->paymentUrl = 'https://www.paypal.com';
			$this->ipnVerificationUrl = 'https://www.paypal.com/cgi-bin/webscr';
		}
	}

	public function createChainedPayment($transactionData)
	{
		$cancelUrl = $transactionData['cancelUrl'];
		$returnUrl = $transactionData['returnUrl'];
		$ipnNotificationUrl = $transactionData['ipnNotificationUrl'];
		$receivers = $transactionData['receivers'];
		$trackingId = $transactionData['trackingId'];
		
		$receiverInfo = array();
		foreach($receivers as $receiver){
			$receiverInfo[] = '{"amount":"'.$receiver['amount'].'","email":"'.$receiver['email'].'","primary":"'.$receiver['primary'].'"}';
		}
		
		$receiver = '{"receiver":['.implode(',',$receiverInfo).']}';
		
		$requestData = array();
		$requestData[] = '"actionType":"CREATE"';
		$requestData[] = '"ipnNotificationUrl":"'.$ipnNotificationUrl.'"';
		$requestData[] = '"trackingId":"'.$trackingId.'"';
		$requestData[] = '"currencyCode":"USD"';
		$requestData[] = '"receiverList":'.$receiver;
		$requestData[] = '"returnUrl":"'.$returnUrl.'"';
		$requestData[] = '"cancelUrl":"'.$cancelUrl.'"';
		$requestData[] = '"requestEnvelope":{"errorLanguage":"en_US","detailLevel":"ReturnAll"}';
		
		$data = '{'.implode(',',$requestData).'}';
		$jsonResponse = $this->curlPost($this->baseApiUrl.'/AdaptivePayments/Pay',$data);
		//var_dump($jsonResponse); exit;
		return $jsonResponse;
	}
	
	public function setPaymentOptions($payKey,$headerImageUrl)
	{	
		$data = '{"payKey":"'.$payKey.'","displayOptions":{"headerImageUrl":"'.$headerImageUrl.'"},"requestEnvelope":{"errorLanguage":"en_US","detailLevel":"ReturnAll"}}';
		
		$jsonResponse = $this->curlPost($this->baseApiUrl.'/AdaptivePayments/SetPaymentOptions',$data);
		
		if(isset($jsonResponse->payKey)){
			return $jsonResponse->payKey;
		}
		
		return null;
	}
	
	public function getPaymentDetailsByPayKey($payKey)
	{
		$data = '{"payKey":"'.$payKey.'","requestEnvelope":{"errorLanguage":"en_US","detailLevel":"ReturnAll"}}';
		$jsonResponse = $this->curlPost($this->baseApiUrl.'/AdaptivePayments/PaymentDetails',$data);
		
		return $jsonResponse;
	}
	
	public function getPaymentDetailsByTrackingId($trackingId)
	{
		$data = '{"trackingId":"'.$trackingId.'","requestEnvelope":{"errorLanguage":"en_US","detailLevel":"ReturnAll"}}';
		$jsonResponse = $this->curlPost($this->baseApiUrl.'/AdaptivePayments/PaymentDetails',$data);
		
		return $jsonResponse;
	}
	
	public function getPaymentDetailsByTransactionId($transactionId)
	{
		$data = '{"transactionId":"'.$transactionId.'","requestEnvelope":{"errorLanguage":"en_US","detailLevel":"ReturnAll"}}';
		$jsonResponse = $this->curlPost($this->baseApiUrl.'/AdaptivePayments/PaymentDetails',$data);
		
		return $jsonResponse;
	}
	
	public function getRedirectToPaypalLink($payKey)
	{
		return $this->paymentUrl.'/cgi-bin/webscr?cmd=_ap-payment&paykey='.$payKey;
	}
	
	public function verifyAccountStatus($email, $firstName, $lastName)
	{
		$data = '{"matchCriteria":"NAME","accountIdentifier":{"emailAddress":"'.$email.'"},"firstName":"'.$firstName.'","lastName":"'.$lastName.'","requestEnvelope":{"errorLanguage":"en_US","detailLevel":"ReturnAll"}}';
		$jsonResponse = $this->curlPost($this->baseApiUrl.'/AdaptiveAccounts/GetVerifiedStatus',$data);
		//var_dump($jsonResponse); exit;
		return isset($jsonResponse->accountStatus) && $jsonResponse->accountStatus == 'VERIFIED';
	}
	
	public function verifyEmailConfirmation($email,$bugglEmail)
	{
		// mock payment
		// check for errorid = 569042
		
		$recievers = array(
			// seller's info
			0 => array(
				'amount' => '1.00',
				'email' => $email,
				'primary' => false
			),
			// buggl's info
			1 => array(
				'amount' => '1.00',
				'email' => $bugglEmail,
				'primary' => true
			)
		);
		
		$transactionData = array(
			'trackingId' => uniqid(),
			'cancelUrl' => 'http://www.cancel.com',
			'returnUrl' => 'http://www.return.com',
			'ipnNotificationUrl' => 'http://www.ipn.com',
			'receivers' => $recievers,
		);
		
		$result = $this->createChainedPayment($transactionData);
		return !(isset($result->error) && $result->error[0]->errorId == '569042');
	}
	
	public function verifyIPNMessage($messageData)
	{	
		$data = array_merge(array('cmd'=>'_notify-validate'),$messageData);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->ipnVerificationUrl);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		$response = curl_exec($ch);
		return $response;
	}
	
	private function getRequestHeaders()
	{
		$headers = array();
		$headers[] = 'X-PAYPAL-SECURITY-USERID: '.$this->userId;
		$headers[] = 'X-PAYPAL-SECURITY-PASSWORD: '.$this->password;
		$headers[] = 'X-PAYPAL-SECURITY-SIGNATURE: '.$this->signature;
		$headers[] = 'X-PAYPAL-APPLICATION-ID: '.$this->appId;
		$headers[] = 'X-PAYPAL-REQUEST-DATA-FORMAT: JSON';
		$headers[] = 'X-PAYPAL-RESPONSE-DATA-FORMAT: JSON';
		$headers[] = 'X-PAYPAL-DEVICE-IPADDRESS: '.$this->ipAddress;
		
		return $headers;
	}
	
	private function curlPost($endpoint,$data)
	{
		$headers = $this->getRequestHeaders();
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $endpoint);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

		$response = curl_exec($ch);
		//var_dump($response);
		//var_dump($data);
		return json_decode($response);
	}
}