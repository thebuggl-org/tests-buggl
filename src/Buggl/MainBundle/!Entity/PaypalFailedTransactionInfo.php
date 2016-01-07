<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PaypalFailedTransactionInfo
 *
 * @ORM\Table(name="paypal_failed_transaction_info")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\PaypalFailedTransactionInfoRepository")
 */
class PaypalFailedTransactionInfo
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_transaction", type="datetime", nullable=false)
     */
    private $dateOfTransaction;
	
    /**
     * @var string
     *
     * @ORM\Column(name="paypal_payment_details", type="string", nullable=true)
     */
    private $paypalPaymentDetails;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
}