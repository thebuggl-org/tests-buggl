<?php

namespace Buggl\MainBundle\Service;

use Doctrine\ORM\EntityManager;

use Buggl\MainBundle\Entity\Message;
use Buggl\MainBundle\Entity\MessageThread;
use Buggl\MainBundle\Entity\MessageToUser;
use Buggl\MainBundle\Entity\MessageThreadToUser;

class GuideShareService
{
    protected $entityManager;
	protected $contants;
	protected $mailer;
	protected $templating;
	protected $router;
	private $siteUrl;

    public function __construct(EntityManager $entityManager, $constants, $mailer, $templating, $router)
    {
        $this->entityManager = $entityManager;
		$this->constants = $constants;
		$this->mailer = $mailer;
		$this->templating = $templating;
		$this->router = $router;
		$this->siteUrl = $this->constants->get('site_url');
    }
	
	public function sendNotification($recipient, $guideInfo)
	{
		$message = \Swift_Message::newInstance();
		$message->setSubject($guideInfo['guide']->getLocalAuthor()->getName().' published a new guide on Buggl');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($recipient);
		$message->setBody($this->templating->render('BugglMainBundle:Notification:guideShareNotification.html.twig', array('guideInfo' => $guideInfo, 'siteUrl' => $this->siteUrl)), 'text/html');
		
		$result = $this->mailer->send($message);
	}
}