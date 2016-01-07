<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaypalPurchaseInfo
 *
 * @ORM\Table(name="paypal_purchase_info")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\PaypalPurchaseInfoRepository")
 */
class PaypalPurchaseInfo
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="amount", type="string", length=10, nullable=false)
     */
    private $amount;

    /**
     * @var string
     *
     * @ORM\Column(name="buggl_fee", type="string", length=10, nullable=false)
     */
    private $bugglFee;

    /**
     * @var string
     *
     * @ORM\Column(name="net_amount", type="string", length=10, nullable=false)
     */
    private $netAmount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_transaction", type="datetime", nullable=false)
     */
    private $dateOfTransaction;

    /**
     * @var string
     *
     * @ORM\Column(name="paypal_pay_key", type="string", length=100, nullable=false)
     */
    private $paypalPayKey;
	
    /**
     * @var string
     *
     * @ORM\Column(name="paypal_tracking_id", type="string", length=127, nullable=false)
     */
    private $paypalTrackingId;
	
    /**
     * @var string
     *
     * @ORM\Column(name="paypal_correlation_id", type="string", length=12, nullable=false)
     */
    private $paypalCorrelationId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="paypal_timestamp", type="datetime", nullable=false)
     */
    private $paypalTimestamp;

    /**
     * @var boolean
     *
     * @ORM\Column(name="paypal_payment_status", type="boolean", nullable=false)
     */
    private $paypalPaymentStatus;
	
    /**
     * @var string
     *
     * @ORM\Column(name="paypal_payment_details", type="string", nullable=true)
     */
    private $paypalPaymentDetails;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="LocalAuthor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="buyer_id", referencedColumnName="id")
     * })
     */
    private $buyer;

    /**
     * @var \EGuide
     *
     * @ORM\ManyToOne(targetEntity="EGuide")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="eguide_id", referencedColumnName="id")
     * })
     */
    private $eguide;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="LocalAuthor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="seller_id", referencedColumnName="id")
     * })
     */
    private $seller;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set amount
     *
     * @param string $amount
     * @return PaypalPurchaseInfo
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return string 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set bugglFee
     *
     * @param string $bugglFee
     * @return PaypalPurchaseInfo
     */
    public function setBugglFee($bugglFee)
    {
        $this->bugglFee = $bugglFee;
    
        return $this;
    }

    /**
     * Get bugglFee
     *
     * @return string 
     */
    public function getBugglFee()
    {
        return $this->bugglFee;
    }

    /**
     * Set netAmount
     *
     * @param string $netAmount
     * @return PaypalPurchaseInfo
     */
    public function setNetAmount($netAmount)
    {
        $this->netAmount = $netAmount;
    
        return $this;
    }

    /**
     * Get netAmount
     *
     * @return string 
     */
    public function getNetAmount()
    {
        return $this->netAmount;
    }

    /**
     * Set dateOfTransaction
     *
     * @param \DateTime $dateOfTransaction
     * @return PaypalPurchaseInfo
     */
    public function setDateOfTransaction($dateOfTransaction)
    {
        $this->dateOfTransaction = $dateOfTransaction;
    
        return $this;
    }

    /**
     * Get dateOfTransaction
     *
     * @return \DateTime 
     */
    public function getDateOfTransaction()
    {
        return $this->dateOfTransaction;
    }

    /**
     * Set paypalPayKey
     *
     * @param string $paypalPayKey
     * @return PaypalPurchaseInfo
     */
    public function setPaypalPayKey($paypalPayKey)
    {
        $this->paypalPayKey = $paypalPayKey;
    
        return $this;
    }

    /**
     * Get paypalPayKey
     *
     * @return string 
     */
    public function getPaypalPayKey()
    {
        return $this->paypalPayKey;
    }
	
    /**
     * Set paypalTrackingId
     *
     * @param string $paypalTrackingId
     * @return PaypalPurchaseInfo
     */
    public function setPaypalTrackingId($paypalTrackingId)
    {
        $this->paypalTrackingId = $paypalTrackingId;
    
        return $this;
    }

    /**
     * Get paypalTrackingId
     *
     * @return string 
     */
    public function getPaypalTrackingId()
    {
        return $this->paypalTrackingId;
    }

    /**
     * Set paypalCorrelationId
     *
     * @param string $paypalCorrelationId
     * @return PaypalPurchaseInfo
     */
    public function setPaypalCorrelationId($paypalCorrelationId)
    {
        $this->paypalCorrelationId = $paypalCorrelationId;
    
        return $this;
    }

    /**
     * Get paypalCorrelationId
     *
     * @return string 
     */
    public function getPaypalCorrelationId()
    {
        return $this->paypalCorrelationId;
    }

    /**
     * Set paypalTimestamp
     *
     * @param \DateTime $paypalTimestamp
     * @return PaypalPurchaseInfo
     */
    public function setPaypalTimestamp($paypalTimestamp)
    {
        $this->paypalTimestamp = $paypalTimestamp;
    
        return $this;
    }

    /**
     * Get paypalTimestamp
     *
     * @return \DateTime 
     */
    public function getPaypalTimestamp()
    {
        return $this->paypalTimestamp;
    }

    /**
     * Set paypalPaymentStatus
     *
     * @param boolean $paypalPaymentStatus
     * @return PaypalPurchaseInfo
     */
    public function setPaypalPaymentStatus($paypalPaymentStatus)
    {
        $this->paypalPaymentStatus = $paypalPaymentStatus;
    
        return $this;
    }

    /**
     * Get paypalPaymentStatus
     *
     * @return boolean 
     */
    public function getPaypalPaymentStatus()
    {
        return $this->paypalPaymentStatus;
    }
	
    /**
     * Set paypalPaymentDetails
     *
     * @param string $paypalPaymentDetails
     * @return PaypalPurchaseInfo
     */
    public function setPaypalPaymentDetails($paypalPaymentDetails)
    {
        $this->paypalPaymentDetails = $paypalPaymentDetails;
    
        return $this;
    }

    /**
     * Get paypalPaymentDetails
     *
     * @return string 
     */
    public function getPaypalPaymentDetails()
    {
        return $this->paypalPaymentDetails;
    }

    /**
     * Set buyer
     *
     * @param \Buggl\MainBundle\Entity\User $buyer
     * @return PaypalPurchaseInfo
     */
    public function setBuyer(\Buggl\MainBundle\Entity\User $buyer = null)
    {
        $this->buyer = $buyer;
    
        return $this;
    }

    /**
     * Get buyer
     *
     * @return \Buggl\MainBundle\Entity\User 
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set eguide
     *
     * @param \Buggl\MainBundle\Entity\EGuide $eguide
     * @return PaypalPurchaseInfo
     */
    public function setEguide(\Buggl\MainBundle\Entity\EGuide $eguide = null)
    {
        $this->eguide = $eguide;
    
        return $this;
    }

    /**
     * Get eguide
     *
     * @return \Buggl\MainBundle\Entity\EGuide 
     */
    public function getEguide()
    {
        return $this->eguide;
    }

    /**
     * Set seller
     *
     * @param \Buggl\MainBundle\Entity\User $seller
     * @return PaypalPurchaseInfo
     */
    public function setSeller(\Buggl\MainBundle\Entity\User $seller = null)
    {
        $this->seller = $seller;
    
        return $this;
    }

    /**
     * Get seller
     *
     * @return \Buggl\MainBundle\Entity\User 
     */
    public function getSeller()
    {
        return $this->seller;
    }
}