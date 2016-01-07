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


class EGuideRequestController extends Controller 
{
	const LIMIT = 10;

    public function indexAction(Request $request)
    {
        $page = $request->get('page',1);
        $localAuthor = $this->get('security.context')->getToken()->getUser();
       
	/*	if($localAuthor->getIsLocalAuthor() == 0){
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
			 'limit'			=> $limit

            );
        return $this->render('BugglMainBundle:LocalAuthor\EGuideRequest:index.html.twig', $data);
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

    public function eGuideRequestSubmitAction(Request $request)
    {
       
        $params     = $request->request->all();
        $country    = $params['newcoun'];
        $country1    = $params['country'];
        $destination    = $params['destination'];
        $trip_theme = $params['trip_theme'];
        // $category   = $params['category'];
        $userip     =$params['userip'];
        $duration   = $params['duration'];
        $good_for   = $params['good_for'];
        $reason     = $params['reason'];
        $budget     = $params['budgetserial'];
        $localauthorid=$params['local_author_id'];
        $message    =$params['description'];
        $email      =$params['email'];
        $price      =$params['price'];
        $pace       =$params['pace'];
        $plan_type=$params['plan_type'];
        if(!empty($params['tripplan']))
        {
        $tripplan   =$params['tripplan'];
       }
       if(!empty($params['tripplan1']))
        {
            $tripplan   =$params['tripplan1'];
       }
        $experience =$params['experience'];
        $food       =$params['food'];
        $drinking   =$params['drinking'];
        $shopping   =$params['shopping'];
        $activities =$params['activities'];
        $hotel      =$params['hotel'];
        $special_touches=$params['special_touches'];
        $stripeToken=$params['stripetoken'];
      // print_r($params); die;
         if(empty($stripeToken))
            {

               $this->get('session')->getFlashBag()->add('Failed', "Transaction Failed"); 
               return $this->render('BugglMainBundle:LocalAuthor\Profile:profile.html.twig', $data);  
            }
        $country = $this->getDoctrine()->getRepository('BugglMainBundle:Country')->findOneById($country);
        $trip_theme = $this->getDoctrine()->getRepository('BugglMainBundle:TripTheme')->findOneById($trip_theme);
        // $category = $this->getDoctrine()->getRepository('BugglMainBundle:Category')->findOneById($category);
        $duration = $this->getDoctrine()->getRepository('BugglMainBundle:Duration')->findOneById($duration);
        $good_for = $this->getDoctrine()->getRepository('BugglMainBundle:GoodFor')->findOneById($good_for);
        $localauthorid = $this->getDoctrine()->getRepository('BugglMainBundle:LocalAuthor')->findOneById($localauthorid);
        $user     = $this->get('security.context')->getToken()->getUser();
        $useremail=$user->getEmail();
        $eguidereq = new EGuideRequest();
       
        $eguidereq->setCountry($country);
        $eguidereq->setTripTheme($trip_theme);
        $eguidereq->setCategory();
        $eguidereq->setDuration($duration);
        $eguidereq->setGoodFor($good_for);
        $eguidereq->setLocalAuthor($localauthorid);
        $eguidereq->setUser($user);
        $eguidereq->setMessage($message);
        $eguidereq->setBudget($budget);
        $eguidereq->setPrice($price);
        $eguidereq->setStatus(0);
        $eguidereq->setPace($pace);
        $eguidereq->setTripplan($tripplan);
        $eguidereq->setExperience($experience);
        $eguidereq->setFood($food);
        $eguidereq->setDrinking($drinking);
        $eguidereq->setShopping($shopping);
        $eguidereq->setActivities($activities);
        $eguidereq->setReason($reason);
        $eguidereq->setPlanType($plan_type);
        $eguidereq->setHotel($hotel);
        $eguidereq->setDestination($destination);
        $eguidereq->setSpecialtouches($special_touches);
        $eguidereq->setDateAdded(new \DateTime(date('Y-m-d H:i:s',time())));

		require_once('/var/www/websites/src/beta_rel_May3/vendor/stripe/stripe-php/init.php');
        \Stripe\Stripe::setApiKey('sk_live_ad96639c1HmqzfvfnyP7qzM0');
        //$myCard = array('number' => '4242424242424242', 'exp_month' => 5, 'exp_year' => 2015);
        $charge = \Stripe\Charge::create(array('card' =>$stripeToken, 'amount' => $price.'00', 'currency' => 'usd'));
        
        if (strlen($message) == 0 || !is_numeric($price) ) {
            $data = array(
                'success' => false,
            );

            return new JsonResponse($data,200);
        }

        if($country->getStatus() !== $this->get('buggl_main.constants')->get('APPROVED_COUNTRY')){
            $country->setStatus($this->get('buggl_main.constants')->get('REQUESTED_COUNTRY'));
        }
       
        try {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($eguidereq);
            
           // echo $em->getSql();
            
            $em->flush();
            $eguidereq->getId();

            $messages=array('subject'=>'Itinerary Request','messagetype'=>'eguiderequest','message'=>$reason,'recipient'=>$localauthorid ,'eguide_request_id'=>$eguidereq->getId() );
            $messageService = $this->get('buggl_main.message_service');
            $messageService->saveMessage($messages,$user);
            $this->get('buggl_main.eguide_request_service')->sendEGuideRequest($eguidereq,$email,$useremail,$userip,$destination,$params['duration'],$country1);
            $data = array('success' => true);
            $this->get('session')->getFlashBag()->add('success', "Your request has been successfully processed");
        } catch(\Exception $e) {
            echo $e;
            $data = array('success' => false);
        }


        // $html='http://buggl.local/buggl/html2pdf/index.html';
        // $options = array(
        //         'page-height'   => 195.95,
        //         'page-width'    => 132.26,
        //         'margin-bottom' => 0,
        //         'margin-left'   => 0.6,
        //         'margin-right'  => 0,
        //         'margin-top'    => 0,
        //         'disable-smart-shrinking' => true,
        //         'zoom'          => .999
        //     );

        // $output = 'C:\Users\Cris Casas\My GAP Documents\buggl\132x195.95_marginleft=0.6_dis.smart.zoom=.999.pdf';

        // $this->get('knp_snappy.pdf')->generate('http://buggl.local/buggl/html2pdf/index.html',$output ,$options);
         //die;
         return new RedirectResponse($this->generateUrl('local_author_profile',array('slug' => $localauthorid->getSlug())));
        return new JsonResponse($data,200);

    }

   
}
