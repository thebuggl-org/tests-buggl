<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessageToUser
 *
 * @ORM\Table(name="message_to_user")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\MessageToUserRepository")
 */
class MessageToUser
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
     * @var integer
     *
     * @ORM\Column(name="notification_status", type="integer", nullable=false)
     */
    private $notificationStatus;

    /**
     * @var \Message
     *
     * @ORM\ManyToOne(targetEntity="Message")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="message_id", referencedColumnName="id")
     * })
     */
    private $message;

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
     *   @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     * })
     */
    private $sender;

    /**
     * @var string
     *
     * @ORM\Column(name="messagetype", type="string")
     */
    private $messagetype;

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
     * @return MessageToUser
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
     * Set notificationStatus
     *
     * @param integer $notificationStatus
     * @return MessageToUser
     */
    public function setNotificationStatus($notificationStatus)
    {
        $this->notificationStatus = $notificationStatus;

        return $this;
    }

    /**
     * Get notificationStatus
     *
     * @return integer
     */
    public function getNotificationStatus()
    {
        return $this->notificationStatus;
    }

    /**
     * Set message
     *
     * @param \Buggl\MainBundle\Entity\Message $message
     * @return MessageToUser
     */
    public function setMessage(\Buggl\MainBundle\Entity\Message $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \Buggl\MainBundle\Entity\Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set user
     *
     * @param \Buggl\MainBundle\Entity\User $user
     * @return MessageToUser
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

    /**
     * Set thread
     *
     * @param \Buggl\MainBundle\Entity\MessageThread $thread
     * @return MessageToUser
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
     * Set sender
     *
     * @param \Buggl\MainBundle\Entity\User $sender
     * @return MessageToUser
     */
    public function setSender(\Buggl\MainBundle\Entity\User $sender = null)
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * Get sender
     *
     * @return \Buggl\MainBundle\Entity\User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set messagetype
     *
     *@param string $messagetype
     * @return MessageToUser
     */
    public function setMessagetype($messagetype)
    { 

        $this->messagetype = $messagetype;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessagetype()
    {
        return $this->messagetype;
    }

}