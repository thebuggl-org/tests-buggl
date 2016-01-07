<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpotDetailToSpotLike
 *
 * @ORM\Table(name="spot_detail_to_spot_like")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\SpotDetailToSpotLikeRepository")
 */
class SpotDetailToSpotLike
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \SpotDetail
     *
     * @ORM\ManyToOne(targetEntity="SpotDetail")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="spot_detail_id", referencedColumnName="id")
     * })
     */
    private $spot_detail;

    /**
     * @var \SpotLike
     *
     * @ORM\ManyToOne(targetEntity="SpotLike")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="spot_like_id", referencedColumnName="id")
     * })
     */
    private $spot_like;


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
     * Set spot_detail
     *
     * @param \Buggl\MainBundle\Entity\SpotDetail $spotDetail
     * @return SpotDetailToSpotLike
     */
    public function setSpotDetail(\Buggl\MainBundle\Entity\SpotDetail $spot_detail = null)
    {
        $this->spot_detail = $spot_detail;

        return $this;
    }

    /**
     * Get spot_detail
     *
     * @return \Buggl\MainBundle\Entity\SpotLike
     */
    public function getSpotDetail()
    {
        return $this->spot_detail;
    }

    /**
     * Set spot_like
     *
     * @param \Buggl\MainBundle\Entity\SpotLike $spot_like
     * @return SpotDetailToSpotLike
     */
    public function setSpotLike(\Buggl\MainBundle\Entity\SpotLike $spot_like = null)
    {
        $this->spot_like = $spot_like;

        return $this;
    }

    /**
     * Get spot_like
     *
     * @return \Buggl\MainBundle\Entity\SpotLike
     */
    public function getSpotLike()
    {
        return $this->spot_like  ;
    }
}
