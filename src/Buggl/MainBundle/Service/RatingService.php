<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\EGuide;
use Doctrine\ORM\EntityManager;

class RatingService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getOverallRating(EGuide $eguide = null)
    {
        $repository = $this->entityManager->getRepository('BugglMainBundle:TravelGuideReview');

        return round($repository->getOverallRating($eguide));
    }

}