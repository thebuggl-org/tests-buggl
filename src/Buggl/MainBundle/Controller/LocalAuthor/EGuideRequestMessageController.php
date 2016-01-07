<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Buggl\MainBundle\Entity\LocalAuthor;
use Symfony\Component\HttpFoundation\Response;
use Buggl\MainBundle\Entity\EGuideRequest;
use Symfony\Component\HttpFoundation\RedirectResponse;


class EGuideRequestMessageController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
    const LIMIT = 10;

    public function indexAction(Request $request)
    {
        $page = $request->get('page',1);
        $localAuthor = $this->get('security.context')->getToken()->getUser();
       
    /*  if($localAuthor->getIsLocalAuthor() == 0){
            throw $this->createNotFoundException('You have no permission to access this page.');
        }*/

        $constants = $this->get('buggl_main.constants');
        $limit = self::LIMIT;
        $offset = $limit * ($page - 1);
        $status = array($constants->get('MSG_NEW'));

        $newMessagesCount = $this->getDoctrine()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countMessagesByStatus($localAuthor,$status);
        $newRequestCount = $this->getDoctrine()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countRequestByStatus($localAuthor,$status); 
       $guideRequest = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuideRequest')->findByLocalAuthor($localAuthor,$offset,$limit);
       $threads = $this->getDoctrine()->getEntityManager()
                                       ->getRepository('BugglMainBundle:MessageThreadToUser')
                                       ->findByUserRequest($localAuthor,$status,$offset,$limit);
        $guideRequestcount = count($guideRequest);



        $data = array(
             'messages'         => $guideRequest,
             'threads'          => $threads,
             'activeTab'        => 'guide_request',
             'newMessagesCount' => $newMessagesCount,
             'newRequestCount' => $newRequestCount,
             'page'             => $page,
             'guideRequestcount'=> $guideRequestcount,
             'limit'            => $limit

            );
        return $this->render('BugglMainBundle:LocalAuthor\EGuideRequest:index.html.twig', $data);
    }

