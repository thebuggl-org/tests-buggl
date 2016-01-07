<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Buggl\MainBundle\Entity\Review;

/**
 * TravelGuideReview
 *
 * @ORM\Table(name="travel_guide_review")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\TravelGuideReviewRepository")
 */
class TravelGuideReview extends Review
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer", nullable=false)
     */
    private $rating;

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
     * Set rating
     *
     * @param boolean $rating
     * @return ReviewToTravelGuides
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return boolean
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set eguide
     *
     * @param \Buggl\MainBundle\Entity\EGuide $eguide
     * @return ReviewToTravelGuides
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