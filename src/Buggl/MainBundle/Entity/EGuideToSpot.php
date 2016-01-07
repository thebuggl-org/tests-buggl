<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EGuideToSpot
 *
 * @ORM\Table(name="e_guide_to_spot")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\EGuideToSpotRepository")
 */
class EGuideToSpot
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
     * @var integer
     *
     * @ORM\Column(name="day_num", type="integer", nullable=false)
     */
    private $dayNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="period_of_day", type="integer", nullable=true)
     */
    private $periodOfDay;

    /**
     * @var integer
     *
     * @ORM\Column(name="`order`", type="integer", nullable=false)
     */
    private $order;

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
     * @var \Spot
     *
     * @ORM\ManyToOne(targetEntity="Spot")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="spot_id", referencedColumnName="id")
     * })
     */
    private $spot;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer", nullable=false)
     */
    private $type;

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
     * Set dayNum
     *
     * @param integer $dayNum
     * @return EGuideToSpot
     */
    public function setDayNum($dayNum)
    {
        $this->dayNum = $dayNum;

        return $this;
    }

    /**
     * Get dayNum
     *
     * @return integer
     */
    public function getDayNum()
    {
        return $this->dayNum;
    }

    /**
     * Set periodOfDay
     *
     * @param integer $periodOfDay
     * @return EGuideToSpot
     */
    public function setPeriodOfDay($periodOfDay)
    {
        $this->periodOfDay = $periodOfDay;

        return $this;
    }

    /**
     * Get periodOfDay
     *
     * @return integer
     */
    public function getPeriodOfDay()
    {
        return $this->periodOfDay;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return EGuideToSpot
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
     * Set spot
     *
     * @param \Buggl\MainBundle\Entity\Spot $spot
     * @return EGuideToSpot
     */
    public function setSpot(\Buggl\MainBundle\Entity\Spot $spot = null)
    {
        $this->spot = $spot;

        return $this;
    }

    /**
     * Get spot
     *
     * @return \Buggl\MainBundle\Entity\Spot
     */
    public function getSpot()
    {
        return $this->spot;
    }

    public function getDay()
    {
        return "Day ".$this->dayNum;
    }

    public function getTime()
    {
        $timeOfDay = array(1 => 'Morning', 2 => 'Afternoon', 3 => 'Evening');
        return isset($timeOfDay[$this->periodOfDay]) ? $timeOfDay[$this->periodOfDay] : null;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return EGuideToSpot
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
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
     * @return EGuideContent
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