<?php

namespace Buggl\MainBundle\Event\Mail;

use Buggl\MainBundle\Event\Mail\MailNotificationEvent;
use Symfony\Component\EventDispatcher\Event;

class ReviewEvent extends Event implements MailNotificationEvent
{

    private $messageBuilder;

    public function __construct($messageBuilder, $review)
    {
        $this->messageBuilder = $messageBuilder;
        $this->message = null;
        $this->review = $review;

        $this->buildMessage();
    }

    private function buildMessage()
    {
        $subjectKey = 'REVIEW_SUBJECT';

        $templating =  $this->messageBuilder->getTemplating();
        $router = $this->messageBuilder->getRouter();

        // $link = $router->generate('update_travel_guide_info',array('travel_guide_id' => $this->travelGuide->getId()),true);
		if(strpos(get_class($this->review),'LocalAuthorReview') !== false){
			$name = $this->review->getLocalAuthor()->getFirstName();
			$to = $this->review->getLocalAuthor()->getEmail();
		}
		else if(strpos(get_class($this->review),'TravelGuideReview') !== false){
			$name = $this->review->getEguide()->getLocalAuthor()->getFirstName();
			$to = $this->review->getEguide()->getLocalAuthor()->getEmail();
		}

        $params = array(
			'link' => $router->generate('local_author_reviews',array(),true),
			'name' => $name
        );

        $body = $templating->render('BugglMainBundle:Notification:reviewMail.html.twig',$params);
        $this->messageBuilder->buildMessage($subjectKey, $to, $body);
    }

    public function getMessage()
    {
        return $this->messageBuilder->getMessage();
    }

}