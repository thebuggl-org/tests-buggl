<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BeforeYouGo
 *
 * @ORM\Table(name="before_you_go")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\BeforeYouGoRepository")
 */
class BeforeYouGo
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
     *   @ORM\JoinColumn(name="e_guide_id", referencedColumnName="id")
     * })
     */
    private $e_guide;

    /**
     * @var string
     *
     * @ORM\Column(name="what_to_take", type="text")
     */
    private $what_to_take;

    /**
     * @var string
     *
     * @ORM\Column(name="getting_around", type="text")
     */
    private $getting_around;

    /**
     * @var string
     *
     * @ORM\Column(name="money_tipping", type="text")
     */
    private $money_tipping;

    /**
     * @var string
     *
     * @ORM\Column(name="safety", type="text")
     */
    private $safety;
	
    /**
     * @var string
     *
     * @ORM\Column(name="useful_info", type="text")
     */
    private $usefulInfo;
	
    /**
     * @var string
     *
     * @ORM\Column(name="local_notes", type="text")
     */
    private $localNotes;


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
     * @return EGuidePhoto
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
     * Set what_to_take
     *
     * @param string $whatToTake
     * @return BeforeYouGo
     */
    public function setWhatToTake($whatToTake)
    {
        $this->what_to_take = $whatToTake;
    
        return $this;
    }

    /**
     * Get what_to_take
     *
     * @return string 
     */
    public function getWhatToTake()
    {
        return $this->what_to_take;
    }

    /**
     * Set getting_around
     *
     * @param string $gettingAround
     * @return BeforeYouGo
     */
    public function setGettingAround($gettingAround)
    {
        $this->getting_around = $gettingAround;
    
        return $this;
    }

    /**
     * Get getting_around
     *
     * @return string 
     */
    public function getGettingAround()
    {
        return $this->getting_around;
    }

    /**
     * Set money_tipping
     *
     * @param string $moneyTipping
     * @return BeforeYouGo
     */
    public function setMoneyTipping($moneyTipping)
    {
        $this->money_tipping = $moneyTipping;
    
        return $this;
    }

    /**
     * Get money_tipping
     *
     * @return string 
     */
    public function getMoneyTipping()
    {
        return $this->money_tipping;
    }

    /**
     * Set safety
     *
     * @param string $safety
     * @return BeforeYouGo
     */
    public function setSafety($safety)
    {
        $this->safety = $safety;
    
        return $this;
    }

    /**
     * Get safety
     *
     * @return string 
     */
    public function getSafety()
    {
        return $this->safety;
    }
	
    /**
     * Set usefulInfo
     *
     * @param string $usefulInfo
     * @return BeforeYouGo
     */
    public function setUsefulInfo($usefulInfo)
    {
        $this->usefulInfo = $usefulInfo;
    
        return $this;
    }

    /**
     * Get usefulInfo
     *
     * @return string 
     */
    public function getUsefulInfo()
    {
        return $this->usefulInfo;
    }
	
    /**
     * Set localNotes
     *
     * @param string $localNotes
     * @return BeforeYouGo
     */
    public function setLocalNotes($localNotes)
    {
        $this->localNotes = $localNotes;
    
        return $this;
    }

    /**
     * Get localNotes
     *
     * @return string 
     */
    public function getLocalNotes()
    {
        return $this->localNotes;
    }
}