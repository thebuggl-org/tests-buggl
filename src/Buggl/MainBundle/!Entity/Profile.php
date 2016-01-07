<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Profile
 *
 * @ORM\Table(name="profile")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\ProfileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Profile
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
     * @ORM\Column(name="phone", type="string", length=45, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="skype_id", type="string", length=100, nullable=true)
     */
    private $skypeId;

    /**
     * @var string
     *
     * @ORM\Column(name="local_since", type="integer", length=4, nullable=true)
     */
    private $localSince;

    /**
     * @var string
     *
     * @ORM\Column(name="interest_and_activities", type="string", length=250, nullable=true)
     */
    private $interestAndActivities;

    /**
     * @var string
     *
     * @ORM\Column(name="accomplishments", type="string", length=500, nullable=true)
     */
    private $accomplishments;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birth_date", type="string", length=30, nullable=true)
     */
    private $birthDate;

    /**
     * @var string
     *
     * @ORM\Column(name="profile_pic", type="string", length=100, nullable=false)
     */
    private $profilePic;

    /**
     * @var string
     *
     * @ORM\Column(name="about_you", type="string", length=500, nullable=false)
     */
    private $aboutYou;

    /**
     * @var string
     *
     * @ORM\Column(name="self_comment", type="string", length=500, nullable=false)
     */
    private $selfComment;

	/**
     * @var string
     *
     * @ORM\Column(name="kids_info", type="string", length=45, nullable=false)
     */
    private $kidsInfo;

    /**
     * @var \LocalAuthor
     *
     * @ORM\OneToOne(targetEntity="LocalAuthor", inversedBy="profile")
     * @ORM\JoinColumn(name="local_author_id", referencedColumnName="id")
     */
    private $localAuthor;

	/**
     * @var string
     *
     * @ORM\Column(name="work", type="string", length=50, nullable=false)
     */
    private $work;


	/**
	 *
     */
    public $file;

	/**
     *
     */
    private $prevImagePath;

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
     * Set phone
     *
     * @param string $phone
     * @return Profile
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set skypeId
     *
     * @param string $skypeId
     * @return Profile
     */
    public function setSkypeId($skypeId)
    {
        $this->skypeId = $skypeId;

        return $this;
    }

    /**
     * Get skypeId
     *
     * @return string
     */
    public function getSkypeId()
    {
        return $this->skypeId;
    }

    /**
     * Set $localSince
     *
     * @param integer $$localSince
     * @return Profile
     */
    public function setLocalSince($localSince)
    {
        $this->localSince = $localSince;

        return $this;
    }

    /**
     * Get $localSince
     *
     * @return integer
     */
    public function getLocalSince()
    {
        return $this->localSince;
    }

    /**
     * Set InterestAndActivities
     *
     * @param string $interestAndActivities
     * @return Profile
     */
    public function setInterestAndActivities($interestAndActivities)
    {
        $this->interestAndActivities = $interestAndActivities;

        return $this;
    }

    /**
     * Get interestAndActivities
     *
     * @return string
     */
    public function getInterestAndActivities()
    {
        return $this->interestAndActivities;
    }

    /**
     * Set accomplishments
     *
     * @param string $accomplishments
     * @return Profile
     */
    public function setAccomplishments($accomplishments)
    {
        $this->accomplishments = $accomplishments;

        return $this;
    }

    /**
     * Get accomplishments
     *
     * @return string
     */
    public function getAccomplishments()
    {
        return $this->accomplishments;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return Profile
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        /**
         * checks if birthdate's not set and returns default as 18 years before
         *
         * @author Farly Taboada <farly.taboada@goabroad.com> May 29, 2013
         */
        if ("0000-00-00 00:00:00" === $this->birthDate) {
            return date('Y-m-d',strtotime("-18 years"));
        }

        return $this->birthDate;
    }

    /**
     * Set profilePic
     *
     * @param string $profilePic
     * @return Profile
     */
    public function setProfilePic($profilePic)
    {
        $this->profilePic = $profilePic;

        return $this;
    }

    /**
     * Get profilePic
     *
     * @return string
     */
    public function getProfilePic()
    {
        return $this->profilePic;
    }

    /**
     * Set aboutYou
     *
     * @param string $aboutYou
     * @return Profile
     */
    public function setAboutYou($aboutYou)
    {
        $this->aboutYou = $aboutYou;

        return $this;
    }

    /**
     * Get aboutYou
     *
     * @return string
     */
    public function getAboutYou()
    {
        return $this->aboutYou;
    }

    /**
     * Set selfComment
     *
     * @param string $selfComment
     * @return Profile
     */
    public function setSelfComment($selfComment)
    {
        $this->selfComment = $selfComment;

        return $this;
    }

    /**
     * Get selfComment
     *
     * @return string
     */
    public function getSelfComment()
    {
        return $this->selfComment;
    }

	/**
     * Set kidsInfo
     *
     * @param string $kidsInfo
     * @return Profile
     */
    public function setKidsInfo($kidsInfo)
    {
        $this->kidsInfo = $kidsInfo;

        return $this;
    }

    /**
     * Get kidsInfo
     *
     * @return string
     */
    public function getKidsInfo()
    {
        return $this->kidsInfo;
    }

	/**
     * Set work
     *
     * @param string $work
     * @return Profile
     */
    public function setWork($work)
    {
        $this->work = $work;

        return $this;
    }

    /**
     * Get work
     *
     * @return string
     */
    public function getWork()
    {
        return $this->work;
    }

    /**
     * Set localAuthor
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $localAuthor
     * @return Profile
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

	public function getAge()
	{
        /**
         * date is today if bithdate's not set
         * @var date
         *
         * @author Farly Taboada <farly.taboada@goabroad.com> May 29,2013
         * @todo  this is a temporary fix. ask noel to improve?
         */
        $date = "0000-00-00 00:00:00" === $this->birthDate ? date("y-m-d") : $this->birthDate;

		$date1 =  new \DateTime($date);
		$date2 = new \DateTime(date("y-m-d"));
		$interval = $date1->diff($date2);

		if($interval->y == 0)
			return null;

		return $interval->y;
	}

	public function setPreviousImagePath($path)
	{
		$this->prevImagePath = $path;
	}

	public function getImageAbsolutePath()
    {
        return null === $this->profilePic ? null : $this->getUploadRootDir().'/'.$this->profilePic;
    }

    public function getImageWebPath()
    {
        return null === $this->profilePic ? null : $this->getUploadDir().'/'.$this->profilePic;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/profile_pics';
    }

	/**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->profilePic = $filename.'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
	public function upload()
	{
	    if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
		if(!@file_exists($this->getUploadRootDir())){
			mkdir($this->getUploadRootDir(),0777,true);
		}

        $this->file->move($this->getUploadRootDir(), $this->profilePic);

		if(!empty($this->prevImagePath)){
			@unlink($this->prevImagePath);
		}

        unset($this->file);
	}

	/**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
		$file = $this->getImageAbsolutePath();

        @unlink($file);
    }
}