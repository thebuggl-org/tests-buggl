<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

class SpotsController extends Controller
{

    const LIMIT = 5;

    public function indexAction()
    {
        $service = $this->get('buggl_main.entity_repository');

        $countries = $service->getRepository('BugglMainBundle:Country')->findAll();
        $types = $service->getRepository('BugglMainBundle:SpotType')->findAll();

        $data = array(
                'countries' => $countries,
                'types' => $types,
                'limit' => self::LIMIT
            );

        return $this->render('BugglMainBundle:Admin/Spots:spots.html.twig',$data);
    }

    public function fetchMoreAction(Request $request)
    {
        $page = $request->get('page',1);
        $type = $request->get('type',0);
        $country = $request->get('country',0);

        $service = $this->get('buggl_main.entity_repository');

        $data = $service->getRepository('BugglMainBundle:Spot')->searchSpot($country,$type,self::LIMIT,$page);
        $count = $service->getRepository('BugglMainBundle:Spot')->countSpot($country,$type);

        $values = array();
        foreach( $data as $each ){
            $spotDetail = $service->getRepository('BugglMainBundle:SpotDetail')->findOneBy(array('spot' => $each));

            $condition = is_null($spotDetail);

            $photo = $condition ? '' : $spotDetail->getPhoto();
            $bestThing = $condition ? '' : $spotDetail->getBestThing();
            $content = $condition ? '' : $spotDetail->getDescription();
            $type = $condition ? '' : $spotDetail->getSpotType()->getName();
            $contact = $each->getContactNumber();
            // $contact = '09991481076';

            $values[] = array(
                    'id' => $each->getId(),
                    'name' => $each->getName(),
                    'address' => $each->getAddress(),
                    'city' => $each->getCity()->getName(),
                    'type' => $type,
                    'photo' => $photo,
                    'country' => $each->getCity()->getCountry()->getName(),
                    'bestThing' => $bestThing,
                    'content' => $content,
                    'contact' => $contact
                );
        }

        $return = array('values' => $values, 'count' => $count);

        return new JsonResponse($return,200);
    }
}