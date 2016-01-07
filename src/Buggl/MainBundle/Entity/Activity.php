<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Activity
 *
 * @ORM\Table(name="activity")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\ActivityRepository")
 */
class Activity
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
     * @ORM\Column(name="object_id", type="integer", nullable=false)
     */
    private $objectId;

    /**
     * @var \Activity
     *
     * @ORM\ManyToOne(targetEntity="ActivityType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * })
     */
    private $type;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="actor_id", referencedColumnName="id")
     * })
     */
    private $actor;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="receiver_id", referencedColumnName="id")
     * })
     */
    private $receiver;

    /**
     * @var integer
     *
     * @ORM\Column(name="show_in_admin", type="integer", nullable=false)
     */
    private $showInAdmin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime", nullable=false)
     */
    private $dateAdded;


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
     * Set objectId
     *
     * @param integer $objectId
     * @return Activity
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * Get objectId
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set type
     *
     * @param \Buggl\MainBundle\Entity\ActivityType $type
     * @return Activity
     */
    public function setType(\Buggl\MainBundle\Entity\ActivityType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Buggl\MainBundle\Entity\ActivityType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set actor
     *
     * @param \Buggl\MainBundle\Entity\User $actor
     * @return Activity
     */
    public function setActor(\Buggl\MainBundle\Entity\User $actor = null)
    {
        $this->actor = $actor;

        return $this;
    }

    /**
     * Get actor
     *
     * @return \Buggl\MainBundle\Entity\User
     */
    public function getActor()
    {
        return $this->actor;
    }

    /**
     * Set receiver
     *
     * @param \Buggl\MainBundle\Entity\User $receiver
     * @return Activity
     */
    public function setReceiver(\Buggl\MainBundle\Entity\User $receiver = null)
    {
        $this->receiver = $receiver;

        return $this;
    }

    /**
     * Get receiver
     *
     * @return \Buggl\MainBundle\Entity\User
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Set $showInAdmin
     *
     * @param integer $showInAdmin
     * @return Activity
     */
    public function setShowInAdmin($showInAdmin)
    {
        $this->showInAdmin = $showInAdmin;

        return $this;
    }

    /**
     * Get $showInAdmin
     *
     * @return integer
     */
    public function getShowInAdmin()
    {
        return $this->showInAdmin;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     * @return Message
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
}