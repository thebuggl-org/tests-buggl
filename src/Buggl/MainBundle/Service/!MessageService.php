<?php

namespace Buggl\MainBundle\Service;

use Doctrine\ORM\EntityManager;

use Buggl\MainBundle\Entity\Message;
use Buggl\MainBundle\Entity\MessageThread;
use Buggl\MainBundle\Entity\MessageToUser;
use Buggl\MainBundle\Entity\MessageThreadToUser;

class MessageService
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

	public function getAllAllowedRecipients($currentUser)
	{
		$users = $this->entityManager->getRepository('BugglMainBundle:User')->findAll();

		$recipients = array();
		foreach($users as $user){
			if(strpos(get_class($user),'AdminUsers') !== false){
				continue;
				$name = $user->getUserName(true).' <admin>';
			}
			else
				$name = $user->getName();

			if($user->getId() != $currentUser->getId())
				$recipients[$user->getId()] = $name;
		}
		return $recipients;
	}

	public function saveMessage($messageData, $sender)
	{ 
		$recipient = $this->entityManager->getRepository('BugglMainBundle:User')->findOneById($messageData['recipient']);
		$users = array($recipient,$sender);
		$key = $this->getThreadKey($users);
		$thread = null;
		$messagetype=$messageData['messagetype'];
		//$this->entityManager->getRepository('BugglMainBundle:MessageThread')->findOneBy(array('usersKey'=>$key));
		$now = new \DateTime(date('Y-m-d H:i:s',time()));

		if(is_null($thread)){
			$thread = new MessageThread();
			$thread->setSubject($messageData['subject']);
			$thread->setUsersKey($key);
			$thread->setDateCreated($now);
			$thread->setDateUpdated($now);
			$thread->setMessagetype($messagetype);
			$thread->setEguideRequest($messageData['eguide_request_id']);
			$this->entityManager->persist($thread);

			foreach($users as $user){
				$messageThreadToUser = new MessageThreadToUser();
				$messageThreadToUser->setThread($thread);
				$messageThreadToUser->setUser($user);
				$messageThreadToUser->setStatus($this->constants->get('MSG_INBOX'));
				$this->entityManager->persist($messageThreadToUser);
			}
		}

		$message = new Message();
		$message->setContent($messageData['message']);
		$message->setDateCreated($now);
		$this->entityManager->persist($message);

		foreach($users as $user){
			$messageToUser = new MessageToUser();
			$messageToUser->setMessage($message);
			$messageToUser->setUser($user);
			$messageToUser->setThread($thread);
			$messageToUser->setSender($sender);
			$messageToUser->setStatus($sender->getId() == $user->getId() ? $this->constants->get('MSG_READ') : $this->constants->get('MSG_NEW'));
			$messageToUser->setNotificationStatus($sender->getId() == $user->getId() ? $this->constants->get('MSG_NOTIF_NO_NEED') : $this->constants->get('MSG_NOTIF_PENDING'));
			$messageToUser->setMessagetype($messagetype);
			$this->entityManager->persist($messageToUser);
		}

		$this->entityManager->flush();

		return $message;
	}

	public function reply($threadId, $sender, $messageString,$messagetype)
	{	
		//$messagetype=$messagetype;
		$now = new \DateTime(date('Y-m-d H:i:s'));
		$thread = $this->entityManager->getRepository('BugglMainBundle:MessageThread')->findOneBy(array('id'=>$threadId));
		$thread->setDateUpdated($now);
		$thread->setMessagetype($messagetype);
		$this->entityManager->persist($thread);
		$message = new Message();
		$message->setContent($messageString);
		$message->setDateCreated($now);
		$this->entityManager->persist($message);

		$messageThreadToUsers = $this->entityManager->getRepository('BugglMainBundle:MessageThreadToUser')->findbyThread($thread);
	
		// connect message to participating users
		foreach($messageThreadToUsers as $messageThreadToUser){
			// transfer user threads with new messages to inbox
			if($messageThreadToUser->getStatus() == $this->constants->get('MSG_ARCHIVED')){
				$messageThreadToUser->setStatus($this->constants->get('MSG_INBOX'));
				$this->entityManager->persist($messageThreadToUser);
			}

			if($sender->getId() != $messageThreadToUser->getUser()->getId()){
				$messageToUser = new MessageToUser();
				$messageToUser->setMessage($message);
				$messageToUser->setUser($messageThreadToUser->getUser());
				$messageToUser->setThread($thread);
				$messageToUser->setSender($sender);
				
				$messageToUser->setMessagetype($messagetype);
				$messageToUser->setStatus($this->constants->get('MSG_NEW'));
				$messageToUser->setNotificationStatus($this->constants->get('MSG_NOTIF_PENDING'));
				$this->entityManager->persist($messageToUser);
			}
		}

		// separate for sender so we can return it as function result
		$messageToUserOfSender = new MessageToUser();
		$messageToUserOfSender->setMessage($message);
		$messageToUserOfSender->setUser($sender);
		$messageToUserOfSender->setThread($thread);
		$messageToUserOfSender->setSender($sender);
		$messageToUserOfSender->setMessagetype($messagetype);
		$messageToUserOfSender->setStatus($this->constants->get('MSG_READ'));
		$messageToUserOfSender->setNotificationStatus($this->constants->get('MSG_NOTIF_NO_NEED'));
		$this->entityManager->persist($messageToUserOfSender);

		$this->entityManager->flush();

		return $messageToUserOfSender;
	}

	public function markMessagesAsRead($messageToUsers, $includeNew = false)
	{
		//Q: Which is more efficient? Querying again for unread messages or use if condition
		foreach($messageToUsers as $messageToUser){
			if($messageToUser->getStatus() == $this->constants->get('MSG_UNREAD') ||
			   ($messageToUser->getStatus() == $this->constants->get('MSG_NEW') && $includeNew)){
				$messageToUser->setStatus($this->constants->get('MSG_READ'));
				$this->entityManager->persist($messageToUser);
			}
		}

		$this->entityManager->flush();

		return $messageToUsers;
	}

	public function markMessagesAsUnRead($messageToUsers)
	{
		//Q: Which is more efficient? Querying again for unread messages or use if condition
		foreach($messageToUsers as $messageToUser){
			if($messageToUser->getStatus() == $this->constants->get('MSG_NEW')){
				$messageToUser->setStatus($this->constants->get('MSG_UNREAD'));
				$this->entityManager->persist($messageToUser);
			}
		}

		$this->entityManager->flush();

		return $messageToUsers;
	}


	public function markThreadsForInbox($messageThreadToUsers)
	{
		//Q: Which is more efficient? Querying again for unread messages or use if condition
		foreach($messageThreadToUsers as $messageThreadToUser){
			//if($messageThreadToUser->getStatus() == $this->constants->get('MSG_NEW')){
				$messageThreadToUser->setStatus($this->constants->get('MSG_INBOX'));
				$this->entityManager->persist($messageThreadToUser);
			//}
		}

		$this->entityManager->flush();

		return $messageThreadToUsers;
	}

	public function inboxThread($messageThreadToUser)
	{
		$messageThreadToUser->setStatus($this->constants->get('MSG_INBOX'));
		$this->entityManager->persist($messageThreadToUser);

		$this->entityManager->flush();

		return $messageThreadToUser;
	}

	public function archiveThread($messageThreadToUser)
	{
		$messageThreadToUser->setStatus($this->constants->get('MSG_ARCHIVED'));
		$this->entityManager->persist($messageThreadToUser);

		$this->entityManager->flush();

		return $messageThreadToUser;
	}

	public function getThreadKey($users)
	{
		$ids = array();

		foreach($users as $user){
			$ids[] = $user->getId();
		}

		sort($ids);
		return implode('-',$ids);
	}
	
	public function sendNotification($messageToUser)
	{
		$messageThreadToUser = $this->entityManager->getRepository('BugglMainBundle:MessageThreadToUser')->findOneBy(array('user'=>$messageToUser->getUser(),'thread'=>$messageToUser->getThread()));
		$types = array(0 => 'inbox',1 => 'inbox',2 => 'archive');
		
		$emailData = array(
			'messageToUser' => $messageToUser,
			'replyLink' => $this->siteUrl.$this->router->generate('local_author_messages_thread',array('type' => $types[$messageThreadToUser->getStatus()],'messageThreadToUserId' => $messageThreadToUser->getId())),
		);

		$message = \Swift_Message::newInstance();
		$message->setSubject('You have a new message from '.$messageToUser->getSender()->getName().' on Buggl');
		$message->setFrom($this->constants->get('BUGGL_EMAIL'));
		$message->setTo($messageToUser->getUser()->getEmail());
		$message->setBody($this->templating->render('BugglMainBundle:Notification:messageNotification.html.twig', $emailData), 'text/html');
		$result = $this->mailer->send($message);

		if($result){
			$messageToUser->setNotificationStatus($this->constants->get('MSG_NOTIF_SENT'));
		}
		else{
			$messageToUser->setNotificationStatus($this->constants->get('MSG_NOTIF_UNSENT'));
		}
		
		$this->entityManager->persist($messageToUser);
		$this->entityManager->flush();
	}
}