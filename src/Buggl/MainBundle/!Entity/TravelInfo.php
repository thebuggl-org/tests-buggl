<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TravelInfo
 *
 * @ORM\Table(name="travel_info")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\TravelInfoRepository")
 */
class TravelInfo
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
     * @ORM\Column(name="why_am_i_the_best", type="string", length=1000, nullable=true)
     */
    private $whyAmITheBest;

    /**
     * @var string
     *
     * @ORM\Column(name="how_i_love_to_travel", type="string", length=1000, nullable=true)
     */
    private $howILoveToTravel;

    /**
     * @var string
     *
     * @ORM\Column(name="worst_travel_exp", type="string", length=1000, nullable=true)
     */
    private $worstTravelExp;

    /**
     * @var string
     *
     * @ORM\Column(name="best_travel_exp", type="string", length=1000, nullable=true)
     */
    private $bestTravelExp;

	/**
     * @var \LocalAuthor
     *
     * @ORM\ManyToOne(targetEntity="LocalAuthor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="local_author_id", referencedColumnName="id")
     * })
     */
    private $localAuthor;

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
     * Set whyAmITheBest
     *
     * @param string $whyAmITheBest
     * @return TravelInfo
     */
    public function setWhyAmITheBest($whyAmITheBest)
    {
        $this->whyAmITheBest = $whyAmITheBest;

        return $this;
    }

    /**
     * Get whyAmITheBest
     *
     * @return string
     */
    public function getWhyAmITheBest()
    {
        return $this->whyAmITheBest;
    }

    /**
     * Set howILoveToTravel
     *
     * @param string $howILoveToTravel
     * @return TravelInfo
     */
    public function setHowILoveToTravel($howILoveToTravel)
    {
        $this->howILoveToTravel = $howILoveToTravel;

        return $this;
    }

    /**
     * Get howILoveToTravel
     *
     * @return string
     */
    public function getHowILoveToTravel()
    {
        return $this->howILoveToTravel;
    }

    /**
     * Set worstTravelExp
     *
     * @param string $worstTravelExp
     * @return TravelInfo
     */
    public function setWorstTravelExp($worstTravelExp)
    {
        $this->worstTravelExp = $worstTravelExp;

        return $this;
    }

    /**
     * Get worstTravelExp
     *
     * @return string
     */
    public function getWorstTravelExp()
    {
        return $this->worstTravelExp;
    }

    /**
     * Set bestTravelExp
     *
     * @param string $bestTravelExp
     * @return TravelInfo
     */
    public function setBestTravelExp($bestTravelExp)
    {
        $this->bestTravelExp = $bestTravelExp;

        return $this;
    }

    /**
     * Get bestTravelExp
     *
     * @return string
     */
    public function getBestTravelExp()
    {
        return $this->bestTravelExp;
    }

	/**
     * Set localAuthor
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $localAuthor
     * @return LocalPassion
     */
    public function setLocalAuthor(\Buggl\MainBundle\Entity\LocalAuthor $localAuthor = null)
    {
        $this->localAuthor = $localAuthor;

        return $this;
    }

	/**
     * Get localAuthor
     *
     * @return \Buggl\MainBundle\Entity\LocalAuthor
     */
    public function getLocalAuthor()
    {
        return $this->localAuthor;
    }
}