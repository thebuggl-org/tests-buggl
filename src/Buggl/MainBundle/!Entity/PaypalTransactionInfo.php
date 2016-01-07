<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaypalTransactionInfo
 *
 * @ORM\Table(name="paypal_transaction_info")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\PaypalTransactionInfoRepository")
 */
class PaypalTransactionInfo
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
     * @ORM\Column(name="primary_receiver_email", type="string", length=100, nullable=false)
     */
    private $primaryReceiverEmail;

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
     * @ORM\Column(name="paypal_transaction_details", type="string", nullable=true)
     */
    private $paypalTransactionDetails;



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
     * Set primaryReceiverEmail
     *
     * @param string $primaryReceiverEmail
     * @return PaypalTransactionInfo
     */
    public function setPrimaryReceiverEmail($primaryReceiverEmail)
    {
        $this->primaryReceiverEmail = $primaryReceiverEmail;
    
        return $this;
    }

    /**
     * Get primaryReceiverEmail
     *
     * @return string 
     */
    public function getPrimaryReceiverEmail()
    {
        return $this->primaryReceiverEmail;
    }

    /**
     * Set bugglFee
     *
     * @param string $bugglFee
     * @return PaypalTransactionInfo
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
     * Set amount
     *
     * @param string $amount
     * @return PaypalTransactionInfo
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
     * Set dateOfTransaction
     *
     * @param \DateTime $dateOfTransaction
     * @return PaypalTransactionInfo
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
     * @return PaypalTransactionInfo
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
     * @return PaypalTransactionInfo
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
     * Set paypalTransactionDetails
     *
     * @param string $paypalTransactionDetails
     * @return PaypalTransactionInfo
     */
    public function setPaypalTransactionDetails($paypalTransactionDetails)
    {
        $this->paypalTransactionDetails = $paypalTransactionDetails;
    
        return $this;
    }

    /**
     * Get paypalTransactionDetails
     *
     * @return string 
     */
    public function getPaypalTransactionDetails()
    {
        return $this->paypalPaymentDetails;
    }
}
