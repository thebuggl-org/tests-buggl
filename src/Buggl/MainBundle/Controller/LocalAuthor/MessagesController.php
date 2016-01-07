<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\MaxLength;
use Symfony\Component\Validator\Constraints\NotBlank;

class MessagesController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{

	public function indexAction(Request $request)
	{
		$activeTab = $request->get('type');
		$currentPage = $request->get('page',1);

		$user = $this->get('security.context')->getToken()->getUser();
		$constants = $this->get('buggl_main.constants');
		$status = array($constants->get('MSG_NEW'));
		$newMessagesCount = $this->getDoctrine()
								 ->getRepository('BugglMainBundle:MessageToUser')
								 ->countMessagesByStatus($user,$status);
		$newRequestCount = $this->getDoctrine()
								 ->getRepository('BugglMainBundle:MessageToUser')
								 ->countRequestByStatus($user,$status);						
		//print_r($activeTab); die;
		$data = array(
					'activeTab' => $activeTab,
					'newMessagesCount' => $newMessagesCount,
					'newRequestCount' => $newRequestCount,
					'currentPage' => $currentPage
				);
		//print_r($data); die;
		return $this->render('BugglMainBundle:LocalAuthor\Messages:messages.html.twig', $data);
	}

	public function createAction(Request $request)
	{
		//Q: Is this needed?

        $securityContext = $this->get('security.context');
        if(!$securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            return new RedirectResponse($this->generateUrl('buggl_homepage'));
        }

		$slug = $request->get('slug');
		$recipient = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:LocalAuthor')->findOneBy(array('slug' => $slug));

		$user = $securityContext->getToken()->getUser();
		$constants = $this->get('buggl_main.constants');
		$status = array($constants->get('MSG_NEW'));
		$newMessagesCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countMessagesByStatus($user,$status);
		$newRequestCount = $this->getDoctrine()
								 ->getRepository('BugglMainBundle:MessageToUser')
								 ->countRequestByStatus($user,$status);	
		// message to self?
		if(!is_null($recipient) && $recipient->getId() == $user->getId()){
			return new RedirectResponse($this->generateUrl('local_author_messages_create'));
		}

		$messageService = $this->get('buggl_main.message_service');
		$builder = $this->createFormBuilder();
		$builder->add('subject', 'text', array('constraints' => array(new MaxLength(array('limit'=>30,'message'=>'Subject too long. Please limit to 30 characters.'))),'required'=>false,'attr' => array('placeholder'=>'Subject')));
		$builder->add('message', 'textarea', array('constraints' => array(new NotBlank(array('message'=>'Please leave a message.')))));
		$builder->add('messagetype', 'hidden', array('data' =>'inbox'));
		$builder->add('eguide_request_id', 'hidden', array('data' =>'0'));
		if(!is_null($recipient)){
			$builder->add('recipient', 'hidden', array('data' => $recipient->getId()));
		}
		else{
			$choices = $messageService->getAllAllowedRecipients($user);
			$builder->add('recipient', 'choice', array('choices' => $choices, 'empty_value' => '', 'attr' => array('data-placeholder' => 'To'),'constraints' => array(new NotNull(array('message'=>'Please specify a recipient.')))));
		}

		$form = $builder->getForm();
		// print_r($user);
		if($request->isMethod('POST')){
			$form->bind($request);
			if($form->isValid()){
				//print_r($form->getData()); die;
				$messageService->saveMessage($form->getData(),$user);
				exec('../app/console buggl:email_message_notification > 2>&1 &');
				return new RedirectResponse($this->generateUrl('local_author_messages',array('type'=>'inbox')));
			}
		}

		$data = array(
			'recipient' => $recipient,
			'form' => $form->createView(),
			'newMessagesCount' => $newMessagesCount,
			'newRequestCount' => $newRequestCount
		);

		return $this->render('BugglMainBundle:LocalAuthor\Messages:createMessage.html.twig', $data);
	}

	public function messageThreadAction(Request $request)
	{    
		$activeTab = $request->get('type');
		//print_r($activeTab); die;
		$messageThreadToUserId = $request->get('messageThreadToUserId');
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		
		$entityManager = $this->getDoctrine()->getEntityManager();
		$messageThreadToUser = $entityManager->getRepository('BugglMainBundle:MessageThreadToUser')->findOneBy(array('id'=>$messageThreadToUserId, 'user' => $localAuthor));
		
		$constants = $this->get('buggl_main.constants');
 		if(is_null($messageThreadToUser) || $messageThreadToUser->getStatus() != $constants->get('MSG_'.strtoupper($activeTab))){
			return new RedirectResponse($this->generateUrl('local_author_messages',array('type'=>$activeTab)));
		}

		$user = $this->get('security.context')->getToken()->getUser();
		$messageService = $this->get('buggl_main.message_service');
		$messages = $entityManager->getRepository('BugglMainBundle:MessageToUser')->getMessagesOfThread($messageThreadToUser);
		$messages = $messageService->markMessagesAsRead($messages);
		$messages = $messageService->markMessagesAsUnRead($messages);
		$messageThreadToUser = $messageService->markMessagesAsUnRead($messageThreadToUser);
		//print_r($messageThreadToUserId); die;
		$status = array($constants->get('MSG_NEW'));
		$newMessagesCount = $entityManager->getRepository('BugglMainBundle:MessageToUser')->countMessagesByStatus($user,$status);
        $newRequestCount = $this->getDoctrine()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countRequestByStatus($user,$status); 
		$data = array(
			'messages' => $messages,
			'newMessagesCount' => $newMessagesCount,
			'newRequestCount' => $newRequestCount,
			'messageThreadToUser' => $messageThreadToUser,
			'activeTab' => $activeTab
		);

		return $this->render('BugglMainBundle:LocalAuthor\Messages:messageThread.html.twig', $data);
	}

