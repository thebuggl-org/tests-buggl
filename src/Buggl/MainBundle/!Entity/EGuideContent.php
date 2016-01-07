<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EGuideContent
 *
 * @ORM\Table(name="e_guide_content")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\EGuideContentRepository")
 */
class EGuideContent
{

    const OVERVIEW_TYPE = 1, BEFORE_YOU_GO_TYPE = 2, ABOUT_THE_AUTHOR_TYPE = 3;
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
     * @ORM\ManyToOne(targetEntity="EGuide")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="e_guide_id", referencedColumnName="id")
     * })
     **/
    private $e_guide;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_added", type="datetime")
     */
    private $date_added;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="`order`", type="smallint")
     */
    private $order;


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
     * Set content
     *
     * @param string $content
     * @return EGuideContent
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date_added
     *
     * @param \DateTime $dateAdded
     * @return EGuideContent
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;

        return $this;
    }

    /**
     * Get date_added
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return EGuideContent
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set order
     *
     * @param integer $order
     * @return EGuideOverview
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }
}
