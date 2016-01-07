<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\UserRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="integer")
 * @ORM\DiscriminatorMap({ 1 = "LocalAuthor", 2 = "AdminUsers" })
 */
class User
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
     * @var \DateTime
     *
     * @ORM\Column(name="last_active", type="datetime", nullable=false)
     */
    private $lastActive;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_inactive_notification_sent", type="datetime", nullable=false)
     */
    private $lastInactiveNotificationSent;


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
    * Set lastActive
    *
    * @param \DateTime $lastActive
    * @return User
    */
   public function setLastActive($lastActive)
   {
       $this->lastActive = $lastActive;

       return $this;
   }

   /**
    * Get lastActive
    *
    * @return \DateTime
    */
   public function getLastActive()
   {
       return $this->lastActive;
   }
   
   /**
   * Set lastInactiveNotificationSent
   *
   * @param \DateTime $lastInactiveNotificationSent
   * @return User
   */
  public function setLastInactiveNotificationSent($lastInactiveNotificationSent)
  {
      $this->lastInactiveNotificationSent = $lastInactiveNotificationSent;

      return $this;
  }

  /**
   * Get lastInactiveNotificationSent
   *
   * @return \DateTime
   */
  public function getLastInactiveNotificationSent()
  {
      return $this->lastInactiveNotificationSent;
  }
}