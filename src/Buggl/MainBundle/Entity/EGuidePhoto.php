<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EGuidePhoto
 *
 * @ORM\Table(name="e_guide_photo")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\EGuidePhotoRepository")
 */
class EGuidePhoto
{
    const
        COVER_PHOTO = 1,
        OVERVIEW_PHOTO = 2,
        BEFORE_YOU_GO_PHOTO = 3,
        LOCAL_SECRET_PHOTO = 4,
        ITINERARY_PHOTO = 5;
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
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=250)
     */
    private $photo;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="smallint")
     */
    private $status;


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
     * @return EGuidePhoto
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
     * Set photo
     *
     * @param string $photo
     * @return EGuidePhoto
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
     * Set type
     *
     * @param integer $type
     * @return EGuidePhoto
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
     * Set status
     *
     * @param integer $status
     * @return EGuidePhoto
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
}
