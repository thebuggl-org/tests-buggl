<?php

namespace Buggl\MainBundle\Event\Mail;

use Buggl\MainBundle\Event\Mail\MailNotificationEvent;
use Symfony\Component\EventDispatcher\Event;

class RepublishEvent extends Event implements MailNotificationEvent
{

    private $messageBuilder;

    public function __construct( $messageBuilder, $eguide, $adminEmail )
    {
        $this->messageBuilder = $messageBuilder;
        $this->message = null;
        $this->travelGuide = $eguide;
		$this->adminEmail = $adminEmail;

        $this->buildMessage();
    }

    private function buildMessage()
    {
		$author = $this->travelGuide->getLocalAuthor();
        $subjectKey = $author->getName().' updated the guide '.$this->travelGuide->getPlainTitle();

        $templating =  $this->messageBuilder->getTemplating();
        $router = $this->messageBuilder->getRouter();

        $params = array(
            'guide' => $this->travelGuide,
			'adminLink' => $router->generate('admin_travel_guides_denied',array(),true)
        );

        $to = $this->adminEmail;
        $body = $templating->render('BugglMainBundle:Notification:republishNotification.html.twig',$params);
        $this->messageBuilder->buildMessage($subjectKey,$to,$body);
    }


    public function getMessage()
    {
        return $this->messageBuilder->getMessage();
    }

}