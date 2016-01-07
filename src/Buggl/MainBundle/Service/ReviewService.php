<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Helper\BugglConstant;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * ReviewService used to save posted review to either local author or travel guide
 *
 * @author Vincent Farly Taboada <farly.taboda@goabroad.com>
 */
class ReviewService
{
    /**
     * @var BugglConstant
     */
    private $constants;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SecurityContext
     */
    private $securityContext;

    /**
     * constructor
     * @param BugglConstant   $constants       []
     * @param EntityManager   $entityManager   []
     * @param SecurityContext $securityContext []
     */
    public function __construct(BugglConstant $constants, EntityManager $entityManager, SecurityContext $securityContext )
    {
        $this->constants = $constants;
        $this->entityManager = $entityManager;
        $this->securityContext = $securityContext;
    }

    /**
     * @param Array  $params
     *
     * @return self
     */
    public function init($params = array())
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Saves the posted review for travel guide
     * @return Array
     */
    public function saveTravelGuideReview()
    {
        $upperLimit = $this->constants->get('rating_upper_limit');
        $lowerLimit = $this->constants->get('rating_lower_limit');
        // $status = $this->constants->get('unviewed_review');
        $status = $this->constants->get('approved_review');
        $rating = $this->params['rating'];

        $rating = $rating > $upperLimit ? $upperLimit : $rating;
        $rating = $rating < $lowerLimit ? $lowerLimit : $rating;

        $content = trim($this->params['content']);
        $travelGuideId = $this->params['travelguide'];

        $travelGuide = $country = $this->entityManager
                                       ->find('BugglMainBundle:EGuide', $travelGuideId);

        if (is_null($travelGuide)) {
            return array('sucess' => false);
        }

        $reviewer = $this->securityContext->getToken()->getUser();

        $purchaseInfo = $this->entityManager
                             ->getRepository('BugglMainBundle:PaypalPurchaseInfo')
                             ->findOneByBuyerAndEguide($reviewer,$travelGuide);

        $allowed = is_null($purchaseInfo) ? false: true;

        if (!$allowed) {
            return array('sucess' => false);
        } else {
            $review = new \Buggl\MainBundle\Entity\TravelGuideReview();

            $review->setReviewer($reviewer)
                   ->setContent($content)
                   ->setDateAdded(new \DateTime(date('Y-m-d H:i:s', time())))
                   ->setStatus($status)
                   ->setRating($rating)
                   ->setEguide($travelGuide);


            $this->entityManager->persist($review);
            $this->entityManager->flush();

            return array('success' => true, 'review' => $review);
        }
    }

    /**
     * Saves the posted review for local author
     * @return Array
     */
    public function saveLocalAuthorReview()
    {
        // $status = $this->constants->get('unviewed_review');
        $status = $this->constants->get('approved_review');

        $content = trim($this->params['content']);
        $localAuthorId = $this->params['local_author'];

        $localAuthor = $country = $this->entityManager
                                       ->find('BugglMainBundle:LocalAuthor', $localAuthorId);

        if (is_null($localAuthor)) {
            return array('sucess' => false);
        }

        $reviewer = $this->securityContext->getToken()->getUser();

        $review = new \Buggl\MainBundle\Entity\LocalAuthorReview();

        $review->setReviewer($reviewer)
               ->setContent($content)
               ->setDateAdded(new \DateTime(date('Y-m-d H:i:s', time())))
               ->setStatus($status)
               ->setLocalAuthor($localAuthor);

        $this->entityManager->persist($review);
        $this->entityManager->flush();

        return array('success' => true, 'review' => $review);
    }
}