<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpotDetail
 *
 * @ORM\Table(name="spot_detail")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\SpotDetailRepository")
 */
class SpotDetail
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
     * @var \Spot
     *
     * @ORM\ManyToOne(targetEntity="Spot")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="spot_id", referencedColumnName="id")
     * })
     */
    private $spot;

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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=250)
     */
    private $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="smallint")
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=250)
     */
    private $photo;

    /**
     * @var \SpotCategory
     *
     * @ORM\ManyToOne(targetEntity="SpotCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="spot_category_id", referencedColumnName="id")
     * })
     */
    private $spotCategory;

    /**
     * @var \SpotType
     *
     * @ORM\ManyToOne(targetEntity="SpotType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="spot_type_id", referencedColumnName="id")
     * })
     */
    private $spotType;

    /**
     * @var string
     *
     * @ORM\Column(name="best_thing", type="string", length=250)
     */
    private $bestThing;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $dateAdded;

    /**
     * @var \SpotLikes
     *
     * @ORM\ManyToMany(targetEntity="SpotLike")
     * @ORM\JoinTable(name="spot_detail_to_spot_like",
     *      joinColumns={@ORM\JoinColumn(name="spot_detail_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="spot_like_id", referencedColumnName="id")}
     *      )
     */
    private $spotLikes;

    /**
     * @var \SpotCategory
     *
     * @ORM\ManyToMany(targetEntity="SpotCategory")
     * @ORM\JoinTable(name="spot_detail_to_spot_category",
     *      joinColumns={@ORM\JoinColumn(name="spot_detail_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="spot_category_id", referencedColumnName="id")}
     *      )
     */
    private $spotCategories;

    /**
     * 
     */ 
    private $ratingText = array(1 => 'Worth a Peek', 2 => 'Good Detour', 3 => 'Make Your Trip');

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
     * Set spot
     *
     * @param \Buggl\MainBundle\Entity\Spot $spot
     * @return SpotDetail
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

    /**
     * Set localAuthor
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $localAuthor
     * @return SpotDetail
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

    /**
     * Set title
     *
     * @param string $title
     * @return SpotDetail
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
     * Set rating
     *
     * @param integer $rating
     * @return SpotDetail
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating
     *
     * @return integer
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return SpotDetail
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
     * @return SpotDetail
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
        return str_replace("https", "http", $this->photo);
        // return $this->photo;
    }

    /**
     * Set spotCategory
     *
     * @param \Buggl\MainBundle\Entity\SpotCategory $spotCategory
     * @return SpotDetail
     */
    public function setSpotCategory(\Buggl\MainBundle\Entity\SpotCategory $spotCategory = null)
    {
        $this->spotCategory = $spotCategory;

        return $this;
    }

    /**
     * Get spotCategory
     *
     * @return \Buggl\MainBundle\Entity\SpotCategory
     */
    public function getSpotCategory()
    {
        return $this->spotCategory;
    }

    /**
     * Set spotType
     *
     * @param \Buggl\MainBundle\Entity\SpotType $spotType
     * @return SpotDetail
     */
    public function setSpotType(\Buggl\MainBundle\Entity\SpotType $spotType = null)
    {
        $this->spotType = $spotType;

        return $this;
    }

    /**
     * Get spotType
     *
     * @return \Buggl\MainBundle\Entity\SpotType
     */
    public function getSpotType()
    {
        return $this->spotType;
    }

    /**
     * Set bestThing
     *
     * @param string $bestThing
     * @return SpotDetail
     */
    public function setBestThing($bestThing)
    {
        $this->bestThing = $bestThing;

        return $this;
    }

    /**
     * Get bestThing
     *
     * @return string
     */
    public function getBestThing()
    {
        return $this->bestThing;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return SpotDetail
     */
    public function setDateAdded(\DateTime $dateAdded)
    {
        $this->dateAdded = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->dateAdded;
    }

    /**
     * Get spotLikes
     *
     * @return array \Buggl\MainBundle\Entity\SpotLike
     */
    public function getSpotLikes()
    {
        return $this->spotLikes;
    }

    /**
     * Get spotCategories
     *
     * @return array \Buggl\MainBundle\Entity\SpotCategory
     */
    public function getSpotCategories()
    {
        return $this->spotCategories;
    }

    public function getRatingText()
    {
        return isset($this->ratingText[$this->rating]) ? $this->ratingText[$this->rating] : null;
    }
}
