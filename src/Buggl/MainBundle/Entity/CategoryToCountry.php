<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CategoryToCountry
 *
 * @ORM\Table(name="category_to_country")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\CategoryToCountryRepository")
 */
class CategoryToCountry
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
     * @var \CategoryPhoto
     *
     * @ORM\ManyToOne(targetEntity="CategoryPhoto")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_to_country_photo_id", referencedColumnName="id")
     * })
     */
    private $categoryToCountryPhoto;

    /**
     * @var \Country
     *
     * @Assert\NotNull(message="select country")
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;

    /**
     * @var \Category
     *
     * @Assert\NotNull(message="select category")
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;



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
     * Set categoryToCountryPhoto
     *
     * @param \Buggl\MainBundle\Entity\CategoryPhoto $categoryToCountryPhoto
     * @return CategoryToCountry
     */
    public function setCategoryToCountryPhoto(\Buggl\MainBundle\Entity\CategoryPhoto $categoryToCountryPhoto = null)
    {
        $this->categoryToCountryPhoto = $categoryToCountryPhoto;

        return $this;
    }

    /**
     * Get categoryToCountryPhoto
     *
     * @return \Buggl\MainBundle\Entity\CategoryPhoto
     */
    public function getCategoryToCountryPhoto()
    {
        return $this->categoryToCountryPhoto;
    }

    /**
     * Set country
     *
     * @param \Buggl\MainBundle\Entity\Country $country
     * @return CategoryToCountry
     */
    public function setCountry(\Buggl\MainBundle\Entity\Country $country = null)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return \Buggl\MainBundle\Entity\Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set category
     *
     * @param \Buggl\MainBundle\Entity\Category $category
     * @return CategoryToCountry
     */
    public function setCategory(\Buggl\MainBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Buggl\MainBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get imageWebPath
     *
     * @return \Buggl\MainBundle\Entity\Category
     */
    public function getImageWebPath()
    {
        return 'uploads/images/'.$this->categoryToCountryPhoto->getFilename();
    }
}