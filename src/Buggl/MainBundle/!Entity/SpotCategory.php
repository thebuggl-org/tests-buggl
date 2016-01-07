<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpotCategory
 *
 * @ORM\Table(name="spot_category")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\SpotCategoryRepository")
 */
class SpotCategory
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
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

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
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=true)
     */
    private $dateAdded;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return SpotCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return SpotCategory
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set localAuthor
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $localAuthor
     * @return EGuide
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
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return SpotCategory
     */
    public function setDateAdded($dateAdded)
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
     * Set spotType
     *
     * @param \Buggl\MainBundle\Entity\SpotType $spotType
     * @return SpotCategory
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
}