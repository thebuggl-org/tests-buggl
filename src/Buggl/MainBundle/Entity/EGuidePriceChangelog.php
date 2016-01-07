<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EGuidePriceChangelog
 *
 * @ORM\Table(name="e_guide_price_changelog")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\EGuidePriceChangelogRepository")
 */
class EGuidePriceChangelog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

     /**
     * @var \EGuide
     *
     * @ORM\ManyToOne(targetEntity="EGuide")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="guide_id", referencedColumnName="id")
     * })
     */
    private $guide;

    /**
     * @var float
     *
     * @ORM\Column(name="price_from", type="float")
     */
    private $priceFrom;

    /**
     * @var float
     *
     * @ORM\Column(name="price_to", type="float")
     */
    private $priceTo;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $date_added;


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
     * Set guide
     *
     * @param \Buggl\MainBundle\Entity\EGuide $guide
     * @return EGuidePriceChangelog
     */
    public function setGuide(\Buggl\MainBundle\Entity\EGuide $guide = null)
    {
        $this->guide = $guide;
    
        return $this;
    }

    /**
     * Get guide
     *
     * @return \Buggl\MainBundle\Entity\EGuide
     */
    public function getGuide()
    {
        return $this->guide;
    }

    /**
     * Set from
     *
     * @param float $priceFrom
     * @return EGuidePriceChangelog
     */
    public function setPriceFrom($priceFrom)
    {
        $this->priceFrom = $priceFrom;
    
        return $this;
    }

    /**
     * Get from
     *
     * @return float 
     */
    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    /**
     * Set to
     *
     * @param float $priceTo
     * @return EGuidePriceChangelog
     */
    public function setPriceTo($priceTo)
    {
        $this->priceTo = $priceTo;
    
        return $this;
    }

    /**
     * Get to
     *
     * @return float 
     */
    public function getPriceTo()
    {
        return $this->priceTo;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return EGuidePriceChangelog
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set date_added
     *
     * @param \DateTime $dateAdded
     * @return EGuidePriceChangelog
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;
    
        return $this;
    }

    /**
     * Get date_added
     *
     * @return \DateTime 
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }
}
