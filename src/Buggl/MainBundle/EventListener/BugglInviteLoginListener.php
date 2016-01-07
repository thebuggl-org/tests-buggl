<?php

namespace Buggl\MainBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * BugglInviteLoginListener class checks if buggl token invite exists
 *
 * @author    Vincent Farly Taboada <farly.taboada@goabroad.com>
 * @copyright 2013 May 28 (c) Buggl.com
 */
class BugglInviteLoginListener
{
    /**
     * @var Router
     *
     */
    private $router;

    /**
     * Constructor
     * @param Router $router
     */
    public function __construct($router)
    {
        $this->router = $router;
    }

    /**
     * Method actually invoked upon dispatching an event "kernel.request"
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $request = $event->getRequest();
        $session = $request->getSession();

		$route = $request->attributes->get('_route');

        $exemptUrl = $route === 'buggl_beta_login' || $route === 'buggl_static_terms' || $route === 'request_beta_token' || $route === 'eguide_preview' ?
                        true: false;

        if (!($session->has('buggl_beta_authenticated') || $exemptUrl)) {
            $this->redirectToBetaAuthentication($event);
        } else {
            return;
        }
    }

    /**
     * Method redirects unauthorized user to beta login
     * @param GetResponseType $event
     */
    private function redirectToBetaAuthentication($event)
    {
        $url = $this->router->generate('buggl_beta_login');

        $event->setResponse(new RedirectResponse($url));
    }
}