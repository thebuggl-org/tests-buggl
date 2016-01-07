<?php

namespace Buggl\MainBundle\Event\Mail;

use Buggl\MainBundle\Event\Mail\MailNotificationEvent;
use Symfony\Component\EventDispatcher\Event;

class AccountSuspendedEvent extends Event implements MailNotificationEvent
{

    private $messageBuilder;

    public function __construct($messageBuilder, $localAuthor)
    {
        $this->messageBuilder = $messageBuilder;
        $this->localAuthor = $localAuthor;

        $this->buildMessage();
    }

    private function buildMessage()
    {
        $subjectKey = 'SUSPENDED_ACCOUNT';

        $templating =  $this->messageBuilder->getTemplating();
        $router = $this->messageBuilder->getRouter();

        // $link = $router->generate('update_travel_guide_info',array('travel_guide_id' => $this->travelGuide->getId()),true);

        $params = array(
        );

        $to = $this->localAuthor->getEmail();
        $body = $templating->render('BugglMainBundle:Notification:suspendedAccount.html.twig',$params);
        $this->messageBuilder->buildMessage( $subjectKey,$to,$body);
    }


    public function getMessage()
    {
        return $this->messageBuilder->getMessage();
    }

}