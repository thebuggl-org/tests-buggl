<?php

namespace Buggl\MainBundle\Event\Mail;

use Buggl\MainBundle\Event\Mail\MailNotificationEvent;
use Symfony\Component\EventDispatcher\Event;

class ContactUsEvent extends Event implements MailNotificationEvent
{

    private $messageBuilder;

    public function __construct($messageBuilder, $contactUs)
    {
        $this->messageBuilder = $messageBuilder;
        $this->message = null;
        $this->contactUs = $contactUs;

        $this->buildMessage();
    }

    private function buildMessage()
    {
        $subjectKey = 'CONTACT_US_SUBJECT';

        $templating =  $this->messageBuilder->getTemplating();
        $router = $this->messageBuilder->getRouter();

        // $link = $router->generate('update_travel_guide_info',array('travel_guide_id' => $this->travelGuide->getId()),true);

        $params = array(
        );

        $to = $this->contactUs->getEmail();
        $body = $templating->render('BugglMainBundle:Notification:contactUsMail.html.twig',$params);
        $this->messageBuilder->buildMessage($subjectKey, $to, $body);
    }


    public function getMessage()
    {
        return $this->messageBuilder->getMessage();
    }

}