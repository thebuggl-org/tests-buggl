<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Keyword
 *
 * @ORM\Table(name="keywords")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\KeywordRepository")
 */
class Keyword
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
     * @var \EGuide
     *
     * @ORM\ManyToOne(targetEntity="EGuide")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="eguide_id", referencedColumnName="id")
     * })
     */
    private $eGuide;

     /**
     * @var string
     *
     * @ORM\Column(name="keyword", type="string", length=500, nullable=true)
     */
    private $keyword;

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
     * Set eGuide
     *
     * @param \Buggl\MainBundle\Entity\EGuide $eGuide
     * @return Keyword
     */
    public function setEGuide(\Buggl\MainBundle\Entity\EGuide $eGuide = null)
    {
        $this->eGuide = $eGuide;

        return $this;
    }

    /**
     * Get eGuide
     *
     * @return \Buggl\MainBundle\Entity\EGuide
     */
    public function getEGuide()
    {
        return $this->eGuide;
    }

    /**
     * Set keyword
     *
     * @param string $keyword
     * @return EGuide
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;

        return $this;
    }

    /**
     * Get keyword
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }
}