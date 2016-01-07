<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EGuideRequest
 *
 * @ORM\Table(name="e_guide_request")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\EGuideRequestRepository")
 */
class EGuideRequest
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
     * @var \Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;

    

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

      /**
      * @var \Duration
      *
      * @ORM\ManyToOne(targetEntity="Duration")
      * @ORM\JoinColumns({
      *   @ORM\JoinColumn(name="duration_id", referencedColumnName="id")
      * })
      */
    private $duration;

    /**
     * @var \GoodFor
     *
     * @ORM\ManyToOne(targetEntity="GoodFor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="good_for_id", referencedColumnName="id")
     * })
     */
    private $good_for;

    /**
     * @var \TripTheme
     *
     * @ORM\ManyToOne(targetEntity="TripTheme")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="trip_theme_id", referencedColumnName="id")
     * })
     */
    private $trip_theme;
    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

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
     * @var integer
     *
     * @ORM\Column(name="budget", type="integer")
     */
    private $budget;
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="price", type="float", nullable=false)
     */

    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="pace", type="string", nullable=false)
     */
    private $pace;

/**
     * @var string
     *
     * @ORM\Column(name="reason", type="string", nullable=false)
     */
    private $reason;

     /**
     * @var string
     *
     * @ORM\Column(name="tripplan", type="string", nullable=false)
     */
    private $tripplan;

    /**
     * @var string
     *
     * @ORM\Column(name="experience", type="string")
     */
    private $experience;     

     /**
     * @var string
     *
     * @ORM\Column(name="food", type="string", nullable=false)
     */
    private $food;


    /**
     * @var string
     *
     * @ORM\Column(name="drinking", type="string", nullable=false)
     */
    private $drinking;

     /**
     * @var string
     *
     * @ORM\Column(name="shopping", type="string", nullable=false)
     */
    private $shopping;

    /**
     * @var string
     *
     * @ORM\Column(name="activities", type="string", nullable=false)
     */
    private $activities;

     /**
     * @var string
     *
     * @ORM\Column(name="hotel", type="string", nullable=false)
     */
    private $hotel;

     /**
     * @var string
     *
     * @ORM\Column(name="specialtouches", type="string", nullable=false)
     */
    private $specialtouches;

    /**
     * @var string
     *
     * @ORM\Column(name="destination", type="string", nullable=true)
     */
    private $destination=0;
    
    /**
     * @var string
     *
     * @ORM\Column(name="plantype", type="string", nullable=false)
     */
    private $plantype;

    private $duration_id;

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
     * Set country
     *
     * @param \stdClass $country
     * @return EGuideRequest
     */
    public function setCountry(\Buggl\MainBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \stdClass
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set trip_theme
     *
     * @param \stdClass $tripTheme
     * @return EGuideRequest
     */
    public function setTripTheme(\Buggl\MainBundle\Entity\TripTheme $tripTheme = null)
    {
        $this->trip_theme = $tripTheme;

        return $this;
    }

    /**
     * Get trip_theme
     *
     * @return \stdClass
     */
    public function getTripTheme()
    {
        return $this->trip_theme;
    }

    /**
     * Set category
     *
     * @param \stdClass $category
     * @return EGuideRequest
     */
    public function setCategory(\Buggl\MainBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \stdClass
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set duration
     *
     * @param \stdClass $duration
     * @return EGuideRequest
     */
    public function setDuration(\Buggl\MainBundle\Entity\Duration $duration = null)
    {
		//die($duration);
           $this->duration= $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return \stdClass
     */
    public function getDuration()
    {
        return $this->duration_id;
    }

    /**
     * Set good_for
     *
     * @param \stdClass $goodFor
     * @return EGuideRequest
     */
    public function setGoodFor(\Buggl\MainBundle\Entity\GoodFor $goodFor = null)
    {
        $this->good_for = $goodFor;

        return $this;
    }

    /**
     * Get good_for
     *
     * @return \stdClass
     */
    public function getGoodFor()
    {
        return $this->good_for;
    }

    /**
     * Set user
     *
     * @param \stdClass $user
     * @return EGuideRequest
     */
    public function setUser(\Buggl\MainBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set local_author_id
     *
     * @param \stdClass $localAuthorId
     * @return EGuideRequest
     */
    public function setLocalAuthor(\Buggl\MainBundle\Entity\LocalAuthor $localAuthor = null)
    {
        $this->localAuthor = $localAuthor;

        return $this;
    }

    /**
     * Get local_author_id
     *
     * @return \stdClass
     */
    public function getLocalAuthor()
    {
        return $this->localAuthor;
    }

    /**
     * Set budget
     *
     * @param integer $budget
     * @return EGuideRequest
     */
    public function setBudget($budget = null)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return integer
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set price
     *
     * @param integer $budget
     * @return EGuideRequest
     */
    public function setPrice($price = null)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return EGuideRequest
     */
    public function setMessage($message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set date_added
     *
     * @param \DateTime $dateAdded
     * @return EGuideRequest
     */
    public function setDateAdded($dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get date_added
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

       /**
     * Set status
     *
     * @param integer $status
     * @return EGuideRequest
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
     * Set pace
     *
     * @param string $pace
     * @return EGuideRequest
     */

    public function setPace($pace)
    {
        $this->pace = $pace;

        return $this;
    }
    /**
     * Get tripplan
     *
     * @return string
     */
    public function getPace()
    {
        return $this->pace;
    }
    /**
     * Set tripplan
     *
     * @param string $tripplan
     * @return EGuideRequest
     */

     public function setTripplan($tripplan)
    {
        $this->tripplan = $tripplan;

        return $this;
    }
    /**
     * Get tripplan
     *
     * @return string
     */
    public function getTripplan()
    {
        return $this->tripplan;
    }
    /**
     * Set experience
     *
     * @param integer $experience
     * @return EGuideRequest
     */
     
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }
    /**
     * Get experience
     *
     * @return string
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set food
     *
     * @param integer $food
     * @return EGuideRequest
     */

    public function setFood($food)
    {
        $this->food = $food;

        return $this;
    }
    /**
     * Get food
     *
     * @return string
     */
    public function getFood()
    {
        return $this->food;
    }


     /**
     * Set drinking
     *
     * @param integer $drinking
     * @return EGuideRequest
     */

    public function setDrinking($drinking)
    {
        $this->drinking = $drinking;

        return $this;
    }

     /**
     * Get food
     *
     * @return string
     */
    public function getDrinking()
    {
        return $this->drinking;
    }


     /**
     * Set shopping
     *
     * @param integer $shopping
     * @return EGuideRequest
     */
    public function setShopping($shopping)
    {
        $this->shopping = $shopping;

        return $this;
    }
      /**
     * Get food
     *
     * @return string
     */
    public function getShopping()
    {
        return $this->shopping;
    }


      /**
     * Set activities
     *
     * @param integer $activities
     * @return EGuideRequest
     */
    public function setActivities($activities)
    {
        $this->activities = $activities;

        return $this;
    }

       /**
     * Get activities
     *
     * @return string
     */
    public function getActivities()
    {
        return $this->activities;
    }


       /**
     * Set hotel
     *
     * @param integer $hotel
     * @return EGuideRequest
     */
    public function setHotel($hotel)
    {
        $this->hotel = $hotel;

        return $this;
    }

       /**
     * Get food
     *
     * @return string
     */
    public function getHotel()
    {
        return $this->hotel;
    }

       /**
     * Set activities
     *
     * @param string $specialtouches
     * @return EGuideRequest
     */
    public function setSpecialTouches($specialtouches)
    {
        $this->specialtouches = $specialtouches;

        return $this;
    }
      /**
     * Get food
     *
     * @return string
     */
    public function getSpecialTouches()
    {
        return $this->specialtouches;
    }

      /**
     * Set activities
     *
     * @param string $reason
     * @return EGuideRequest
     */
    public function setReason($reason)
    {
        $this->reason = $reason;

        return $this;
    }
      /**
     * Get food
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set activities
     *
     * @param string $destination
     * @return EGuideRequest
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }
    /**
     * Get destination
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }
   
    /**
     * Set activities
     *
     * @param string $plantype
     * @return EGuideRequest
     */
    public function setPlanType($plantype)
    {
        $this->plantype = $plantype;

        return $this;
    }
    /**
     * Get plantype
     *
     * @return string
     */
    public function getPlanType()
    {
        return $this->plantype;
    }

      /**
     * Set activities
     *
     * @param string $userfile
     * @return EGuideRequest
     */
    public function setUserfile($userfile)
    {
        $this->user_file = $userfile;

        return $this;
    }
      /**
     * Get food
     *
     * @return string
     */
    public function getUserfile()
    {
        return $this->user_file;
    }

}
