<?php

namespace Buggl\MainBundle\EventListener;

use Buggl\MainBundle\Event\Mail\MailNotificationEvent;

class MailNotificationListener
{
    private $mailer;

    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }


    public function sendMail( MailNotificationEvent $event )
    {
        $message = $event->getMessage();
        $this->mailer->send($message);
    }
}