<?php

namespace Buggl\MainBundle\Service;

class MessageBuilder
{

    private $templating;
    private $router;
    private $constants;

    public function __construct($templating, $router, $constants)
    {
        $this->templating = $templating;
        $this->router = $router;
        $this->constants = $constants;

        $this->message = null;
    }

    public function buildMessage($subjectKey,$to,$body)
    {
        $subject = $this->constants->get($subjectKey);

        if (is_null($subject)) {
            $subject = $subjectKey;
        }

        $this->message = \Swift_Message::newInstance();
        $this->message->setSubject($subject);
        $this->message->setFrom($this->constants->get('BUGGL_EMAIL'));
        $this->message->setTo($to);

        $this->message->setBody($body, 'text/html');

        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getTemplating()
    {
        return $this->templating;
    }

    public function getRouter()
    {
        return $this->router;
    }
}