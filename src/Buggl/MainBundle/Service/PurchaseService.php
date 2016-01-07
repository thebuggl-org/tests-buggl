<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\PurchaseInfo;

class PurchaseService
{
	protected $entityManager;

	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function savePurchaseInfo($stripeData,$buyer,$eguide)
	{
		$purchaseInfo = new PurchaseInfo();
		$purchaseInfo->setSeller($eguide->getLocalAuthor());
		$purchaseInfo->setBuyer($buyer);
		$purchaseInfo->setEguide($eguide);
		$purchaseInfo->setAmount($stripeData['amount']);
		$purchaseInfo->setNetAmount($stripeData['netAmount']);
		$purchaseInfo->setBugglFee($stripeData['fees']['application_fee']);
		$purchaseInfo->setStripeFee($stripeData['fees']['stripe_fee']);
		$purchaseInfo->setStripeChargeId($stripeData['charge_id']);
		$purchaseInfo->setDateOfTransaction(new \DateTime(date('Y-m-d H:i:s')));
		$this->entityManager->persist($purchaseInfo);

		//$eguide->setDlCount($eguide->getDlCount()+1);
		//$this->entityManager->persist($eguide);

		$this->entityManager->flush();

		return $purchaseInfo;
	}
}