<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MessageThread
 *
 * @ORM\Table(name="message_thread")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\MessageThreadRepository")
 */
class MessageThread
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
     * @ORM\Column(name="subject", type="string", length=30, nullable=false)
     */
    private $subject;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=false)
     */
    private $dateUpdated;

    /**
     * @var string
     *
     * @ORM\Column(name="users_key", type="string", length=200, nullable=false)
     */
    private $usersKey;
    
    /**
     * @var string
     *
     * @ORM\Column(name="messagetypes", type="string")
     */
    private $messagetypes;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="eguide_request_id", type="integer")
     */
    private $eguideRequest=0;


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
     * Set subject
     *
     * @param string $subject
     * @return MessageThread
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
		if(empty($this->subject) || is_null($this->subject))
			return 'No Subject';

        return $this->subject;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return MessageThread
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return MessageThread
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
     * Set usersKey
     *
     * @param string $usersKey
     * @return MessageThread
     */
    public function setUsersKey($usersKey)
    {
        $this->usersKey = $usersKey;

        return $this;
    }

    /**
     * Get usersKey
     *
     * @return string
     */
    public function getUsersKey()
    {
        return $this->usersKey;
    }
     /**
     * Set messagetype
     *
     *@param string $messagetypes
     * @return MessageThread
     */
    public function setMessagetype($messagetypes)
    {  
        //echo $messagetypes; die;
        $this->messagetypes =$messagetypes;

        return $this;
    }

    /**
     * Get messagetype
     *
     * @return string
     */
    public function getMessagetype()
    {
        return $this->messagetypes;
    }
    /**
     * Set messagetype
     *
     *@param string $eguideRequest
     * @return MessageThread
     */
    public function setEguideRequest($eguideRequest)
    {  
        //echo $messagetypes; die;
        $this->eguideRequest =$eguideRequest;

        return $this;
    }

    /**
     * Get messagetype
     *
     * @return string
     */
    public function getEguideRequest()
    {
        return $this->eguideRequest;
    }
}