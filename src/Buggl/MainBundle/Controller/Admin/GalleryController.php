<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * controller related to gallery
 *
 * @author    Vincent Farly G. Taboada <farly.taboada@goabroad.com>
 *
 * @copyright 2013 April (c) Buggl.com
 */
class GalleryController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{

    /**
     * @var limit of photos per page
     */
    const LIMIT = 4;


    /**
     * default page for gallery
     *
     * @return Response template/html page with data
     */
    public function indexAction()
    {
        $countries = $this->get('buggl_main.entity_repository')
                          ->getRepository('BugglMainBundle:Country')
                          ->findAll();

        $result = $this->get('buggl_main.search_pictures')
                       ->search(array('limit' => self::LIMIT, 'page'=>1));

        $totalCount  = $result['count'];

        $photos = json_encode($result['result']);

        $countries = json_encode(array_map(function($each){
            return array('id' => $each->getId(), 'name' => $each->getName());
        }, $countries));

        $data = array(
            'status' => 'images',
            'countries' => $countries,
            'images' => $photos,
            'limit' => self::LIMIT,
            'count' => $totalCount
        );

        return $this->render('BugglMainBundle:Admin/Gallery:gallery.html.twig', $data);
    }

    /**
     * fetching more photos: invoked via ajax
     *
     * @param Request $request [description]
     *
     * @return JsonResponse
     */
    public function fetchMoreAction(Request $request)
    {
        $page = $request->get('page', 1);
        $country = $request->get('country', 0);
        $city = $request->get('city', 0);

        $limits = array('limit' => self::LIMIT, 'page'=>$page);
        $filters = array('country' => $country, 'city'=>$city);

        $results = $this->get('buggl_main.search_pictures')
                        ->search($limits, $filters);

        return new JsonResponse($results, 200);
    }
}