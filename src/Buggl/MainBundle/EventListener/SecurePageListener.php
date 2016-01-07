<?php

namespace Buggl\MainBundle\EventListener;

use Buggl\MainBundle\Interfaces\BugglSecuredPage;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurePageListener
{

	private $securityContext;

    private $router;

    public function __construct($securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        /*
         * $controller passed can be either a class or a Closure. This is not usual in Symfony2 but it may happen.
         * If it is a class, it comes in array format
         */
        if (!is_array($controller)) {
            return;
        }

        if ($controller[0] instanceof BugglSecuredPage) {
        	if(!$this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
                // throw new AccessDeniedHttpException('Cannot view page');
                $event->setController(function() use ($controller) {
                    // return new RedirectResponse($url);
                    return $controller[0]->forward('BugglMainBundle:Frontend\Main:login');
               });
            }
        }

        return;
    }
}