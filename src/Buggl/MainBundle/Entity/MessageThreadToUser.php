<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessageThreadToUser
 *
 * @ORM\Table(name="message_thread_to_user")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\MessageThreadToUserRepository")
 */
class MessageThreadToUser
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
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var \MessageThread
     *
     * @ORM\ManyToOne(targetEntity="MessageThread")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="thread_id", referencedColumnName="id")
     * })
     */
    private $thread;
    
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return MessageThreadToUser
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
     * Set thread
     *
     * @param \Buggl\MainBundle\Entity\MessageThread $thread
     * @return MessageThreadToUser
     */
    public function setThread(\Buggl\MainBundle\Entity\MessageThread $thread = null)
    {
        $this->thread = $thread;

        return $this;
    }

    /**
     * Get thread
     *
     * @return \Buggl\MainBundle\Entity\MessageThread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * Set user
     *
     * @param \Buggl\MainBundle\Entity\User $user
     * @return MessageThreadToUser
     */
    public function setUser(\Buggl\MainBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Buggl\MainBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}