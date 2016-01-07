<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpotToSpotLike
 *
 * @ORM\Table(name="spot_to_spot_like")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\SpotToLikeRepository")
 */
class SpotToSpotLike
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
     * @ORM\Column(name="spot_id", type="integer", nullable=false)
     */
    private $spotId;

    /**
     * @var \SpotLike
     *
     * @ORM\ManyToOne(targetEntity="SpotLike")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="spot_like_id", referencedColumnName="id")
     * })
     */
    private $spotLike;



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
     * Set spotId
     *
     * @param integer $spotId
     * @return SpotToSpotLike
     */
    public function setSpotId($spotId)
    {
        $this->spotId = $spotId;

        return $this;
    }

    /**
     * Get spotId
     *
     * @return integer
     */
    public function getSpotId()
    {
        return $this->spotId;
    }

    /**
     * Set spotLike
     *
     * @param \Buggl\MainBundle\Entity\SpotLike $spotLike
     * @return SpotToSpotLike
     */
    public function setSpotLike(\Buggl\MainBundle\Entity\SpotLike $spotLike = null)
    {
        $this->spotLike = $spotLike;

        return $this;
    }

    /**
     * Get spotLike
     *
     * @return \Buggl\MainBundle\Entity\SpotLike
     */
    public function getSpotLike()
    {
        return $this->spotLike;
    }
}