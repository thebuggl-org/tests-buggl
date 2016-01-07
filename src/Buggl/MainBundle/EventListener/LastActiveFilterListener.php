<?php

namespace Buggl\MainBundle\EventListener;

// use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * LastActiveFilter post filter for saving last active of a user
 *
 * @author    Vincent Farly G. Taboada <farly.taboada@goabroad.com>
 * @copyright 2013 (c) Buggl.com
 */
class LastActiveFilterListener
{
    /**
     * @var SecurityContext
     */
    private $securityContext;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Constructor
     *
     * @param SecurityContext $securityContext for getting the current user
     * @param EntityManager   $entityManager   for saving info
     *
     * @return LastActiveFilterListener
     */
    public function __construct($securityContext, $entityManager)
    {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
    }

    /**
     * Method actually invoked upon dispatching an event "kernel.response"
     *
     * @param FilterResponseEvent $event
     *
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {

        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $token = $this->securityContext->getToken();

        if ( $token instanceOf UsernamePasswordToken AND $token->isAuthenticated()) {
            $user = $token->getUser();
            $user->setLastActive(new \DateTime(date('Y-m-d H:i:s', time())));

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }
}