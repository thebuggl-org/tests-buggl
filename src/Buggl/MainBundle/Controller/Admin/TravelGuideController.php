<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class TravelGuideController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
    const LIMIT = 5;

    public function indexAction()
    {
        $status = $this->get('buggl_main.constants')->get('unpublished');
        $data = array(
            'limit' => self::LIMIT,
            'guideStatus' => $status,
            'status' => 'approval'
        );

        return $this->render('BugglMainBundle:Admin/TravelGuide:travelguide.html.twig',$data);
    }

    public function activeAction(Request $request)
    {
        $status = $this->get('buggl_main.constants')->get('published');
        
        $data = array(
            'limit' => self::LIMIT,
            'guideStatus' => $status,
            'status' => 'active'
        );

        return $this->render('BugglMainBundle:Admin/TravelGuide:active.html.twig',$data);
    }

    public function archivedAction(Request $request)
    {
        $status = $this->get('buggl_main.constants')->get('archived');
        $data = array(
            'limit' => self::LIMIT,
            'guideStatus' => $status,
            'status' => 'archived'
        );

        return $this->render('BugglMainBundle:Admin/TravelGuide:archived.html.twig',$data);
    }

    public function deniedAction(Request $request)
    {
        $status = $this->get('buggl_main.constants')->get('denied');

        $data = array(
            'limit' => self::LIMIT,
            'guideStatus' => $status,
            'status' => 'denied'
            );

        return $this->render('BugglMainBundle:Admin/TravelGuide:denied.html.twig',$data);
    }

    public function featuredAction(Request $request)
    {
        $limit = 5;
        $page = $request->get('page');
        $title = $request->get('value');

        $constants = $this->get('buggl_main.constants');

        $isFeatured = 1;
        $status = $constants->get('published_guide');

        $lists = $this->get('buggl_main.entity_repository')
                      ->getRepository('BugglMainBundle:EGuide')
                      ->findTravelGuideByTitle($title,$status,$isFeatured,$limit,$page-1);

        $count = count($lists);
        $showPages = $limit * $page >= $count ? false : true;

        $data = array(
                'lists' => $lists,
                'limit' => $limit,
                'page' => $page,
                'itemCount' => $count
            );
        $html = $this->renderView('BugglMainBundle:Admin\TravelGuide:unfeaturedguide.html.twig',$data);

        $data = array(
                'html' => $html,
                'lastPage' => ceil($count/$limit)
            );

        return new JsonResponse($data,200);
    }

    public function fetchAction($status)
    {
        $request = $this->getRequest();

        $country = $request->get('country',0);
        $category = $request->get('category',0);
        $qstring = $request->get('q','');
        $page = $request->get('page',1);
        $limit = self::LIMIT;

        $service = $this->get('doctrine.orm.entity_manager');

        $country = $country ? $service->find('BugglMainBundle:Country',$country) : null;
        $category = $category? $service->find('BugglMainBundle:Category',$category) : null;

        // $guides = $this->get('buggl_main.entity_repository')
        //                ->getRepository('BugglMainBundle:EGuide')
        //                ->findByCountryAndCategory($country,$category,$status,$limit,$page);

        $guides = $this->get('buggl_main.entity_repository')
                       ->getRepository('BugglMainBundle:EGuide')
                       ->findByCountryAndCategoryWithTitle($country, $category, $qstring, $status,$limit,$page);

        $constants = $this->get('buggl_main.constants');

        $publish = $constants->get('published');
        $deny = $constants->get('denied');
        $suspend = $constants->get('suspended');
        $unpublish = $constants->get('unpublished');
        $archive = $constants->get('archived');

        $values = array();
        foreach($guides as $guide){
            $price = $guide->getPrice();
            $whole = floor($price);
            $fraction = $price - $whole;
            $path = $this->generateUrl('admin_buggl_eguide_overview',array('slug'=>$guide->getSlug()));

            $publishUrl = $this->generateUrl('admin_buggl_eguide_change_status',array('status' => $publish, 'id'=> $guide->getId()));
            $denyUrl = $this->generateUrl('admin_buggl_eguide_change_status',array('status' => $deny, 'id'=> $guide->getId()));
            $suspendUrl = $this->generateUrl('admin_buggl_eguide_change_status',array('status' => $suspend, 'id'=> $guide->getId()));
            $unpublishUrl = $this->generateUrl('admin_buggl_eguide_change_status',array('status' => $unpublish, 'id'=> $guide->getId()));
            $archiveUrl = $this->generateUrl('admin_buggl_eguide_change_status',array('status' => $archive, 'id'=> $guide->getId()));

            $values[] = array(
                    'title' => $guide->getPlainTitle(),
                    'whole' => $whole,
                    'decimal' => $fraction,
                    'author' => $guide->getLocalAuthor()->getName(),
                    'id' => $guide->getId(),
                    'country' => $guide->getCountry()->getName(),
                    'duration' => $guide->getRealDuration() > 0 ? $guide->getRealDuration().' day(s)' : 'N/A',
                    'dlCount' => $guide->getDlCount(),
                    'path' => $path,
                    'publish' => $publishUrl,
                    'deny' => $denyUrl,
                    'suspend' => $suspendUrl,
                    'unpublish' => $unpublishUrl,
                    'archive' => $archiveUrl,
                    'localAuthorId' => $guide->getLocalAuthor()->getId()
                );
        }

        $data = array('data' => $values, 'count'=>count($guides));
        return new \Symfony\Component\HttpFoundation\JsonResponse($data,200);
    }

    public function changeStatusAction( $status, $id )
    {

        $em = $this->getDoctrine()->getEntityManager();
        $guide = $em->find('BugglMainBundle:EGuide',$id);

        $constants = $this->get('buggl_main.constants');
        $publish = $constants->get('published');
        $unpublish = $constants->get('unpublished');
        $denied = $constants->get('denied');

        if( $status != $publish ){
            $guide->setIsSpotlight(0);
            $guide->setIsFeaturedInHome(0);
            $guide->setIsFeaturedInCountry(0);
            $guide->setIsFeaturedInProfile(0);

            if($status == $denied) {
                $event = new \Buggl\MainBundle\Event\Mail\DeniedEguideEvent($this->get('buggl_main.mail_message_builder'),$guide);
                $this->get('event_dispatcher')->dispatch('buggl.denied_eguide',$event);
            }
        }
		else{
			$event = new \Buggl\MainBundle\Event\ActivityEvent($guide,$guide->getLocalAuthor(),null,$constants->get('ACTIVITY_PUBLISH_GUIDE'));
			$this->get('event_dispatcher')->dispatch('buggl.activity',$event);

            $approve = new \Buggl\MainBundle\Event\Mail\ApprovedEguideEvent($this->get('buggl_main.mail_message_builder'),$guide);
            $this->get('event_dispatcher')->dispatch('buggl.approve_eguide',$approve);
			
			if($this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->countFeaturedInProfile($guide->getLocalAuthor()) < $constants->get('FEATURED_IN_PROFILE_LIMIT')){
				$guide->setIsFeaturedInProfile(1);
			}

            /**
             * @todo improve. maybe use event dispatching
             */
            $guide->getLocalAuthor()->setIsApproved($constants->get('APPROVE_LOCAL_AUTHOR'));
		}

        $guide->setStatus($status);

        $em->flush();

        $data = array('status' => $status, 'id'=>$id);
        return new \Symfony\Component\HttpFoundation\JsonResponse($data,200);
    }

    /* This is the old implementation. please do not delete...
    public function changeStatusAction( $status, $id )
    {

        $em = $this->getDoctrine()->getEntityManager();
        $guide = $em->find('BugglMainBundle:EGuide',$id);

        $constants = $this->get('buggl_main.constants');
        $publish = $constants->get('published');

        if( $status != $publish ){
            $guide->setIsSpotlight(0);
            $guide->setIsFeaturedInHome(0);
            $guide->setIsFeaturedInCountry(0);
        }
        else{
            $event = new \Buggl\MainBundle\Event\ActivityEvent($guide,$guide->getLocalAuthor(),null,$constants->get('ACTIVITY_PUBLISH_GUIDE'));
            $this->get('event_dispatcher')->dispatch('buggl.activity',$event);
        }

        $guide->setStatus($status);

        $em->flush();

        $data = array('status' => $status, 'id'=>$id);
        return new \Symfony\Component\HttpFoundation\JsonResponse($data,200);
    }
    */

    public function guideOverviewAction(Request $request)
    {
		$request->getSession()->set('has_admin_access', 1);
		return new RedirectResponse($this->generateUrl('buggl_eguide_overview',array('slug'=>$request->get('slug'))));
		// => Redirect to frontend view	
        $slug = $request->get('slug');
        $repo = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide');
        $guide = $repo->findOneBySlug($slug);

        $constants = $this->get('buggl_main.constants');
        if (!$guide) {
            throw $this->createNotFoundException('The guide does not exist');
        }

        $rating = $this->get('buggl_main.rating')->getOverallRating($guide);

        $data = array(
            'guide' => $guide,
            'rating' => $rating,
			'localPerks' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuideContent')->findBy(array('e_guide' => $guide, 'type' => \Buggl\MainBundle\Entity\EGuideContent::OVERVIEW_TYPE)),
        );

        return $this->render('BugglMainBundle:Admin\TravelGuide:guideOverview.html.twig', $data);
    }

    public function fullGuideAction(Request $request)
    {
		$request->getSession()->set('has_admin_access', 1);
		return new RedirectResponse($this->generateUrl('buggl_eguide_overview',array('slug'=>$request->get('slug'))));
		
		// => Redirect to frontend view
        $slug = $request->get('slug');
        $repo = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide');
        $guide = $repo->findOneBySlug($slug);

        $itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findByGuide($guide);

        $firstActive = null;
        $itineraryInfo = array();
        foreach($itinerary as $day){
            $itineraryInfo[$day->getDayNum()] = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpot')->getByEguideAndDay($guide,$day->getDayNum());
            if(is_null($firstActive)){
                $firstActive = $day->getDayNum();
            }
        }

        $localAuthor = $this->get('security.context')->getToken()->getUser();
        $purchased = false;
        if(!is_null($localAuthor)){
			// NOTE: for stripe
            // $purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PurchaseInfo')->findOneByBuyerAndEguide($localAuthor,$guide);
			$purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($localAuthor,$guide);
            $purchased = !is_null($purchaseInfo);
        }

        $data = array(
            'guide' => $guide,
            'itinerary' => $itinerary,
            'itineraryInfo' => $itineraryInfo,
            'firstActive' => $firstActive,
            'purchased' => $purchased
        );

        return $this->render('BugglMainBundle:Admin\TravelGuide:fullGuide.html.twig', $data);
    }

    public function localPlacesAction(Request $request)
    {
		// => Redirect to frontend view
        $slug = $request->get('slug');
        $repo = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide');
        $guide = $repo->findOneBySlug($slug);

        $activeType = $request->get('type');
        $type = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findOneByName(ucwords(str_replace('-',' ',$activeType)));

        $ids = $this->get('buggl_main.eguide_and_spots_native_query')->getSpotDetailsId($guide,$type);
        $spotDetails = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findByIds($ids);

        $spotTypes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findAll();
        $environmentVars = $this->get('buggl_main.environment_variables');

        $localAuthor = $this->get('security.context')->getToken()->getUser();
        $purchased = false;
        if(!is_null($localAuthor)){
			// NOTE: for stripe
            // $purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PurchaseInfo')->findOneByBuyerAndEguide($localAuthor,$guide);
			$purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($localAuthor,$guide);
            $purchased = !is_null($purchaseInfo);
        }

        $data = array(
            'guide' => $guide,
            'spotTypes' => $spotTypes,
            'spotDetails' => $spotDetails,
            'activeType' => $activeType,
            'purchased' => $purchased,
            'googleMapsApiKey' => $environmentVars->getVariable('googleMapsApiKey')
        );

        return $this->render('BugglMainBundle:Admin\TravelGuide:localPlaces.html.twig', $data);
    }

	public function messageGuideAction(Request $request)
	{
		if($request->isMethod('POST')){
			$messageService = $this->get('buggl_main.message_service');
			$data = $request->request->all();
			$user = $this->get('security.context')->getToken()->getUser();
			$messageService->saveMessage($data,$user);
			$this->get('session')->getFlashBag()->add('success', "Message has been sent!");
		}

		return new RedirectResponse($request->headers->get('referer'));
	}
}