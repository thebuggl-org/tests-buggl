<?php

namespace Buggl\MainBundle\EventListener;

use Doctrine\ORM\EntityManager;
use Buggl\MainBundle\Event\FollowEvent;
use Buggl\MainBundle\Entity\Follower;

class FollowListener
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function update(FollowEvent $event)
    {
        $follower = $event->getFollower();
        $following = $event->getFollowing();
        $action = $event->getAction();

        $followClassOfFollower = $this->entityManager
                                      ->getRepository('BugglMainBundle:Follower')
                                      ->findOneBy(array('localAuthor' => $follower));

        if(is_null($followClassOfFollower)){
            $followClassOfFollower = new Follower();
            $followClassOfFollower->setLocalAuthor($follower);
            $followClassOfFollower->setFollower(json_encode(array()));
            $followingUser = array();
        }
        else{
            $followingUser = json_decode($followClassOfFollower->getFollowing(),true);
        }

        $followClassOfBeingFollowed = $this->entityManager
                                           ->getRepository('BugglMainBundle:Follower')
                                           ->findOneBy(array('localAuthor' => $following));

        if(is_null($followClassOfBeingFollowed)){
            $followClassOfBeingFollowed = new Follower();
            $followClassOfBeingFollowed->setLocalAuthor($following);
            $followClassOfBeingFollowed->setFollowing(json_encode(array()));
            $followerUser = array();
        }
        else{
            $followerUser = json_decode($followClassOfBeingFollowed->getFollower(),true);
        }

        // actual un/following updates the follower and following //

        ///follow action == 0 <- not following
        if(!$action){
            $followingUser[] = $following->getId();
            $followerUser[] = $follower->getId();
        }
        // unfollow action == 1 <- following
        else{
            $removeFollowingKey = array_keys($followingUser,$following->getId());
            $removeFollowerKey = array_keys($followerUser,$follower->getId());

            if(!empty($removeFollowingKey)){
                unset($followingUser[$removeFollowingKey[0]]);    
            }

            if(!empty($removeFollowerKey)){
                unset($followerUser[$removeFollowerKey[0]]);    
            }
        }

        $followingUser = json_encode($followingUser);
        $followerUser = json_encode($followerUser);

        $followClassOfFollower->setFollowing($followingUser);
        $followClassOfBeingFollowed->setFollower($followerUser);

        $this->entityManager->persist($followClassOfFollower);
        $this->entityManager->persist($followClassOfBeingFollowed);

        $this->entityManager->flush();
    }
}