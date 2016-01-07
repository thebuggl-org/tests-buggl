<?php

namespace Buggl\MainBundle\Twig;

class MessageHelperExtension extends \Twig_Extension
{
	private $entityManager;
	private $constants;
	
	public function __construct($entityManager,$constants)
	{
		$this->entityManager = $entityManager;
		$this->constants = $constants;
	}
	
    public function getFilters()
    {
        return array(
			'latestMessage' => new \Twig_Filter_Method($this, 'getLatestMessage'),
			'isRecent' => new \Twig_Filter_Method($this, 'isRecent'),
			'addOpenClass' => new \Twig_Filter_Method($this, 'addOpenClass'),
        );
    }
	
	public function getLatestMessage($messageThreadToUser)
	{
		$message = $this->entityManager->getRepository('BugglMainBundle:MessageToUser')->getLatestMessage($messageThreadToUser->getThread(),$messageThreadToUser->getUser());
		
		return $message;
	}
	
	public function isRecent($dateTime)
	{
		$timeElapsed = '';
		$diff = $dateTime->diff(new \DateTime(date('Y-m-d H:i:s')));
		
		if($diff->m > 0 || $diff->d > 0 || $diff->h > 0){
			return false;
		}
			
		return true;
	}
	
	public function addOpenClass($messageToUser, $class)
	{
		if($messageToUser->getStatus() == $this->constants->get('MSG_UNREAD')){
			return $class;
		}
		
		if($this->isRecent($messageToUser->getMessage()->getDateCreated())){
			return $class;
		}
		
		$latestMessageToUser = $this->entityManager->getRepository('BugglMainBundle:MessageToUser')->getLatestMessage($messageToUser->getThread(),$messageToUser->getUser());
		if($latestMessageToUser->getId() == $messageToUser->getId()){
			return $class;
		}
		
		return '';
	}

    public function getName()
    {
        return 'buggl_message_helper_extension';
    }
}