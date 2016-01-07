<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Duration
 *
 * @ORM\Table(name="duration")
 * @ORM\Entity
 */
class Duration
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
     * @ORM\Column(name="name", type="string", length=45, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="no_of_days", type="integer", nullable=false)
     */
    private $no_of_days;


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
     * Set name
     *
     * @param string $name
     * @return Duration
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set no_of_days
     *
     * @param string $no_of_days
     * @return Duration
     */
    public function setNoOfDays($no_of_days)
    {
        $this->no_of_days = $no_of_days;

        return $this;
    }

    /**
     * Get no_of_days
     *
     * @return integer
     */
    public function getNoOfDays()
    {
        return $this->no_of_days;
    }
}