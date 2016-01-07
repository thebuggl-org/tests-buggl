<?php

namespace Buggl\MainBundle\Event\Mail;

use Buggl\MainBundle\Event\Mail\MailNotificationEvent;
use Symfony\Component\EventDispatcher\Event;

class DeniedEguideEvent extends Event implements MailNotificationEvent
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
        $subjectKey = 'DENIED_TRAVEL_GUIDE_SUBJECT';

        $templating =  $this->messageBuilder->getTemplating();
        $router = $this->messageBuilder->getRouter();

        $link = $router->generate('update_travel_guide_info',array('travel_guide_id' => $this->travelGuide->getId()),true);
		$link2 = $router->generate('local_author_eguides',array('status' => 'denied'),true);
        $login = $router->generate('login',array(),true);

        $params = array(
            'travelguide' => $this->travelGuide->getPlainTitle(),
            'link' => $link,
			'link2' => $link2,
            'name' => $this->travelGuide->getLocalAuthor()->getName(),
            'login' => $login
        );

        $to = $this->travelGuide->getLocalAuthor()->getEmail();
        $body = $templating->render('BugglMainBundle:Notification:deniedTravelGuide.html.twig',$params);
        $this->messageBuilder->buildMessage( $subjectKey,$to,$body);
    }


    public function getMessage()
    {
        return $this->messageBuilder->getMessage();
    }

}