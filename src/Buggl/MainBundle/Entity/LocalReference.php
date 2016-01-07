<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LocalReference
 *
 * @ORM\Table(name="local_reference")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\LocalReferenceRepository")
 */
class LocalReference
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
     * @ORM\Column(name="name", type="string", length=300, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="reference_email", type="string", length=300, nullable=false)
     */
    private $referenceEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=false)
     */
    private $dateUpdated;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_featured", type="boolean", nullable=false)
     */
    private $isFeatured;



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
     * @return LocalReference
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
     * Set referenceEmail
     *
     * @param string $referenceEmail
     * @return LocalReference
     */
    public function setReferenceEmail($referenceEmail)
    {
        $this->referenceEmail = $referenceEmail;

        return $this;
    }

    /**
     * Get referenceEmail
     *
     * @return string
     */
    public function getReferenceEmail()
    {
        return $this->referenceEmail;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return LocalReference
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return LocalReference
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set isFeatured
     *
     * @param boolean $isFeatured
     * @return LocalReference
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    /**
     * Get isFeatured
     *
     * @return boolean
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }
}