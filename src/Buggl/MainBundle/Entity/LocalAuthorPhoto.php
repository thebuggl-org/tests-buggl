<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LocalAuthorPhoto
 *
 * @ORM\Table(name="local_author_photo")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\LocalAuthorPhotoRepository")
 */
class LocalAuthorPhoto extends \Buggl\PhotoBundle\Entity\Photos
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
     * @ORM\Column(name="caption", type="text", nullable=false)
     */
    private $caption;

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
     * Set caption
     *
     * @param string $caption
     * @return LocalAuthorPhoto
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set localAuthor
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $localAuthor
     * @return LocalAuthorPhoto
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

    public function getImageWebPath()
    {
        return 'uploads/images/'.$this->getFilename();
    }
}