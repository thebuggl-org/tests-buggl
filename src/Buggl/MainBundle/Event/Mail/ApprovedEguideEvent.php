<?php

namespace Buggl\MainBundle\Event\Mail;

use Buggl\MainBundle\Event\Mail\MailNotificationEvent;
use Symfony\Component\EventDispatcher\Event;

class ApprovedEguideEvent extends Event implements MailNotificationEvent
{

    private $messageBuilder;

    public function __construct( $messageBuilder, $eguide )
    {
        $this->messageBuilder = $messageBuilder;
        $this->message = null;
        $this->travelGuide = $eguide;

        $this->buildMessage();
    }

    private function buildMessage()
    {
        $subjectKey = 'APPROVED_TRAVEL_GUIDE_SUBJECT';

        $templating =  $this->messageBuilder->getTemplating();
        $router = $this->messageBuilder->getRouter();

        // $link = $router->generate('update_travel_guide_info',array('travel_guide_id' => $this->travelGuide->getId()),true);

        $params = array(
            'travelguide' => $this->travelGuide->getPlainTitle(),
            // 'link' => $link,
            'name' => $this->travelGuide->getLocalAuthor()->getName()
        );

        $to = $this->travelGuide->getLocalAuthor()->getEmail();
        $body = $templating->render('BugglMainBundle:Notification:approvedTravelGuide.html.twig',$params);
        $this->messageBuilder->buildMessage( $subjectKey,$to,$body);
    }


    public function getMessage()
    {
        return $this->messageBuilder->getMessage();
    }

}