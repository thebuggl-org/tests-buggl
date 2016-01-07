<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SocialMedia
 *
 * @ORM\Table(name="social_media")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\SocialMediaRepository")
 */
class SocialMedia
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
     * @ORM\Column(name="fb_id", type="string", length=30, nullable=true)
     */
    private $fbId;

	/**
     * @var string
     *
     * @ORM\Column(name="fb_access_token", type="string", length=300, nullable=true)
     */
    private $fbAccessToken;

	/**
     * @var string
     *
     * @ORM\Column(name="fb_url", type="string", length=500, nullable=true)
     */
    private $fbUrl;

	/**
     * @var string
     *
     * @ORM\Column(name="fb_friends_count", type="string", length=10, nullable=true)
     */
    private $fbFriendsCount;

    /**
     * @var string
     *
     * @ORM\Column(name="twitter_id", type="string", length=30, nullable=true)
     */
    private $twitterId;

	/**
     * @var string
     *
     * @ORM\Column(name="twitter_access_token", type="string", length=300, nullable=true)
     */
    private $twitterAccessToken;
	
	/**
     * @var string
     *
     * @ORM\Column(name="twitter_token_secret", type="string", length=300, nullable=true)
     */
    private $twitterTokenSecret;

	/**
     * @var string
     *
     * @ORM\Column(name="twitter_url", type="string", length=500, nullable=true)
     */
    private $twitterUrl;

	/**
     * @var string
     *
     * @ORM\Column(name="twitter_followers_count", type="string", length=10, nullable=true)
     */
    private $twitterFollowersCount;
	
    /**
     * @var string
     *
     * @ORM\Column(name="google_plus_id", type="string", length=50, nullable=true)
     */
    private $googlePlusId;

	/**
     * @var string
     *
     * @ORM\Column(name="google_plus_refresh_token", type="string", length=300, nullable=true)
     */
    private $googlePlusRefreshToken;

	/**
     * @var string
     *
     * @ORM\Column(name="google_plus_access_token", type="string", length=300, nullable=true)
     */
    private $googlePlusAccessToken;

	/**
     * @var string
     *
     * @ORM\Column(name="google_plus_url", type="string", length=500, nullable=true)
     */
    private $googlePlusUrl;


	/**
     * @var string
     *
     * @ORM\Column(name="youtube_url", type="string", length=500, nullable=true)
     */
    private $youtubeUrl;

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
     * Set fbId
     *
     * @param string $fbId
     * @return SocialMedia
     */
    public function setFbId($fbId)
    {
        $this->fbId = $fbId;

        return $this;
    }

    /**
     * Get fbId
     *
     * @return string
     */
    public function getFbId()
    {
        return $this->fbId;
    }

	/**
     * Set fbAccessToken
     *
     * @param string $$fbAccessToken
     * @return SocialMedia
     */
    public function setFbAccessToken($fbAccessToken)
    {
        $this->fbAccessToken = $fbAccessToken;

        return $this;
    }

    /**
     * Get fbAccessToken
     *
     * @return string
     */
    public function getFbAccessToken()
    {
        return $this->fbAccessToken;
    }

	/**
     * Set fbUrl
     *
     * @param string $fbId
     * @return SocialMedia
     */
    public function setFbUrl($fbUrl)
    {
        $this->fbUrl = $fbUrl;

        return $this;
    }

    /**
     * Get fbUrl
     *
     * @return string
     */
    public function getFbUrl()
    {
        return $this->fbUrl;
    }

	/**
     * Set fbFriendsCount
     *
     * @param string $fbFriendsCount
     * @return SocialMedia
     */
    public function setFbFriendsCount($fbFriendsCount)
    {
        $this->fbFriendsCount = $fbFriendsCount;

        return $this;
    }

    /**
     * Get fbFriendsCount
     *
     * @return string
     */
    public function getFbFriendsCount()
    {
        return $this->fbFriendsCount;
    }

    /**
     * Set twitterId
     *
     * @param string $twitterId
     * @return SocialMedia
     */
    public function setTwitterId($twitterId)
    {
        $this->twitterId = $twitterId;

        return $this;
    }

    /**
     * Get twitterId
     *
     * @return string
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }

	/**
     * Set twitterAccessToken
     *
     * @param string $twitterAccessToken
     * @return SocialMedia
     */
    public function setTwitterAccessToken($twitterAccessToken)
    {
        $this->twitterAccessToken = $twitterAccessToken;

        return $this;
    }

    /**
     * Get twitterAccessToken
     *
     * @return string
     */
    public function getTwitterAccessToken()
    {
        return $this->twitterAccessToken;
    }
	
	/**
     * Set twitterTokenSecret
     *
     * @param string $twitterTokenSecret
     * @return SocialMedia
     */
    public function setTwitterTokenSecret($twitterTokenSecret)
    {
        $this->twitterTokenSecret = $twitterTokenSecret;

        return $this;
    }

    /**
     * Get twitterTokenSecret
     *
     * @return string
     */
    public function getTwitterTokenSecret()
    {
        return $this->twitterTokenSecret;
    }

	/**
     * Set twitterUrl
     *
     * @return SocialMedia
     */
    public function setTwitterUrl($twitterUrl)
    {
        $this->twitterUrl = $twitterUrl;

        return $this;
    }

    /**
     * Get twitterUrl
     *
     * @return string
     */
    public function getTwitterUrl()
    {
        return $this->twitterUrl;
    }
	
	/**
     * Set twitterFollowersCount
     *
     * @param string $twitterFollowersCount
     * @return SocialMedia
     */
    public function setTwitterFollowersCount($twitterFollowersCount)
    {
        $this->twitterFollowersCount = $twitterFollowersCount;

        return $this;
    }

    /**
     * Get twitterFollowersCount
     *
     * @return string
     */
    public function getTwitterFollowersCount()
    {
        return $this->twitterFollowersCount;
    }

    /**
     * Set googlePlusId
     *
     * @param string $googlePlusId
     * @return SocialMedia
     */
    public function setGooglePlusId($googlePlusId)
    {
        $this->googlePlusId = $googlePlusId;

        return $this;
    }

    /**
     * Get googlePlusId
     *
     * @return string
     */
    public function getGooglePlusId()
    {
        return $this->googlePlusId;
    }

	/**
     * Set googlePlusRefreshToken
     *
     * @param string $googlePlusRefreshToken
     * @return SocialMedia
     */
    public function setGooglePlusRefreshToken($googlePlusRefreshToken)
    {
        $this->googlePlusRefreshToken = $googlePlusRefreshToken;

        return $this;
    }

    /**
     * Get googlePlusRefreshToken
     *
     * @return string
     */
    public function getGooglePlusRefreshToken()
    {
        return $this->googlePlusRefreshToken;
    }

	/**
     * Set googlePlusAccessToken
     *
     * @param string $googlePlusAccessToken
     * @return SocialMedia
     */
    public function setGooglePlusAccessToken($googlePlusAccessToken)
    {
        $this->googlePlusAccessToken = $googlePlusAccessToken;

        return $this;
    }

    /**
     * Get googlePlusAccessToken
     *
     * @return string
     */
    public function getGooglePlusAccessToken()
    {
        return $this->googlePlusAccessToken;
    }

	/**
     * Set googlePlusUrl
     *
     * @return SocialMedia
     */
    public function setGooglePlusUrl($googlePlusUrl)
    {
        $this->googlePlusUrl = $googlePlusUrl;

        return $this;
    }

    /**
     * Get googlePlusUrl
     *
     * @return string
     */
    public function getGooglePlusUrl()
    {
        return $this->googlePlusUrl;
    }

	//===========

	/**
     * Set youtubeUrl
     *
     * @param string $fbId
     * @return SocialMedia
     */
    public function setYoutubeUrl($youtubeUrl)
    {
        $this->youtubeUrl = $youtubeUrl;

        return $this;
    }

    /**
     * Get youtubeUrl
     *
     * @return string
     */
    public function getYoutubeUrl()
    {
        return $this->youtubeUrl;
    }

    /**
     * Set localAuthor
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $localAuthor
     * @return SocialMedia
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