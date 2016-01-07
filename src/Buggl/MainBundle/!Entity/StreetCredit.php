<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StreetCredit
 *
 * @ORM\Table(name="street_credit")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\StreetCreditRepository")
 */
class StreetCredit
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
     * @ORM\Column(name="guide_status", type="integer", nullable=true)
     */
    private $guideStatus;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="vouch_status", type="integer", nullable=true)
     */
    private $vouchStatus;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="profile_status", type="integer", nullable=true)
     */
    private $profileStatus;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="invite_author_status", type="integer", nullable=true)
     */
    private $inviteAuthorStatus;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="share_status", type="integer", nullable=true)
     */
    private $shareStatus;
	
    /**
     * @var integer
     *
     * @ORM\Column(name="connect_status", type="integer", nullable=true)
     */
    private $connectStatus;

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
     * Set guideStatus
     *
     * @param integer $guideStatus
     * @return StreetCredit
     */
    public function setGuideStatus($guideStatus)
    {
        $this->guideStatus = $guideStatus;

        return $this;
    }
	
    /**
     * Get guideStatus
     *
     * @return integer
     */
    public function getGuideStatus()
    {
        return $this->guideStatus;
    }

    /**
     * Get vouchStatus
     *
     * @return integer
     */
    public function getVouchStatus()
    {
        return $this->vouchStatus;
    }
	
    /**
     * Set vouchStatus
     *
     * @param integer $vouchStatus
     * @return StreetCredit
     */
    public function setVouchStatus($vouchStatus)
    {
        $this->vouchStatus = $vouchStatus;

        return $this;
    }
	
    /**
     * Set profileStatus
     *
     * @param integer $profileStatus
     * @return StreetCredit
     */
    public function setProfileStatus($profileStatus)
    {
        $this->profileStatus = $profileStatus;

        return $this;
    }
	
    /**
     * Get profileStatus
     *
     * @return integer
     */
    public function getProfileStatus()
    {
        return $this->profileStatus;
    }
	
    /**
     * Set inviteAuthorStatus
     *
     * @param integer $inviteAuthorStatus
     * @return StreetCredit
     */
    public function setInviteAuthorStatus($inviteAuthorStatus)
    {
        $this->inviteAuthorStatus = $inviteAuthorStatus;

        return $this;
    }
	
    /**
     * Get inviteAuthorStatus
     *
     * @return integer
     */
    public function getInviteAuthorStatus()
    {
        return $this->inviteAuthorStatus;
    }
	
    /**
     * Set shareStatus
     *
     * @param integer $shareStatus
     * @return StreetCredit
     */
    public function setShareStatus($shareStatus)
    {
        $this->shareStatus = $shareStatus;

        return $this;
    }
	
    /**
     * Get shareStatus
     *
     * @return integer
     */
    public function getShareStatus()
    {
        return $this->shareStatus;
    }
	
    /**
     * Set connectStatus
     *
     * @param integer $connectStatus
     * @return StreetCredit
     */
    public function setConnectStatus($connectStatus)
    {
        $this->connectStatus = $connectStatus;

        return $this;
    }
	
    /**
     * Get connectStatus
     *
     * @return integer
     */
    public function getConnectStatus()
    {
        return $this->connectStatus;
    }

    /**
     * Set localAuthor
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $localAuthor
     * @return StreetCredit
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