<?php

namespace Buggl\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EGuide
 *
 * @ORM\Table(name="e_guide")
 * @ORM\Entity(repositoryClass="Buggl\MainBundle\Repository\EGuideRepository")
 */
class EGuide
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
     * @var integer
     *
     * @ORM\Column(name="is_featured_in_home", type="integer", nullable=false)
     */
    private $isFeaturedInHome;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_featured_in_country", type="integer", nullable=false)
     */
    private $isFeaturedInCountry;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_featured_in_profile", type="integer", nullable=false)
     */
    private $isFeaturedInProfile;

    /**
     * @var string
     *
     * @ORM\Column(name="category_names", type="string", length=500, nullable=true)
     */
    private $categoryNames;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=250, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="plain_title", type="string", length=250, nullable=true)
     */
    private $plainTitle;


    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=250, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string", length=200, nullable=true)
     */
    private $subtitle;

    /**
     * @var string
     *
     * @ORM\Column(name="excerpts", type="text", nullable=true)
     */
    private $excerpts;

    /**
     * @var string
     *
     * @ORM\Column(name="overview_intro", type="text", nullable=true)
     */
    private $overview_intro;

     /**
     * @var string
     *
     * @ORM\Column(name="local_secret_intro", type="text", nullable=true)
     */
    private $local_secret_intro;

    /**
     * @var string
     *
     * @ORM\Column(name="before_you_go_intro", type="text", nullable=true)
     */
    private $before_you_go_intro;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="decimal", nullable=true)
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="dl_count", type="integer", nullable=true)
     */
    private $dlCount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    private $dateCreated;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="budget", type="integer", nullable=false)
     */
    private $budget;

    /**
     * @var string
     *
     * @ORM\Column(name="cover_photo", type="string", length=100, nullable=true)
     */
    private $coverPhoto;

    /**
     * @var \Duration
     *
     * @ORM\ManyToOne(targetEntity="Duration")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="duration_id", referencedColumnName="id")
     * })
     */
    private $duration;

    /**
     * @var integer
     *
     * @ORM\Column(name="real_duration", type="integer", nullable=false, options={"default" = 0})
     */
    private $real_duration; 
    
    /**
     * @var \Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
     * })
     */
    private $country;

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
     * @var \TripTheme
     *
     * @ORM\ManyToOne(targetEntity="TripTheme")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="trip_theme_id", referencedColumnName="id")
     * })
     */
    private $tripTheme;

    /**
     * @var \Category
     *
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $category;

    /**
     * @var \Categories
     *
     * @ORM\ManyToMany(targetEntity="Category")
     * @ORM\JoinTable(name="e_guide_to_category",
     *      joinColumns={@ORM\JoinColumn(name="e_guide_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *      )
     */
    private $categories;

    /**
     * @var \City
     *
     * @ORM\ManyToMany(targetEntity="City")
     * @ORM\JoinTable(name="e_guide_to_city",
     *      joinColumns={@ORM\JoinColumn(name="e_guide_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="city_id", referencedColumnName="id")}
     *      )
     */
    private $cities;

    /**
     * @var \Spot
     *
     * @ORM\ManyToMany(targetEntity="Spot")
     * @ORM\JoinTable(name="e_guide_to_spot",
     *      joinColumns={@ORM\JoinColumn(name="e_guide_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="spot_id", referencedColumnName="id")}
     *      )
     */
    private $spots;

    /**
     * @var string
     *
     * @ORM\Column(name="best_time_to_go", type="text", nullable=true)
     */
    private $bestTimeToGo;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_spotlight", type="integer", nullable=false)
     */
    private $isSpotlight;

    /**
     * @var string
     *
     * @ORM\Column(name="spotlight_photo", type="string", length=100, nullable=true)
     */
    private $spotlightPhoto;

    /**
     * @var \GoodFor
     *
     * @ORM\ManyToMany(targetEntity="GoodFor")
     * @ORM\JoinTable(name="e_guide_to_good_for",
     *      joinColumns={@ORM\JoinColumn(name="eguide_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="good_for_id", referencedColumnName="id")}
     *      )
     */
    private $goodFor;

    /**
     * @var \Locations
     *
     * @ORM\OneToMany(targetEntity="EGuideLocation", mappedBy="e_guide")
     **/
    private $locations;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_updated", type="datetime", nullable=true)
     */
    private $dateUpdated;


    /**
     *
     *
     */
    private $hasSpots = false;


    /**
     * @var text
     * @ORM\Column(name="free_search", type="text", nullable=true)
     */
    private $freeSearch;

    /**
     * @var string
     *
     * @ORM\Column(name="pdf_filename", type="string", length=200, nullable=true)
     */
    private $pdfFilename;

    /**
     * @var string
     *
     * @ORM\Column(name="html_filename", type="string", length=200, nullable=true)
     */
    private $htmlFilename;

    /**
     * @var integer
     *
     * @ORM\Column(name="pdf_page_count", type="integer", nullable=false)
     */
    private $pdfPageCount;


    /**
     * @var \PaypalPurchaseInfo
     *
     * @ORM\OneToMany(targetEntity="PaypalPurchaseInfo", mappedBy="eguide")
     **/
    private $purchaseInfos;

    /**
     * @var integer
     *
     * @ORM\Column(name="request_user_id", type="integer", nullable=true)
     */
    private $isRequestId;


    public function __construct()
    {
        $this->price = 0.00;
        $this->isFeaturedInHome = 0;
		$this->isFeaturedInProfile = 0;
        $this->isFeaturedInCountry = 0;
        $this->dlCount = 0;
        $this->isSpotlight = 0;
        $this->pdfPageCount = 0;
        $this->isRequestId = 0;
        $this->freeSearch = "";
        $this->goodFor = array();
        $this->categories = array();
        $this->locations = array();
        $this->purchaseInfos = array();
    }

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
     * Set isFeaturedInHome
     *
     * @param integer $isFeaturedInHome
     * @return EGuide
     */
    public function setIsFeaturedInHome($isFeaturedInHome)
    {
        $this->isFeaturedInHome = $isFeaturedInHome;

        return $this;
    }

    /**
     * Get isFeaturedInHome
     *
     * @return integer
     */
    public function getIsFeaturedInHome()
    {
        return $this->isFeaturedInHome;
    }

    /**
     * Set isFeaturedInCountry
     *
     * @param integer $isFeaturedInCountry
     * @return EGuide
     */
    public function setIsFeaturedInCountry($isFeaturedInCountry)
    {
        $this->isFeaturedInCountry = $isFeaturedInCountry;

        return $this;
    }

    /**
     * Get isFeaturedInCountry
     *
     * @return integer
     */
    public function getIsFeaturedInCountry()
    {
        return $this->isFeaturedInCountry;
    }

    /**
     * Set isRequestId
     *
     * @param integer $isRequestId
     * @return EGuide
     */
    public function setisRequestId($isRequestId)
    {
       
        $this->isRequestId = $isRequestId;

        return $this;
    }

    /**
     * Get isRequestId
     *
     * @return integer
     */
    public function getisRequestId()
    {
        return $this->isRequestId;
    }


    /**
     * Set isFeaturedInProfile
     *
     * @param integer $isFeaturedInHome
     * @return EGuide
     */
    public function setIsFeaturedInProfile($isFeaturedInProfile)
    {
        $this->isFeaturedInProfile = $isFeaturedInProfile;

        return $this;
    }

    /**
     * Get isFeaturedInProfile
     *
     * @return integer
     */
    public function getIsFeaturedInProfile()
    {
        return $this->isFeaturedInProfile;
    }

    /**
     * Set categoryNames
     *
     * @param string $categoryNames
     * @return EGuide
     */
    public function setCategoryNames($categoryNames)
    {
        $this->categoryNames = $categoryNames;

        return $this;
    }

    /**
     * Get categoryNames
     *
     * @return string
     */
    public function getCategoryNames()
    {
        return $this->categoryNames;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return EGuide
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
     * Set plainTitle
     *
     * @param string $plainTitle
     * @return EGuide
     */
    public function setPlainTitle($plainTitle)
    {
        $this->plainTitle = $plainTitle;

        return $this;
    }

    /**
     * Get plainTitle
     *
     * @return string
     */
    public function getPlainTitle()
    {
        return $this->plainTitle;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return EGuide
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set subtitle
     *
     * @param string $subtitle
     * @return EGuide
     */
    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Get subtitle
     *
     * @return string
     */
    public function getSubtitle()
    {
        return $this->subtitle;
    }

    /**
     * Set excerpts
     *
     * @param string $excerpts
     * @return EGuide
     */
    public function setExcerpts($excerpts)
    {
        $this->excerpts = $excerpts;

        return $this;
    }

    /**
     * Get excerpts
     *
     * @return string
     */
    public function getExcerpts()
    {
        return $this->excerpts;
    }

    /**
     * Set overview_intro
     *
     * @param string $overview_intro
     * @return EGuide
     */
    public function setOverviewIntro($overview_intro)
    {
        $this->overview_intro = $overview_intro;

        return $this;
    }

    /**
     * Get overview_intro
     *
     * @return string
     */
    public function getOverviewIntro()
    {
        return $this->overview_intro;
    }

    /**
     * Set local_secret_intro
     *
     * @param string $local_secret_intro
     * @return EGuide
     */
    public function setLocalSecretIntro($local_secret_intro)
    {
        $this->local_secret_intro = $local_secret_intro;

        return $this;
    }

    /**
     * Get local_secret_intro
     *
     * @return string
     */
    public function getLocalSecretIntro()
    {
        return $this->local_secret_intro;
    }

    /**
     * Set before_you_go_intro
     *
     * @param string $before_you_go_intro
     * @return EGuide
     */
    public function setBeforeYouGoIntro($before_you_go_intro)
    {
        $this->before_you_go_intro = $before_you_go_intro;

        return $this;
    }

    /**
     * Get overview_intro
     *
     * @return string
     */
    public function getBeforeYouGoIntro()
    {
        return $this->before_you_go_intro;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return EGuide
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set dlCount
     *
     * @param integer $dlCount
     * @return EGuide
     */
    public function setDlCount($dlCount)
    {
        $this->dlCount = $dlCount;

        return $this;
    }

    /**
     * Get dlCount
     *
     * @return integer
     */
    public function getDlCount()
    {
        return $this->dlCount;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     * @return EGuide
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return EGuide
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set budget
     *
     * @param integer $budget
     * @return EGuide
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return integer
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set coverPhoto
     *
     * @param string $coverPhoto
     * @return EGuide
     */
    public function setCoverPhoto($coverPhoto)
    {
        $this->coverPhoto = $coverPhoto;

        return $this;
    }

    /**
     * Get coverPhoto
     *
     * @return string
     */
    public function getCoverPhoto()
    {
        return $this->coverPhoto;
    }

    /**
     * Set duration
     *
     * @param \Buggl\MainBundle\Entity\Duration $duration
     * @return EGuide
     */
    public function setDuration(\Buggl\MainBundle\Entity\Duration $duration = null)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return \Buggl\MainBundle\Entity\Duration
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set real_duration
     *
     * @param integer $real_duration
     * @return EGuide
     */
    public function setRealDuration($real_duration)
    {
        $this->real_duration = $real_duration;

        return $this;
    }

    /**
     * Get real_duration
     *
     * @return integer
     */
    public function getRealDuration()
    {
        return $this->real_duration;
    }

    /**
     * Set country
     *
     * @param \Buggl\MainBundle\Entity\Country $country
     * @return EGuide
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
     * Set localAuthor
     *
     * @param \Buggl\MainBundle\Entity\LocalAuthor $localAuthor
     * @return EGuide
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

    /**
     * Set tripTheme
     *
     * @param \Buggl\MainBundle\Entity\TripTheme $tripTheme
     * @return EGuide
     */
    public function setTripTheme(\Buggl\MainBundle\Entity\TripTheme $tripTheme = null)
    {
        $this->tripTheme = $tripTheme;

        return $this;
    }

    /**
     * Get tripTheme
     *
     * @return \Buggl\MainBundle\Entity\TripTheme
     */
    public function getTripTheme()
    {
        return $this->tripTheme;
    }

    /**
     * Set category
     *
     * @param \Buggl\MainBundle\Entity\Category $category
     * @return EGuide
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
     * Get cities
     *
     * @return array \Buggl\MainBundle\Entity\City
     */
    public function getCities()
    {
        return $this->cities;
    }

    /**
     * Get spots
     *
     * @return array \Buggl\MainBundle\Entity\Spot
     */
    public function getSpots()
    {
        return $this->spots;
    }

    public function getHasSpots()
    {
        return (count($this->spots)) ? true : false;
    }

    /**
     * Set bestTimeToGo
     *
     * @param string $bestTimeToGo
     * @return EGuide
     */
    public function setBestTimeToGo($bestTimeToGo)
    {
        $this->bestTimeToGo = $bestTimeToGo;

        return $this;
    }

    /**
     * Get plainTitle
     *
     * @return string
     */
    public function getBestTimeToGo()
    {
        return $this->bestTimeToGo;
    }

    /**
     * Set isSpotlight
     *
     * @param integer $isSpotlight
     * @return EGuide
     */
    public function setIsSpotlight($isSpotlight)
    {
        $this->isSpotlight = $isSpotlight;

        return $this;
    }

    /**
     * Get isSpotlight
     *
     * @return integer
     */
    public function getIsSpotlight()
    {
        return $this->isSpotlight;
    }

    /**
     * Set spotlightPhoto
     *
     * @param string $spotlightPhoto
     * @return EGuide
     */
    public function setSpotlightPhoto($spotlightPhoto)
    {
        $this->spotlightPhoto = $spotlightPhoto;

        return $this;
    }

    /**
     * Get spotlightPhoto
     *
     * @return string
     */
    public function getSpotlightPhoto()
    {
        return $this->spotlightPhoto;
    }

	public function getSpotlightPhotoAbsolutePath()
    {
        return (null === $this->spotlightPhoto || '' === $this->spotlightPhoto) ? null : __DIR__.'/../../../../web/uploads/spotlight/'.$this->spotlightPhoto;
    }

    public function getSpotlightPhotoWebPath()
    {
        return (null === $this->spotlightPhoto || '' === $this->spotlightPhoto ) ? null : 'uploads/spotlight/'.$this->spotlightPhoto;
    }

    /**
     * Get goodFor
     *
     * @return \Buggl\MainBundle\Entity\GoodFor
     */
    public function getGoodFor()
    {
        return $this->goodFor;
    }

    /**
     *
     *
     */
    public function getGoodForIds()
    {
        $ids = array();
        if(count($this->goodFor))
        {
            foreach($this->goodFor as $goodFor)
            {
                $ids[] = $goodFor->getId();
            }
        }
        return $ids;
    }

    /**
     * Get categories
     *
     * @return array \Buggl\MainBundle\Entity\Categories
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     *
     *
     */
    public function getCategoryIds()
    {
        $ids = array();
        if(count($this->categories))
        {
            foreach($this->categories as $category)
            {
                $ids[] = $category->getId();
            }
        }
        return $ids;
    }

     /**
     * Get
     *
     * @return array \Buggl\MainBundle\Entity\EGuideLocation
     */
    public function getLocations()
    {
        return $this->locations;
    }

     /**
     * Set $freeSearch
     *
     * @param string $freeSearch
     * @return EGuide
     */
    public function setFreeSearch($freeSearch)
    {
        $this->freeSearch = $freeSearch;

        return $this;
    }

    /**
     * Get freeSearch
     *
     * @return string
     */
    public function getFreeSearch()
    {
        return $this->freeSearch;
    }
	
    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     * @return EGuide
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

     /**
     * Set $pdfFilename
     *
     * @param string $pdfFilename
     * @return EGuide
     */
    public function setPdfFilename($pdfFilename)
    {
        $this->pdfFilename = $pdfFilename;

        return $this;
    }

    /**
     * Get pdfFilename
     *
     * @return string
     */
    public function getPdfFilename()
    {
        return $this->pdfFilename;
    }

    /**
     * Set $htmlFilename
     *
     * @param string $htmlFilename
     * @return EGuide
     */
    public function setHtmlFilename($htmlFilename)
    {
        $this->htmlFilename = $htmlFilename;

        return $this;
    }

    /**
     * Get htmlFilename
     *
     * @return string
     */
    public function getHtmlFilename()
    {
        return $this->htmlFilename;
    }

    /**
     * Set pdfPageCount
     *
     * @param integer $isFeaturedInHome
     * @return EGuide
     */
    public function setPdfPageCount($pdfPageCount)
    {
        $this->pdfPageCount = $pdfPageCount;

        return $this;
    }

    /**
     * Get pdfPageCount
     *
     * @return integer
     */
    public function getPdfPageCount()
    {
        return $this->pdfPageCount;
    }

    /**
     * Get purchaseInfos
     *
     * @return array \Buggl\MainBundle\Entity\PaypalPurchaseInfo
     */
    public function getPurchaseInfos()
    {
        return $this->purchaseInfos;
    }

    public function getPurchaseCount()
    {
        return count($this->purchaseInfos);
    }
}