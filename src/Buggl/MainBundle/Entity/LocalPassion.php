<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LocalPassion
 *
 * @ORM\Table(name="local_passion")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\LocalPassionRepository")
 * @ORM\HasLifecycleCallbacks
 */
class LocalPassion
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
     * @ORM\Column(name="title", type="string", length=250, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="image_filename", type="string", length=250, nullable=true)
     */
    private $imageFilename;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=1000, nullable=false)
     */
    private $content;

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
	 *
     */
    public $file;

	/**
     *
     */
    private $prevImagePath;


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
     * Set title
     *
     * @param string $title
     * @return LocalPassion
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set imageFilename
     *
     * @param string $imageFilename
     * @return LocalPassion
     */
    public function setImageFilename($imageFilename)
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    /**
     * Get imageFilename
     *
     * @return string
     */
    public function getImageFilename()
    {
        return $this->imageFilename;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return LocalPassion
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
     * Set localAuthor
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $localAuthor
     * @return LocalPassion
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

	public function setPreviousImagePath($path)
	{
		$this->prevImagePath = $path;
	}

	public function getImageAbsolutePath()
    {
        return null === $this->imageFilename ? null : $this->getUploadRootDir().'/'.$this->imageFilename;
    }

    public function getImageWebPath()
    {
        return null === $this->imageFilename ? null : $this->getUploadDir().'/'.$this->imageFilename;
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
        return 'uploads/local_interest';
    }

	/**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->imageFilename = $filename.'.'.$this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
	public function upload()
	{
	    if (null === $this->file) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
		if(!@file_exists($this->getUploadRootDir())){
			mkdir($this->getUploadRootDir(),0777,true);
		}

        $this->file->move($this->getUploadRootDir(), $this->imageFilename);

		if(!empty($this->prevImagePath)){
			@unlink($this->prevImagePath);
		}

        unset($this->file);
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