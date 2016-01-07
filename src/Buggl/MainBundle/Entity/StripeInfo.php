<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StripeInfo
 *
 * @ORM\Table(name="stripe_info")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\StripeInfoRepository")
 */
class StripeInfo
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
     * @ORM\Column(name="access_token", type="string", length=200, nullable=false)
     */
    private $accessToken;

    /**
     * @var string
     *
     * @ORM\Column(name="refresh_token", type="string", length=200, nullable=false)
     */
    private $refreshToken;

    /**
     * @var string
     *
     * @ORM\Column(name="stripe_user_id", type="string", length=100, nullable=false)
     */
    private $stripeUserId;

    /**
     * @var string
     *
     * @ORM\Column(name="stripe_pub_key", type="string", length=100, nullable=false)
     */
    private $stripePubKey;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set accessToken
     *
     * @param string $accessToken
     * @return StripeInfo
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set refreshToken
     *
     * @param string $refreshToken
     * @return StripeInfo
     */
    public function setRefreshToken($refreshToken)
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }

    /**
     * Get refreshToken
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Set stripeUserId
     *
     * @param string $stripeUserId
     * @return StripeInfo
     */
    public function setStripeUserId($stripeUserId)
    {
        $this->stripeUserId = $stripeUserId;

        return $this;
    }

    /**
     * Get stripeUserId
     *
     * @return string
     */
    public function getStripeUserId()
    {
        return $this->stripeUserId;
    }

    /**
     * Set stripePubKey
     *
     * @param string $stripePubKey
     * @return StripeInfo
     */
    public function setStripePubKey($stripePubKey)
    {
        $this->stripePubKey = $stripePubKey;

        return $this;
    }

    /**
     * Get stripePubKey
     *
     * @return string
     */
    public function getStripePubKey()
    {
        return $this->stripePubKey;
    }

    /**
     * Set localAuthor
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $localAuthor
     * @return StripeInfo
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
}