	public function replyToMessageAction(Request $request)
	{

		$threadId = $request->get('threadId');
		$message = $request->get('message');
		$messagetype='inbox';
		$user = $this->get('security.context')->getToken()->getUser();
		$messageService = $this->get('buggl_main.message_service');
		$messages = $messageService->reply($threadId,$user,$message,$messagetype);
		//print_r($messages);
		$data = array(
			'messages' => array($messages)
		);
		
		exec('../app/console buggl:email_message_notification > /dev/null 2>&1 &');
		return $this->render('BugglMainBundle:LocalAuthor\Messages:messagesList.html.twig', $data);
	}

	public function messageThreadListAction(Request $request)
	{
		$active = $request->get('type');
		$constants = $this->get('buggl_main.constants');

		$limit = $constants->get('messages_pagination');
		$currentPage = $request->get('currentPage',1);

		$offset =  $currentPage > 0 ? ($currentPage-1) * $limit : 0;
		$localAuthor = $this->get('security.context')->getToken()->getUser();

		$status = array($constants->get('MSG_'.strtoupper($active)));
		//print_r($status); die;
		if($active == 'inbox'){
			$status[] = $constants->get('MSG_NEW');
		}
		$threads = $this->getDoctrine()->getEntityManager()
									   ->getRepository('BugglMainBundle:MessageThreadToUser')
									   ->findByUser($localAuthor,$status,$offset,$limit);
		$messageService = $this->get('buggl_main.message_service');

		$softPageLimit = 8;
		$hardPageLimit = 12;

		$data = array(
			"activeTab" => $active,
			"threads" => $threads,
			'itemLimit' => $limit,
			'currentPage' => $currentPage,
			'softPageLimit' => $softPageLimit,
			'hardPageLimit' => $hardPageLimit,
			'dataUrl' => $this->generateUrl('local_author_messages_pagination',array('type'=>$active))
		);
		//print_r($data);die;
		return $this->render('BugglMainBundle:LocalAuthor/Messages:messageThreadList.html.twig', $data);
	}

	public function archiveAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$messageThreadToUser = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageThreadToUser')->findOneBy(array('id'=>$request->get('messageThreadToUserId'), 'user' => $localAuthor));
 		if(is_null($messageThreadToUser)){
			return new RedirectResponse($this->generateUrl('local_author_messages',array('type'=>'inbox')));
		}

		$messageService = $this->get('buggl_main.message_service');
		$messageThreadToUser = $messageService->archiveThread($messageThreadToUser);

		return new RedirectResponse($this->generateUrl('local_author_messages_thread',array('type'=>'archived','messageThreadToUserId'=>$messageThreadToUser->getId())));
	}

	public function unarchiveAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$messageThreadToUser = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageThreadToUser')->findOneBy(array('id'=>$request->get('messageThreadToUserId'), 'user' => $localAuthor));
 		if(is_null($messageThreadToUser)){
			return new RedirectResponse($this->generateUrl('local_author_messages',array('type'=>'archived')));
		}
		
		$messageService = $this->get('buggl_main.message_service');
		$messageThreadToUser = $messageService->inboxThread($messageThreadToUser);

		return new RedirectResponse($this->generateUrl('local_author_messages_thread',array('type'=>'inbox','messageThreadToUserId'=>$messageThreadToUser->getId())));
	}

	public function checkNewMessageAction(Request $request)
	{
		$entityManager =  $this->getDoctrine()->getEntityManager();
		$constants = $this->get('buggl_main.constants');

		$thread = $entityManager->getRepository('BugglMainBundle:MessageThread')->findOneBy(array('id'=>$request->get('threadId')));
		$user = $entityManager->getRepository('BugglMainBundle:User')->findOneBy(array('id'=>$request->get('userId')));
		$messageToUser = $entityManager->getRepository('BugglMainBundle:MessageToUser')->getLatestMessage($thread,$user,$constants->get('MSG_NEW'));

		$status = array($constants->get('MSG_NEW'));
		if(!is_null($messageToUser)){
			$messageService = $this->get('buggl_main.message_service');
			$messageService->markMessagesAsUnRead(array($messageToUser));
		}
		$newMessagesCount = $entityManager->getRepository('BugglMainBundle:MessageToUser')->countMessagesByStatus($user,$status);

		$data = array(
			'newMessageId' => is_null($messageToUser) ? 0 : $messageToUser->getId(),
			'newMessagesCount' => $newMessagesCount
		);

		return new JsonResponse($data,200);
	}

	public function checkNewThreadMessagesAction(Request $request)
	{
		$entityManager =  $this->getDoctrine()->getEntityManager();
		$user = $this->get('security.context')->getToken()->getUser();

		$constants = $this->get('buggl_main.constants');
		$status = array($constants->get('MSG_NEW'));
		$newMessagesCount = $entityManager->getRepository('BugglMainBundle:MessageToUser')->countMessagesByStatus($user,$status);

		$data = array(
			'newMessagesCount' => $newMessagesCount
		);

		return new JsonResponse($data,200);
	}

	/**
     * check if there is a message
     * @return Response template
     */
    public function checkMessagesAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $constants = $this->get('buggl_main.constants');
		$status = array($constants->get('MSG_NEW'));
		$newMessagesCount = $this->getDoctrine()
								 ->getRepository('BugglMainBundle:MessageToUser')
								 ->countMessagesByStatus($user,$status);

		$data = array(
			'count' => $newMessagesCount
		);

	    return $this->render('BugglMainBundle:LocalAuthor\Messages:messageLink.html.twig',$data);
    }
}