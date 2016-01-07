<?php

namespace Buggl\MainBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    private $templating;

    public function __construct($templating)
    {

        $this->templating = $templating;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $response = new Response($this->templating->render('BugglMainBundle:Markup:error.html.twig', array(
                    'exception' => $exception
        )));

        $event->setResponse($response);
    }
}