<?php

namespace Buggl\MainBundle\Event\Mail;

use Buggl\MainBundle\Event\Mail\MailNotificationEvent;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event holder for purchasing guide
 *
 * @author vftabooada <farly.taboada@goabroad.com>
 * @copyright 2013 July 17 (c) Buggl.com
 */
class PurchaseEvent extends Event implements MailNotificationEvent
{

    /**
     * builds the message instance (swift message)
     * @var Buggl\MainBundle\Service\MessageBuilder
     */
    private $messageBuilder;

    /**
     * PurchaseInfo Entity
     * @var Buggl\MainBundle\Entity\PurchaseInfo
     */
    private $purchaseInfo;

    public function __construct($messageBuilder, $purchaseInfo)
    {
        $this->messageBuilder = $messageBuilder;
        $this->purchaseInfo = $purchaseInfo;

        $this->buildMessage();
    }

    /**
     * builds the email template
     * @return MessageBuilder
     */
    private function buildMessage()
    {
        $subjectKey = 'SOLD_GUIDE';

        $templating =  $this->messageBuilder->getTemplating();
        $router = $this->messageBuilder->getRouter();

        // $link = $router->generate('update_travel_guide_info',array('travel_guide_id' => $this->travelGuide->getId()),true);

        $params = array(
			'link' => $router->generate('login',array(),true),
			'guide' => $this->purchaseInfo->getEguide()
        );

        $to = $this->purchaseInfo->getSeller()->getEmail();
        $body = $templating->render('BugglMainBundle:Notification:soldGuide.html.twig',$params);
        $this->messageBuilder->buildMessage($subjectKey, $to, $body);
    }

    /**
     * builds the email template
     * @return MessageBuilder
     */
    public function buildMessageForBuyer($download=false)
    {
        $subjectKey = $download? 'DOWNLOAD_GUIDE' : 'PURCHASED_GUIDE';

        $templating =  $this->messageBuilder->getTemplating();
        $router = $this->messageBuilder->getRouter();

        $link = $router->generate('local_author_payments_purchased',array(),true);

        $params = array(
            'link' => $link
        );

        $to = $this->purchaseInfo->getBuyer()->getEmail();
        $body = $templating->render('BugglMainBundle:Notification:purchasedGuide.html.twig',$params);
        $this->messageBuilder->buildMessage($subjectKey, $to, $body);

        return $this;
    }

    /**
     * Swift_Message
     * @return Swift_Message
     */
    public function getMessage()
    {
        return $this->messageBuilder->getMessage();
    }

}