public function ShowMessageAction(Request $request)
    {
        $id = $request->get('id');
       // echo $id; die;
        $active = $request->get('type');
        $constants = $this->get('buggl_main.constants');

        $limit = $constants->get('messages_pagination');
        $currentPage = $request->get('currentPage',1);

        $offset =  $currentPage > 0 ? ($currentPage-1) * $limit : 0;
        $localAuthor = $this->get('security.context')->getToken()->getUser();

        $status = array($constants->get('MSG_'.strtoupper($active)));
        //print_r($status); die;
        if($active == 'message'){
            $status[] = $constants->get('MSG_NEW');
        }
        //$localAuthor = $this->get('security.context')->getToken()->getUser();

		if($localAuthor->getIsLocalAuthor() == 0){
			throw $this->createNotFoundException('You have no permission to access this page.');
		}
        $threads = $this->getDoctrine()->getEntityManager()
                                       ->getRepository('BugglMainBundle:MessageThreadToUser')
                                       ->findByUser($localAuthor,$status,$offset,$limit);

        $constants = $this->get('buggl_main.constants');
        $status = array($constants->get('MSG_NEW'));
        $newMessagesCount = $this->getDoctrine()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countMessagesByStatus($localAuthor,$status);
        $newRequestCount = $this->getDoctrine()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countRequestByStatus($localAuthor,$status); 

        $em = $this->getDoctrine()->getEntityManager();
        $messagerequest = $em->getRepository('BugglMainBundle:EGuideRequest')->findOneById($id);


        $messagerequest->setStatus(1);
        $em->persist($messagerequest);
        $em->flush();



        $data = array(
            'request'          => $messagerequest,
            'activeTab'        => 'guide_request',
            'threads'          =>$threads,
            'newMessagesCount' => $newMessagesCount,
            'newRequestCount' => $newRequestCount,
                   );

        return $this->render('BugglMainBundle:LocalAuthor\EGuideRequest:eguideviewmessage.html.twig', $data);
    }

    public function ReplyMessageAction(Request $request)
    {
        $threadId = $request->get('threadId');
        //echo $threadId; die;
        $message = $request->get('message');
         
           // print_r($mail);                          // echo $threadId; die; 
         $sender = $request->get('email'); 
        $messagetype='eguiderequest';
        $user = $this->get('security.context')->getToken()->getUser();
        $mail=$this->getDoctrine()->getEntityManager()
                                       ->getRepository('BugglMainBundle:MessageThreadToUser')
                                       ->findOneByThread($threadId,$user->getId());
                                       
          $this->get('buggl_main.eguide_request_service')->sendEGuideReply($mail,$sender);  
        $messageService = $this->get('buggl_main.message_service');
        $messages = $messageService->reply($threadId,$user,$message,$messagetype);

         //$messageThread=$this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageThread')->getMessagesOfThread($threadId); 
         //$thread=$messageThread->getEguideRequest();
       $data = array(
            
            'messages' => array($messages)
        );
        return $this->render('BugglMainBundle:LocalAuthor\EGuideRequest:messagesList.html.twig', $data);
    }



    public function eGuideRequestModalAction(Request $request)
    {
        $countries = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Country')->findAllCountry();
        $trip_themes = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:TripTheme')->findAllTheme();
        $categories = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Category')->findAllCategory();
        $durations = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Duration')->findAll();
        $good_for = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:GoodFor')->findAll();
        $id = $request->get('id');
        $email = $request->get('email');
        $session = $this->getRequest()->getSession();
        $localAuthor = $this->get('security.context')->getToken()->getUser();
       
       // $localAuthor-
        //echo $email1=$eguidereq->getUser()->getEmail();
       //print_r($localAuthor);
        $data = array(
                'countries'      => $countries,
                'trip_themes'    => $trip_themes,
                'categories'     => $categories,
                'durations'      => $durations,
                'good_for'       => $good_for,
                'local_author_id'=> $id,
                'email'          => $email,
                'localAuthor'   =>$localAuthor
            );

        return $this->render('BugglMainBundle:Frontend\LocalAuthor:eguiderequestmodal.html.twig', $data);

    }

    
    public function messageThreadAction(Request $request)
    {   
        $localAuthor = $this->get('security.context')->getToken()->getUser();
        
       
        $activeTab = $request->get('type');
       // print_r($activeTab); die;
        $messageThreadToUserId = $request->get('messageThreadToUserId');
        //echo $messageThreadToUserId; die;
        
        $securityContext = $this->get('security.context');
        $entityManager = $this->getDoctrine()->getEntityManager();
        $messageThreadToUser = $entityManager->getRepository('BugglMainBundle:MessageThreadToUser')->findOneBy(array('id'=>$messageThreadToUserId, 'user' => $localAuthor));
        
        $constants = $this->get('buggl_main.constants');
        if(is_null($messageThreadToUser) || $messageThreadToUser->getStatus() != $constants->get('MSG_'.strtoupper($activeTab))){
            //echo $this->generateUrl('local_author_eguide'); die;
            return new RedirectResponse($this->generateUrl('local_author_eguide'));
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
        $messageThread=$this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageThread')->getMessagesOfThread($messageThreadToUser->getThread()); 
         $thread=$messageThread->getEguideRequest(); 
         //echo $thread; die;
          $eguide_request=$this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuideRequest')->findOneById($thread); 
         
        $data = array(
            'messages' => $messages,
            'eguide_thread'=>$thread,
            'eguide_request'=>$eguide_request,
            'newMessagesCount' => $newMessagesCount,
            'newRequestCount' => $newRequestCount,
            'messageThreadToUser' => $messageThreadToUser,
            'messageThreadToUserId' => $messageThreadToUserId,
            'activeTab' => $activeTab
        );

        return $this->render('BugglMainBundle:LocalAuthor\EGuideRequest:messageThread.html.twig', $data);
    }
}