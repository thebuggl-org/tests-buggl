<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SpotDetailToSpotCategory
 *
 * @ORM\Table(name="spot_detail_to_spot_category")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\SpotDetailToSpotCategoryRepository")
 */
class SpotDetailToSpotCategory
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
     * @var \SpotCategory
     *
     * @ORM\ManyToOne(targetEntity="SpotCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="spot_category_id", referencedColumnName="id")
     * })
     */
    private $spot_category;


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
     * @param \Buggl\MainBundle\Entity\SpotDetail $spot_detail
     * @return SpotDetail
     */
    public function setSpotDetail(\Buggl\MainBundle\Entity\SpotDetail $spot_detail = null)
    {
        $this->spot_detail = $spot_detail;

        return $this;
    }

    /**
     * Get spot_detail
     *
     * @return \Buggl\MainBundle\Entity\SpotDetail
     */
    public function getSpotDetail()
    {
        return $this->spot_detail;
    }

    /**
     * Set spot_category
     *
     * @param \Buggl\MainBundle\Entity\SpotCategory $spot_category
     * @return SpotDetail
     */
    public function setSpotCategory(\Buggl\MainBundle\Entity\SpotCategory $spot_category = null)
    {
        $this->spot_category = $spot_category;

        return $this;
    }

    /**
     * Get spot_category
     *
     * @return \Buggl\MainBundle\Entity\SpotCategory
     */
    public function getSpotCategory()
    {
        return $this->spot_category;
    }
}
