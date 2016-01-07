<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EGuideLocation
 *
 * @ORM\Table(name="e_guide_location")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\EGuideLocationRepository")
 */
class EGuideLocation
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
     * @var e_guide
     *
     * @ORM\ManyToOne(targetEntity="EGuide", inversedBy="locations")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="e_guide_id", referencedColumnName="id")
     * })
     **/
    private $e_guide;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=800)
     */
    private $address;


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
     * @return EGuideToCity
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
     * Set address
     *
     * @param string $address
     * @return EGuideLocation
     */
    public function setAddress($address)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }
}
