<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * LocalAuthor
 *
 * @ORM\Table(name="beta_invite")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\BetaInviteRepository")
 * @UniqueEntity(fields = "email", message="Email Address is already used.")
 */
class BetaInvite
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
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=300, nullable=true)
     */
    protected $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="token_expiration", type="datetime", nullable=false)
     */
    private $tokenExpiration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_invited", type="datetime", nullable=true)
     */
    protected $dateInvited;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return LocalAuthor
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return BetaToken
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }


    /**
     * Set tokenExpiration
     *
     * @param \DateTime $tokenExpiration
     * @return BetaToken
     */
    public function setTokenExpiration($tokenExpiration)
    {
        $this->tokenExpiration = $tokenExpiration;

        return $this;
    }

    /**
     * Get tokenExpiration
     *
     * @return \DateTime
     */
    public function getTokenExpiration()
    {
        return $this->tokenExpiration;
    }

    /**
     * Set dateInvited
     *
     * @param \DateTime $dateInvited
     * @return Review
     */
    public function setDateInvited($dateInvited)
    {
        $this->dateInvited = $dateInvited;

        return $this;
    }

    /**
     * Get dateInvited
     *
     * @return \DateTime
     */
    public function getDateInvited()
    {
        return $this->dateInvited;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return BetaInvite
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
     * Set sender
     *
     * @param \Buggl\MainBundle\Entity\User $sender
     * @return BetaInvite
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
}