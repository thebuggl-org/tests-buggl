<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\LocalAuthor;

/**
 * BugglLocalAuthorToArray
 *
 * @author    Vincent Farly G. Taboada <farly.taboada@goabroad.com>
 * @copyright 2013 (c) Buggl.com
 */
class BugglLocalAuthorToArray
{
    private $router;
    private $entityManager;

    /**
     * Constructor
     * @param Router         $router        []
     * @param EntityMaanager $entityManager []
     */
    public function __construct($router,$entityManager)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
    }

    /**
     * return information of local author in array form
     * @param LocalAuthor $localAuthor
     *
     * @return array
     */
    public function __toArray(  LocalAuthor $localAuthor = null )
    {
        if (is_null($localAuthor)) {
            return null;
        }

        $values = array();

        $values['id'] = $localAuthor->getId();
        $values['name'] = $localAuthor->getName();

        $profile = $localAuthor->getProfile();

        if (is_null($profile)) {
            $values['pic'] = 'bundles/bugglmain/images/profile-big.jpg';
        } else {
            $pic = $profile->getProfilePic();

            if (is_null($pic)) {
                $values['pic'] = 'bundles/bugglmain/images/profile-big.jpg';
            } else {
                $values['pic'] = $profile->getImageWebPath();
            }
        }

        $status = $localAuthor->getStatus();

        if ($status) {
            $text = 'Unsuspend';
        } else {
            $text = 'Suspend';
        }

        $values['action'] = $text;

        $repo = $this->entityManager->getRepository("BugglMainBundle:EGuide");
        $eguideCount = $repo->countByLocalAuthor($localAuthor);
        $dlCount = $repo->findDLSumByLocalAuthor($localAuthor);


        $values['eguideCount'] = $eguideCount;
        $values['dlCount'] = $dlCount;

        $dateJoined = $localAuthor->getDateJoined();
        $values['dateJoined'] = $dateJoined->format('M d, Y');
        // since the implementation for now is 1 - 1
        // change this if neccessary
        $values['city'] = $localAuthor->getLocation()->getCity()->getName();

        $values['viewUrl'] = $this->router
                                  ->generate('buggl_local_author_profile', array(
                                        'slug' => $localAuthor->getSlug()
                                    ));

        $values['slug'] = $localAuthor->getSlug();

        return $values;
    }
}