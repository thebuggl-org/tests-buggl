<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Itinerary
 *
 * @ORM\Table(name="itinerary")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\ItineraryRepository")
 */
class Itinerary
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
    private $e_guide;

    /**
     * @var integer
     *
     * @ORM\Column(name="day_num", type="smallint")
     */
    private $day_num;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=500)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string" )
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=250)
     */
    private $photo;

    /**
     * @var \EGuidePhoto
     *
     * @ORM\ManyToOne(targetEntity="EGuidePhoto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="e_guide_photo_id", referencedColumnName="id")
     * })
     */
    private $e_guide_photo;

    /**
     * @var \SpotDetails
     *
     * @ORM\ManyToMany(targetEntity="SpotDetail")
     * @ORM\JoinTable(name="itinerary_to_spot_detail",
     *      joinColumns={@ORM\JoinColumn(name="itinerary_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="spot_detail_id", referencedColumnName="id")}
     *      )
     */
    private $spotDetails;

    public function __construct()
    {
        $this->spotDetails = array();

    }
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
     * Set e_guide
     *
     * @param \Buggl\MainBundle\Entity\EGuide $e_guide
     * @return Itinerary
     */
    public function setEGuide(\Buggl\MainBundle\Entity\EGuide $e_guide = null)
    {
        $this->e_guide = $e_guide;

        return $this;
    }

    /**
     * Get e_guide
     *
     * @return \Buggl\MainBundle\Entity\EGuide
     */
    public function getEGuide()
    {
        return $this->e_guide;
    }

    /**
     * Set day_num
     *
     * @param integer $dayNum
     * @return Itinerary
     */
    public function setDayNum($dayNum)
    {
        $this->day_num = $dayNum;

        return $this;
    }

    /**
     * Get day_num
     *
     * @return integer
     */
    public function getDayNum()
    {
        return $this->day_num;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Itinerary
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Itinerary
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return Itinerary
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set e_guide_photo
     *
     * @param \Buggl\MainBundle\Entity\EGuidePhoto $e_guide_photo
     * @return Itinerary
     */
    public function setEGuidePhoto(\Buggl\MainBundle\Entity\EGuidePhoto $e_guide_photo = null)
    {
        $this->e_guide_photo = $e_guide_photo;

        return $this;
    }

    /**
     * Get e_guide
     *
     * @return \Buggl\MainBundle\Entity\EGuide
     */
    public function getEGuidePhoto()
    {
        return $this->e_guide_photo;
    }

    /**
     * Get spotDetails
     *
     * @return array \Buggl\MainBundle\Entity\SpotDetail
     */
    public function getSpotDetails()
    {
        return $this->spotDetails;
    }
}
