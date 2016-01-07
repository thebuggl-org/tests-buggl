<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RotatingFeature
 *
 * @ORM\Table(name="rotating_feature")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\RotatingFeatureRepository")
 * @ORM\HasLifecycleCallbacks
 */
class RotatingFeature
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
     * @ORM\Column(name="photo", type="string", length=200, nullable=false)
     */
    private $photo;

    /**
     * @var \EGuide
     *
     * @ORM\ManyToOne(targetEntity="EGuide")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="eguide_id", referencedColumnName="id")
     * })
     */
    private $eguide;

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
     * Set photo
     *
     * @param string $photo
     * @return RotatingFeature
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set eguide
     *
     * @param \Buggl\MainBundle\Entity\EGuide $eguide
     * @return RotatingFeature
     */
    public function setEguide(\Buggl\MainBundle\Entity\EGuide $eguide = null)
    {
        $this->eguide = $eguide;

        return $this;
    }

    /**
     * Get eguide
     *
     * @return \Buggl\MainBundle\Entity\EGuide
     */
    public function getEguide()
    {
        return $this->eguide;
    }

	public function getImageAbsolutePath()
    {
        return null === $this->photo ? null : $this->getUploadRootDir().'/'.$this->photo;
    }

    public function getImageWebPath()
    {
        return null === $this->photo ? null : $this->getUploadDir().'/'.$this->photo;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/rotating_feature';
    }

	/**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
		$file = $this->getImageAbsolutePath();

        @unlink($file);
    }
}