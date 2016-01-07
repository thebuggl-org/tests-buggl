<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Buggl\MainBundle\Event\Mail\AccountSuspendedEvent;

/**
 * Controller related to local authors
 *
 * @author    Vincent Farly G. Tabaoda <farly.taboada@goabroad.com>
 *
 * @copyright 2013 May (c) Buggl.com
 */
class AuthorController extends Controller
{

    /**
     * @var integer limit of showed authors per page
     */
    const LIMIT = 10;


    /**
     * default controller
     * @param Request $request []
     *
     * @return Response         html page
     */
    public function indexAction(Request $request)
    {
        $limit = self::LIMIT;
        $page = $request->get('page', 1);
        $status = $this->get('buggl_main.constants')->get('allowed_user');

        $lists = $this->get('buggl_main.entity_repository')
                      ->getRepository('BugglMainBundle:LocalAuthor')
                      ->findAll($limit, $page-1);

        $countries = $this->get('buggl_main.entity_repository')
                          ->getRepository('BugglMainBundle:Country')
                          ->findAll();

        $countries = json_encode(array_map(function($each){
            return array('id' => $each->getId(), 'name' => $each->getName());
        }, $countries));

        $service = $this->get('buggl_main.local_author_to_json');

        $authors = array();
        foreach ($lists as $each) {
            $authors[] = $service->__toArray($each);
        }

        $data = array(
            'lists' => json_encode($authors),
            'countries' => $countries,
            'totalCount' => count($lists),
            'limit' => $limit
        );

        return $this->render('BugglMainBundle:Admin/Author:author.html.twig', $data);
    }

    /**
     * get cities
     * @param Request $request []
     *
     * @return JsonResponse    returns list of cities in json format
     */
    public function getCityAction(Request $request)
    {
        $countryId = $request->get('countryID');

        $repo = $this->getDoctrine()->getRepository('BugglMainBundle:City');
        $cities = $repo->getByCountryID($countryId, true);

        if (count($cities) == 0) {
            $cities = null;
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($cities, 200);
    }


    /**
     * searching of local authors
     * @param Request $request []
     *
     * @return JsonResponse     returns the total count and filtered local authors
     */
    public function searchLocalAuthorAction(Request $request)
    {
        $limit = self::LIMIT;

        $country = $request->get('country', 0);
        $city = $request->get('city', 0);
        $name = $request->get('name', '');
        $page = $request->get('page', 1);

        $service = $this->get('buggl_main.search_local')->setRepository('BugglMainBundle:LocalAuthor');

        $lists = $service->searchLocalAuthor(
            array('country'=>$country, 'city'=>$city),
            array('name' => $name, 'page' => $page, 'limit' => $limit)
        );

        $service = $this->get('buggl_main.local_author_to_json');

        $data = array();
        foreach ($lists['result'] as $list) {
            $data[] = $service->__toArray($list);
        }

        $value = array('count'=>$lists['count'], 'data'=>$data);

        return new \Symfony\Component\HttpFoundation\JsonResponse($value, 200);
    }

    /**
     * toggle suspension status of local author
     * @param Request $request []
     *
     * @return JsonResponse    returns status
     */
    public function toggleSuspensionAction(Request $request)
    {
        $localAuthorId = $request->get('id');

        $entityManager = $this->getDoctrine()->getEntityManager();

        $author = $entityManager->find('BugglMainBundle:LocalAuthor', $localAuthorId);

        $constants = $this->get('buggl_main.constants');

        $allowUser = $constants->get('allowed_user');
        $suspendUser = $constants->get('suspended_user');

        // 1 suspended 0 allowed
        $toggle = $author->getStatus() ? $allowUser : $suspendUser;
        // toggle here changed to
        // toggle -> send mail suspended
        // dispatch event here
        if ($toggle && !is_null($author)) {
            $event = new AccountSuspendedEvent($this->get('buggl_main.mail_message_builder'), $author);
            $this->get('event_dispatcher')->dispatch('buggl.account_suspended',$event);
        }


        $author->setStatus($toggle);

        $entityManager->flush();

        $text = $toggle ? 'Unsuspend' : 'Suspend';

        $value = array('text'=>$text);

        return new \Symfony\Component\HttpFoundation\JsonResponse($value, 200);
    }


    /**
     * shows info of specific local author
     * @param integer $id []
     *
     * @return Response    info page of local author
     *
     * @version Release: May 2013; Not used?
     */
    public function infoAction( $localAuthorId )
    {
        $entityManager = $this->getDoctrine()->getEntityManager();

        $author = $entityManager->find('BugglMainBundle:LocalAuthor', $localAuthorId);

        if (!count($author)) {
            return new RedirectResponse($this->generateUrl('admin_author'));
        }

        $data = array(
            'localauthor' => $author
        );

        if (count($author)) {
            $followService = $this->get('buggl_main.follow')->init($author);
            $followersCount = $followService->countFollowers();

            $data['followerCount'] = $followersCount;

            $constants = $this->get('buggl_main.constants');
            $refs = $entityManager->getRepository('BugglMainBundle:LocalAuthorToLocalReference')
                       ->retrieveRequestsByStatus(
                           $constants->get('LOCAL_REF_LIST'), $author
                       );

            $refCount = count($refs);

            $data['refsCount'] = $refCount;

            $dlCount = $entityManager->getRepository('BugglMainBundle:EGuide')
                          ->findDLSumByLocalAuthor($author);

            $data['dlCount'] = $dlCount;

            $eguideCount = $entityManager->getRepository('BugglMainBundle:EGuide')
                              ->countByLocalAuthor($author);

            $data['eguideCount'] = $eguideCount;
        }

        return $this->render('BugglMainBundle:Admin/Author:info.html.twig', $data);
    }
	
    

}