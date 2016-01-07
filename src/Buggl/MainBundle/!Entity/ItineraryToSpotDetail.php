<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItineraryToSpotDetail
 *
 * @ORM\Table(name="itinerary_to_spot_detail")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\ItineraryToSpotDetailRepository")
 */
class ItineraryToSpotDetail
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
     * @var \Itinerary
     * 
     * @ORM\ManyToOne(targetEntity="Itinerary")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="itinerary_id", referencedColumnName="id")
     * })
     */
    private $itinerary;

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
     * @ORM\Column(name="period_of_day", type="smallint")
     */
    private $periodOfDay;

    /**
     * @var integer
     *
     * @ORM\Column(name="`order`", type="smallint")
     */
    private $order;

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
     * Set itinerary
     *
     * @param \Buggl\MainBundle\Entity\SpotDetail $itinerary
     * @return ItineraryToSpotDetail
     */
    public function setItinerary(\Buggl\MainBundle\Entity\Itinerary $itinerary)
    {
        $this->itinerary = $itinerary;
    
        return $this;
    }

    /**
     * Get itinerary
     *
     * @return \Buggl\MainBundle\Entity\SpotDetail
     */
    public function getItinerary()
    {
        return $this->itinerary;
    }

    /**
     * Set spotDetail
     *
     * @param \Buggl\MainBundle\Entity\SpotDetail $spotDetail
     * @return ItineraryToSpotDetail
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
     * Set periodOfDay
     *
     * @param integer $periodOfDay
     * @return ItineraryToSpotDetail
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
     * @return ItineraryToSpotDetail
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
     * Set date_added
     *
     * @param \DateTime $dateAdded
     * @return ItineraryToSpotDetail
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

    public function getTime()
    {
        $timeOfDay = array(1 => 'Morning', 2 => 'Afternoon', 3 => 'Evening');
        return isset($timeOfDay[$this->periodOfDay]) ? $timeOfDay[$this->periodOfDay] : null;
    }
}
