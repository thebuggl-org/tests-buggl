<?php

namespace Buggl\MainBundle\Event\Mail;

interface MailNotificationEvent
{
    public function getMessage();
}