<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LocalAuthorToLocalReference
 *
 * @ORM\Table(name="local_author_to_local_reference")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\LocalAuthorToLocalReferenceRepository")
 */
class LocalAuthorToLocalReference
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
     * @ORM\Column(name="token", type="string", length=100, nullable=false)
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="token_expiration", type="datetime", nullable=false)
     */
    private $tokenExpiration;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var \LocalReference
     *
     * @ORM\ManyToOne(targetEntity="LocalReference")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="local_reference_id", referencedColumnName="id")
     * })
     */
    private $localReference;

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
     * Set token
     *
     * @param string $token
     * @return LocalAuthorToLocalReference
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
     * @return LocalAuthorToLocalReference
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
     * Set status
     *
     * @param integer $status
     * @return LocalAuthorToLocalReference
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
     * Set localReference
     *
     * @param \Buggl\MainBundle\Entity\LocalReference $localReference
     * @return LocalReference
     */
    public function setLocalReference(\Buggl\MainBundle\Entity\LocalReference $localReference = null)
    {
        $this->localReference = $localReference;

        return $this;
    }

    /**
     * Get localReference
     *
     * @return \Buggl\MainBundle\Entity\LocalReference
     */
    public function getLocalReference()
    {
        return $this->localReference;
    }

    /**
     * Set localAuthor
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $localAuthor
     * @return LocalAuthorToLocalReference
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

	public function isExpired()
	{
		return strtotime($localReferenceRequest->getTokenExpiration()->format('Y-m-d H:i:s')) < strtotime(date('Y-m-d H:i:s'));
	}
}