<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Service\EntityRepositoryService;
use Buggl\MainBundle\Entity\LocalAuthor;
/**
 * FollowService
 *
 * @author    Vincent Farly Taboada <farly.taboada@goabroad.com>
 * @copyright 2013 (c) Buggl.com
 */
class FollowService
{
    /**
     *
     * @var EntityRepositoryService
     */
    private $service;

    /**
     *
     * @var LocalAuthor
     */
    private $object;

    /**
     * constructor: assigns instance variable $service
     * @param EntityRepositoryService $entityRepositoryService
     */
    public function __construct(EntityRepositoryService $entityRepositoryService)
    {
        $this->service = $entityRepositoryService;
    }
    /**
     * sets the instance variable object
     * @param LocalAuthor $localAuthor
     *
     * @return FollowService
     */
    public function init(LocalAuthor $localAuthor)
    {
        $this->object = $localAuthor;

        $this->follow = $this->service
                             ->getRepository('BugglMainBundle:Follower')
                             ->findOneBy(array('localAuthor' => $localAuthor));

        return $this;
    }

    /**
     * Counts the followers of instance variable object
     * @return int
     */
    public function countFollowers()
    {
        $followers = json_encode(array());
        if (!is_null($this->follow)) {
            $followers = $this->follow->getFollower();
        }

        return count(json_decode($followers),true);
    }

    /**
     * Counts the number of local author being followed by object
     * @return int
     */
    public function countFollowing()
    {
        if (!is_null($this->follow)) {
            $following = $this->follow->getFollowing();
        } else {
            $following = json_encode(array());
        }


        return count(json_decode($following),true);
    }

    /**
     * checks if follower is following the given slug
     * @param LocalAuthor $follower follower to slug
     * @param string      $slug     being followed
     *
     * @return boolean               true if following false otherwise
     */
    public function isFollowing(LocalAuthor $follower, $slug = '')
    {
        $localAuthor = $this->service
            ->getRepository('BugglMainBundle:LocalAuthor')
            ->findOneBy(array('slug' => $slug));

        $follow = $this->service->getRepository('BugglMainBundle:Follower')
                                ->findOneBy(array('localAuthor' => $follower));

        if (is_null($follow)) {
            return false;
        }

        $following = json_decode($follow->getFollowing(), true);

        return in_array($localAuthor->getId(), $following);

    }

    /**
     * this was never used
     * @param string $slug follower
     *
     * @return boolean     returns if follower.
     * @deprecated was never used. maybe this will be removed.
     */
    public function isFollower($slug)
    {

    }
}