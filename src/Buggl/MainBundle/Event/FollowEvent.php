<?php

namespace Buggl\MainBundle\Event;

use Buggl\MainBundle\Entity\LocalAuthor;
use Symfony\Component\EventDispatcher\Event;


class FollowEvent extends Event
{

    private $follower;
    private $following;
    private $action;

    public function __construct(LocalAuthor $follower, LocalAuthor $following, $action = 0)
    {
        $this->follower = $follower;
        $this->following = $following;
        $this->action = $action;
    }

    public function getFollowing()
    {
        return $this->following;
    }

    public function getFollower()
    {
        return $this->follower;
    }

    public function getAction()
    {
        return $this->action;
    }
}