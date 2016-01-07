<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PurchaseInfo
 *
 * @ORM\Table(name="purchase_info")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\PurchaseInfoRepository")
 */
class PurchaseInfo
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
     * @ORM\Column(name="net_amount", type="string", length=10, nullable=false)
     */
    private $netAmount;

    /**
     * @var string
     *
     * @ORM\Column(name="buggl_fee", type="string", length=10, nullable=false)
     */
    private $bugglFee;

    /**
     * @var string
     *
     * @ORM\Column(name="stripe_fee", type="string", length=10, nullable=false)
     */
    private $stripeFee;

    /**
     * @var string
     *
     * @ORM\Column(name="stripe_charge_id", type="string", length=100, nullable=false)
     */
    private $stripeChargeId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_of_transaction", type="datetime", nullable=false)
     */
    private $dateOfTransaction;

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
     * @var \LocalAuthor
     *
     * @ORM\ManyToOne(targetEntity="LocalAuthor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="seller_id", referencedColumnName="id")
     * })
     */
    private $seller;

    /**
     * @var \LocalAuthor
     *
     * @ORM\ManyToOne(targetEntity="LocalAuthor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="buyer_id", referencedColumnName="id")
     * })
     */
    private $buyer;



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
     * @return PurchaseInfo
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
     * Set netAmount
     *
     * @param string $netAmount
     * @return PurchaseInfo
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
     * Set bugglFee
     *
     * @param string $bugglFee
     * @return PurchaseInfo
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
     * Set stripeFee
     *
     * @param string $stripeFee
     * @return PurchaseInfo
     */
    public function setStripeFee($stripeFee)
    {
        $this->stripeFee = $stripeFee;

        return $this;
    }

    /**
     * Get stripeFee
     *
     * @return string
     */
    public function getStripeFee()
    {
        return $this->stripeFee;
    }

    /**
     * Set stripeChargeId
     *
     * @param string $stripeChargeId
     * @return PurchaseInfo
     */
    public function setStripeChargeId($stripeChargeId)
    {
        $this->stripeChargeId = $stripeChargeId;

        return $this;
    }

    /**
     * Get stripeChargeId
     *
     * @return string
     */
    public function getStripeChargeId()
    {
        return $this->stripeChargeId;
    }

    /**
     * Set dateOfTransaction
     *
     * @param \DateTime $dateOfTransaction
     * @return PurchaseInfo
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
     * Set eguide
     *
     * @param \Buggl\MainBundle\Entity\EGuide $eguide
     * @return PurchaseInfo
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
     * @param \Buggl\MainBundle\Entity\LocalAuthor $seller
     * @return PurchaseInfo
     */
    public function setSeller(\Buggl\MainBundle\Entity\LocalAuthor $seller = null)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get seller
     *
     * @return \Buggl\MainBundle\Entity\LocalAuthor
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set buyer
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $buyer
     * @return PurchaseInfo
     */
    public function setBuyer(\Buggl\MainBundle\Entity\LocalAuthor $buyer = null)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get buyer
     *
     * @return \Buggl\MainBundle\Entity\LocalAuthor
     */
    public function getBuyer()
    {
        return $this->buyer;
    }
}