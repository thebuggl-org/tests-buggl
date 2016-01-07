<?php

namespace Buggl\MainBundle\Twig;

use Buggl\MainBundle\Service\EntityRepositoryService;
use \Buggl\MainBundle\Service\RatingService;

class BugglQueryExtension extends \Twig_Extension
{
    private $service;
    private $ratingService;

    public function __construct(EntityRepositoryService $service, RatingService $ratingService)
    {
        $this->service = $service;
        $this->ratingService = $ratingService;
    }


    public function getFilters()
    {
        return array(
            'profession' => new \Twig_Filter_Method($this, 'profession'),
            'overallRating' => new \Twig_Filter_Method($this, 'overallRating'),
        );
    }

    public function profession(\Buggl\MainBundle\Entity\LocalAuthor $localAuthor)
    {
        $profile = $this->service
                        ->getRepository('BugglMainBundle:Profile')
                        ->getByLocalAuthor($localAuthor,true);


        return $profile->getWork();
    }

    public function overallRating(\Buggl\MainBundle\Entity\EGuide $guide)
    {
        return $this->ratingService->getOverallRating($guide);
    }

    public function getName()
    {
        return 'buggl_query_extension';
    }
}