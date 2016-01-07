<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\HttpFoundation\RedirectResponse;


/**
 * Class related to messages controller
 *
 * @author    Vincent Farly Taboada <farly.taboada@goabroad.com>
 * @copyright 2013 (c) Buggl.com
 *
 * @version   Release: April 2013
 */
class MessagesController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
    /**
     * @var limit per page
     */
    const LIMIT = 5;

    /**
     * controller for viewing of messages
     * @return Symfony\Component\HttpFoundation\Response shows the template
     */
    public function indexAction()
    {
        $data = array(
                'limit' => self::LIMIT,
                'status' => 'messages'
            );

        return $this->render('BugglMainBundle:Admin/Messages:messages.html.twig', $data);
    }

    /**
     * controller for viewing of guide requests
     * @param Request $request
     *
     * @return Response           shows the template/html page
     *
     * @author Cris Casas | Scott Amor <gapinterns> may 2013
     */
    public function guideRequestAction(Request $request)
    {
        $page = $request->get('page', 1);

        $offset= self::LIMIT * ($page-1);

        $guideRequest = $this->getDoctrine()->getEntityManager()
                             ->getRepository('BugglMainBundle:EGuideRequest')
                             ->findByLocalAuthor(null, $offset, self::LIMIT);
        $guideRequestcount=count($guideRequest);
        $quotient = $guideRequestcount/self::LIMIT;
        $lastpage = $quotient;
        $modulo = $guideRequestcount%self::LIMIT;

        if ($modulo!=0) {
            $lastpage=$quotient+1;
        }

        $data = array(
            'limit' => self::LIMIT,
            'page' => $page,
            'guideRequestcount' => $guideRequestcount,
            'guideRequests' => $guideRequest,
            'status' => 'guiderequest',
        );

        if ($page > $lastpage) {
            return $this->redirect($this->generateUrl('admin_guide_request_message'));
        }

        return $this->render('BugglMainBundle:Admin/Messages:guiderequest.html.twig', $data);
    }

    /**
     * fetch more messages: invoked via ajax
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function fetchUnreadMessageAction(Request $request)
    {
        $page = $request->get('page', 1);
        $length = 30;

        $status = $this->get('buggl_main.constants')
                       ->get('MSG_READ');

        $messages = $this->get('buggl_main.entity_repository')
                         ->getRepository('BugglMainBundle:MessageToUser')
                         ->findAllFilterByExcludingStatus($status, self::LIMIT, $page);

        $count = count($messages);

        $data = array();
        foreach ($messages as $message) {
            $string = $message->getMessage()->getContent();
            // if (strlen($string) > $length) {
            //     $string = substr($string, 0, strpos(wordwrap($string, $length), "\n")).'...';
            // }

            $data[] = array(
                    'recipient' => $message->getUser()->getName(),
                    'message' => $string,
                    'subject' => $message->getThread()->getSubject(),
                    'sender' => $message->getSender()->getName(),
                    'id' => $message->getId()
                );
        }

        $response = array('count'=> $count, 'data' => $data);

        return new JsonResponse($response, 200);
    }

    /**
     * changes the status of a message: via ajax
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function markAsReadAction(Request $request)
    {
        $messageId = $request->get('id');

        $status = $this->get('buggl_main.constants')->get('MSG_READ');

        $entityManager = $this->getDoctrine()->getEntityManager();
        $message = $entityManager->find('BugglMainBundle:MessageToUser', $messageId);

        if (!is_null($message)) {
            $message->setStatus($status);
            $entityManager->flush();
            $success = true;
        } else {
            $success = false;
        }

        return new JsonResponse(array('success' => $success), 200);
    }

    /**
     * fetch location request: countries
     * @param Request $request
     *
     * @return Response shows the html page
     *
     * @author Cris Casas | Scott Amor <gapinterns> may 2013
     */
    public function fetchLocalRequestMessageAction(Request $request)
    {
        $page = $request->get('page', 1);

        $offset= self::LIMIT *($page-1);

        $status = $this->get('buggl_main.constants')
                       ->get('REQUESTED_COUNTRY');

        $countries = $this->getDoctrine()->getEntityManager()
                          ->getRepository('BugglMainBundle:Country')
                          ->findAllByRequestedStatus($status, $offset, self::LIMIT);

        $countrycount = count($countries);
        $quotient = $countrycount/self::LIMIT;
        $lastpage = $quotient;

        $modulo = $countrycount%self::LIMIT;
        if ($modulo!=0) {
            $lastpage=$quotient+1;
        }

        $data = array();
        foreach ($countries as $country) {

            $result = $this->getDoctrine()
                           ->getRepository('BugglMainBundle:EGuideRequest')
                           ->countByRequestedCountry($country);

            $count = count($result);

            $data[] = array(
                    'country' => $country->getName(),
                    'count' => $count,

                );
        }

        if ($page > $lastpage) {
            return $this->redirect($this->generateUrl('admin_local_request_message'));
        }

        return $this->render('BugglMainBundle:Admin/Messages:localrequests.html.twig',
            array(
                'data' => $data,
                'page' => $page,
                'countrycount' => $countrycount,
                'limit' => self::LIMIT,
                'lastpage' => $lastpage
            ));
    }

    /**
     * upates the country that has been requested
     * @param Request $request
     *
     * @return Response           Redirects to location request page
     *
     * @author Cris Casas | Scott Amor <gapinterns> may 2013
     */
    public function updateLocalRequestMessageAction(Request $request)
    {

        $name = $request->get('country');

        $entityManager = $this->getDoctrine()->getEntityManager();

        $country = $entityManager->getRepository("BugglMainBundle:Country")
                      ->findOneByName($name);

        $status = $this->get('buggl_main.constants')->get('APPROVED_COUNTRY');

        $country->setStatus($status);
        $entityManager->flush();

        return $this->redirect($this->generateUrl('admin_local_request_message'));
    }

    /**
     * controller for page of contact us
     * @param Request $request
     *
     * @return Response           shows the template/page
     */
    public function contactUsAction(Request $request)
    {
        return $this->render('BugglMainBundle:Admin/Messages:contactUs.html.twig',
            array(
                'status' => 'contactus',
                'limit' => self::LIMIT
            ));
    }

    /**
     * fetch all contact us messages arranged by status and date
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function fetchContactUsAction(Request $request)
    {
        $page = $request->get('page', 1);
        $limit = self::LIMIT;

        $repository = $this->get('buggl_main.entity_repository')
                           ->getRepository('BugglMainBundle:ContactUs');

        $messages = $repository->findAll($limit, $page);

        $data = is_null($messages) ? null : array();

        foreach ($messages as $message) {
            $data[] = array(
                'message' => $message->getComment(),
                'date' => date('F d, Y', $message->getDateContacted()->getTimestamp()),
                'email' => $message->getEmail()
            );
        }

        $response = array('data'=>$data,'count'=>count($messages));

        return new JsonResponse($response, 200);
    }
}