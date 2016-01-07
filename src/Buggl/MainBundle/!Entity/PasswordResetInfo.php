<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PasswordResetInfo
 *
 * @ORM\Table(name="password_reset_info")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\PasswordResetInfoRepository")
 */
class PasswordResetInfo
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
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=50, nullable=false)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="token_expiration", type="datetime", nullable=false)
     */
    private $tokenExpiration;


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
     * Set user
     *
     * @param \Buggl\MainBundle\Entity\User $actor
     * @return Activity
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
     * Set token
     *
     * @param string $token
     * @return PasswordResetInfo
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
     * @return PasswordResetInfo
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
}