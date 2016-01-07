<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EGuideToSpotDetail
 *
 * @ORM\Table(name="e_guide_to_spot_detail")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\EGuideToSpotDetailRepository")
 */
class EGuideToSpotDetail
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
     *   @ORM\JoinColumn(name="e_guide_id", referencedColumnName="id")
     * })
     */
    private $eGuide;

    /**
     * @var \SpotDetail
     *
     * @ORM\ManyToOne(targetEntity="SpotDetail")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="spot_detail_id", referencedColumnName="id")
     * })
     */
    private $spotDetail;

    /**
     * @var integer
     *
     * @ORM\Column(name="`order`", type="smallint")
     */
    private $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_featured", type="integer", nullable=false)
     */
    private $isFeatured;

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
     * Set eGuide
     *
     * @param \Buggl\MainBundle\Entity\EGuide $eGuide
     * @return EGuideToSpot
     */
    public function setEGuide(\Buggl\MainBundle\Entity\EGuide $eGuide = null)
    {
        $this->eGuide = $eGuide;

        return $this;
    }

    /**
     * Get eGuide
     *
     * @return \Buggl\MainBundle\Entity\EGuide
     */
    public function getEGuide()
    {
        return $this->eGuide;
    }

    /**
     * Set spotDetail
     *
     * @param \Buggl\MainBundle\Entity\SpotDetail $spotDetail
     * @return EGuideToSpotDetail
     */
    public function setSpotDetail(\Buggl\MainBundle\Entity\SpotDetail $spotDetail = null)
    {
        $this->spotDetail = $spotDetail;

        return $this;
    }

    /**
     * Get spotDetail
     *
     * @return \Buggl\MainBundle\Entity\SpotDetail
     */
    public function getSpotDetail()
    {
        return $this->spotDetail;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return EGuideToSpotDetail
     */
    public function setOrder($order)
    {
        $this->order = $order;
    
        return $this;
    }

    /**
     * Get order
     *
     * @return integer 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set isFeatured
     *
     * @param integer $isFeatured
     * @return EGuideToSpot
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    /**
     * Get isFeatured
     *
     * @return integer
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * Set date_added
     *
     * @param \DateTime $dateAdded
     * @return EGuideToSpotDetail
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
