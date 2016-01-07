<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Buggl\MainBundle\Entity\User;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * LocalAuthor
 *
 * @ORM\Table(name="local_author")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\LocalAuthorRepository")
 * @UniqueEntity(fields = "email", message="Email Address is already used.")
 */
class LocalAuthor extends User implements AdvancedUserInterface
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
     * @ORM\Column(name="first_name", type="string", length=100, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     */
    protected $email;

	/**
     * @var boolean
     *
     * @ORM\Column(name="email_verified", type="boolean", nullable=false)
     */
    protected $emailVerified;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=200, nullable=true)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=150, nullable=true)
     */
    protected $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_joined", type="datetime", nullable=true)
     */
    protected $dateJoined;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    protected $status;

    /**
     * @ORM\OneToOne(targetEntity="Profile", mappedBy="localAuthor")
     */
    protected $profile;

    /**
     * @ORM\OneToOne(targetEntity="Location", mappedBy="localAuthor")
     */
    protected $location;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_local_author", type="boolean", nullable=false)
     */
    protected $isLocalAuthor;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_beta_participant", type="boolean", nullable=false)
     */
    protected $isBetaParticipant;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_approved", type="boolean", nullable=false)
     */
    protected $isApproved;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="street_credit", type="integer", nullable=false)
     */
    protected $streetCredit;

    /**
     * @var integer
     *
     * @ORM\Column(name="eguide_request", type="integer", nullable=true)
     */
    protected $eguideRequest=1;
    /**
     * @var string
     *
     * @ORM\Column(name="short_url", type="string",length=100, nullable=true)
     */
    protected $ShortUrl='1';


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
     * Set firstName
     *
     * @param string $firstName
     * @return LocalAuthor
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return LocalAuthor
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
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
     * Get emailVerified
     *
     * @return boolean
     */
    public function getEmailVerified()
    {
        return $this->emailVerified;
    }

	/**
     * Set emailVerified
     *
     * @param boolean $emailVerified
     * @return LocalAuthor
     */
    public function setEmailVerified($emailVerified)
    {
        $this->emailVerified = $emailVerified;

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
     * Set password
     *
     * @param string $password
     * @return LocalAuthor
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return LocalAuthor
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set dateJoined
     *
     * @param \DateTime $dateJoined
     * @return Review
     */
    public function setDateJoined($dateJoined)
    {
        $this->dateJoined = $dateJoined;

        return $this;
    }

    /**
     * Get dateJoined
     *
     * @return \DateTime
     */
    public function getDateJoined()
    {
        return $this->dateJoined;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Review
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
     * Get isLocalAuthor
     *
     * @return boolean
     */
    public function getIsLocalAuthor()
    {
        return $this->isLocalAuthor;
    }

    /**
     * Set isLocalAuthor
     *
     * @param boolean $isLocalAuthor
     * @return LocalAuthor
     */
    public function setIsLocalAuthor($isLocalAuthor)
    {
        $this->isLocalAuthor = $isLocalAuthor;

        return $this;
    }

    /**
    * Get isBetaParticipant
    *
    * @return boolean
    */
   public function getIsBetaParticipant()
   {
       return $this->isBetaParticipant;
   }

   /**
    * Set isBetaParticipant
    *
    * @param boolean $isBetaParticipant
    * @return LocalAuthor
    */
   public function setIsBetaParticipant($isBetaParticipant)
   {
       $this->isBetaParticipant = $isBetaParticipant;

       return $this;
   }

   /**
    * Get isApproved
    *
    * @return boolean
    */
   public function getIsApproved()
   {
       return $this->isApproved;
   }

   /**
    * Set isApproved
    *
    * @param boolean $isApproved
    * @return LocalAuthor
    */
   public function setIsApproved($isApproved)
   {
       $this->isApproved = $isApproved;

       return $this;
   }

	/**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

	/**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return "";
    }

	/**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        if($this->isLocalAuthor){
            return array('ROLE_LOCAL_AUTHOR');
        }

        return array('ROLE_TRAVELLER');
	}

	/**
     * Erase credentials
     *
     */
	public function eraseCredentials()
	{
	}

    /**
     * get full name
     * @param properize - whether or not to add proper apostrophe usage
     */
    public function getName($properize = false)
    {
        $name = $this->getFirstName() . " " . $this->getLastName();
        if($properize)
            return $name.'\''.($name[strlen($name) - 1] != 's' ? 's' : '');

        return $name;
    }

    /**
     * Set profile
     *
     * @param \Buggl\MainBundle\Entity\Profile $profile
     * @return LocalAuthor
     */
    public function setProfile(\Buggl\MainBundle\Entity\Profile $profile = null)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return \Buggl\MainBundle\Entity\Profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set location
     *
     * @param \Buggl\MainBundle\Entity\Location $location
     * @return LocalAuthor
     */
    public function setLocation(\Buggl\MainBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \Buggl\MainBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }
	
    /**
     * Set streetCredit
     *
     * @param integer $streetCredit
     * @return LocalAuthor
     */
    public function setStreetCredit($streetCredit)
    {
        $this->streetCredit = $streetCredit;

        return $this;
    }

    /**
     * Get streetCredit
     *
     * @return integer
     */
    public function getStreetCredit()
    {
        return $this->streetCredit;
    }

	public function getFireWall()
	{
		return 'secured_area';
	}

	public function __sleep()
    {
        return array('id');
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->status ? false: true;
    }

    /**
     * Get short_url
     *
     * @return string
     */
    public function getShortUrl()
    {
        return $this->ShortUrl;
    }

    /**
     * Set short_url
     *
     * @param string $shortUrl
     * @return LocalAuthor
     */
    public function setShortUrl($ShortUrl)
    {
        $this->ShortUrl = $ShortUrl;

        return $this;
    }

    /**
     * Get eguideRequest
     *
     * @return string
     */
    public function getEguideRequest()
    {
        return $this->eguideRequest;
    }

    /**
     * Set eguideRequest
     *
     * @param integer $eguideRequest
     * @return LocalAuthor
     */
    public function setEguideRequest($eguideRequest)
    {
        $this->eguideRequest = $eguideRequest;

        return $this;
    }
}