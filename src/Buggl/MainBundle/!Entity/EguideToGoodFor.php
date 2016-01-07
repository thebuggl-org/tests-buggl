<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EguideToGoodFor
 *
 * @ORM\Table(name="e_guide_to_good_for")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\EguideToGoodForRepository")
 */
class EguideToGoodFor
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
     * @var \EGuide
     *
     * @ORM\ManyToOne(targetEntity="EGuide")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="eguide_id", referencedColumnName="id")
     * })
     */
    private $e_guide;

    /**
     * @var \GoodFor
     *
     * @ORM\ManyToOne(targetEntity="GoodFor")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="good_for_id", referencedColumnName="id")
     * })
     */
    private $good_for;


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
     * Set e_guide
     *
     * @param \Buggl\MainBundle\Entity\EGuide $e_guide
     * @return EguideToGoodFor
     */
    public function setEGuide(\Buggl\MainBundle\Entity\EGuide $e_guide = null)
    {
        $this->e_guide = $e_guide;

        return $this;
    }

    /**
     * Get e_guide
     *
     * @return \Buggl\MainBundle\Entity\EGuide
     */
    public function getEGuide()
    {
        return $this->e_guide;
    }

    /**
     * Set good_for
     *
     * @param \Buggl\MainBundle\Entity\GoodFor $good_for
     * @return EguideToGoodFor
     */
    public function setGoodFor(\Buggl\MainBundle\Entity\GoodFor $good_for = null)
    {
        $this->good_for = $good_for;

        return $this;
    }

    /**
     * Get good_for
     *
     * @return \Buggl\MainBundle\Entity\GoodFor
     */
    public function getGoodFor()
    {
        return $this->good_for;
    }
}
