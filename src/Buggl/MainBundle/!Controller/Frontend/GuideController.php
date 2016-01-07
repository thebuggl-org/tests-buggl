<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\Query\ResultSetMapping;

use Buggl\MainBundle\Entity\EGuideContent;
use Buggl\MainBundle\Entity\EGuidePhoto;

class GuideController extends Controller
{

    /**
     * seach guides
     * @param  Request $request []
     * 
     * @return RedirectResponse           []
     *
     * @todo 
     *
     * 1 - Search for Country Only 
     *   - If country page is live, then show the country page
     *   - If NO country page, then show the Search Results with the guides associated to the country
     * 2 - Search for Activity Only 
     *   - Show Search Results Page 
     * 3 - Searches for Country and Activity
     *   - Display the Search Results Page
     * 4 - Searches for Country and/or Activity we don't have guides for
     *   - Display the 'request for a guide form'
     */
    public function searchAction(Request $request)
    {

        if( $request->getMethod() == 'POST' ){

            $countryId = $request->get('country_search',0);
            $activity = $request->get('activity_search',0);
            $budget = $request->get('budget',0);
            $duration = $request->get('duration',0);
            $theme = $request->get('theme',0);

            if(!$countryId && !$activity){
                return new RedirectResponse($this->generateUrl('buggl_homepage'));
            }

            if($countryId){
                $country = $this->get('doctrine.orm.entity_manager')->find('BugglMainBundle:Country',$countryId);

                $text = $this->get('buggl_main.slugifier')
                             ->format($country->getName())
                             ->getSlug();

                $params['country'] = $text;
            }

            if($activity){
                $activity = $this->get('doctrine.orm.entity_manager')->find('BugglMainBundle:Category',$activity);

                if($activity){
                    $text = $this->get('buggl_main.slugifier')
                             ->format($activity->getName())
                             ->append($activity->getId())
                             ->getSlug();
                    $params["activity"] = $text;
                    $params["activity_object"] = $activity;
                }
            }

            if($duration){
                $duration = $this->get('doctrine.orm.entity_manager')->find('BugglMainBundle:Duration',$duration);

                if($duration){
                    $text = $this->get('buggl_main.slugifier')
                             ->format($duration->getName())
                             ->append($duration->getId())
                             ->getSlug();

                    $params["duration"] = $text;
                }
            }

            if($budget){
                $upperLimit = 5;
                $lowerLimit = 1;

                if($budget < $lowerLimit){
                    $budget = $lowerLimit;
                }
                if($budget > $upperLimit){
                    $budget = $upperLimit;
                }
                $params["budget"] = $budget;
            }
            if($theme){
                $theme = $this->get('doctrine.orm.entity_manager')->find('BugglMainBundle:TripTheme',$theme);
                if($theme){
                    $text = $this->get('buggl_main.slugifier')
                             ->format($theme->getName())
                             ->append($theme->getId())
                             ->getSlug();

                    $params["theme"] = $text;
                }
            }

            $urlParams = $this->get('buggl_main.url_encoder')
                              ->setParameter($params)
                              ->getUrl();

            return new RedirectResponse($this->generateUrl('buggl_guide_results',array('slug'=>$urlParams)));
        }
    }

    public function resultsPageAction($slug)
    {
        $limit = 20;

        $service = $this->get('buggl_main.url_encoder')->decode($slug);
        $params = $service->getParameters();

        if(!isset($params['country']) && !isset($params['activity'])){
            return new RedirectResponse($this->generateUrl('buggl_homepage'));
        }

        $country = isset($params['country']) ? $params['country'] : 0;
        $activity = isset($params['activity']) ? $params['activity'] : 0;
        $budget = isset($params['budget']) ? $params['budget'] : 0;
        $duration = isset($params['duration']) ? $params['duration'] : '';
        $theme = isset($params['theme']) ? $params['theme'] : '';

        if($activity){
            $keys = array_reverse(explode('-', $activity));
            $activity = $keys[0];
        }

        if($country){
            $countryName = $this->get('buggl_main.slugifier')->format($params["country"])
                            ->getSlugText();

            $status = $this->get('buggl_main.constants')->get('APPROVED_COUNTRY');
            $country = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:Country')
                        ->findByCountryName($countryName,$status);

            if(is_null($country) && !$activity){
               throw $this->createNotFoundException('Country does not exists');
            }
        }
        //country only search
        if($country && !$activity){
            $baseSlug = $service->clear()->setParameter($params)->getUrl();
            $breadcrumbs = $this->get('buggl_main.breadcrumbs')->init('country_guide_page')
                            ->setParameters(array('country-name'=>$country->getName()))
                            ->getBreadcrumbs();


            return $this->render('BugglMainBundle:Frontend\Guide:countryGuide.html.twig',
                array(
                    'country'=>$country,
                    'breadcrumbs' => $breadcrumbs,
                    'baseUrl' => $baseSlug.".activity="
                ));
        }

        //else
        $page = 1;
        if(isset($params['page'])){
            $page = $params['page'];

            unset($params['page']);
        }
        $baseSlug = $service->clear()->setParameter($params)->getUrl();

        $countryId = 0;
        if($country){
            $params['country'] = $country;
            $countryId = $country->getId();
        }

        $activity = $this->get('doctrine.orm.entity_manager')->find('BugglMainBundle:Category',$activity);
        $slug = $this->get('buggl_main.slugifier')->format($activity->getUrl())->getSlug();
        if (is_null($activity) || $slug !== $params['activity']) {
            throw $this->createNotFoundException('Activity not found');
        } else {
            $params['activity_object'] = $activity;
        }

        $durationId = 0;
        if(strlen($duration)){
            $chunks = array_reverse(explode('-', $duration));
            $id = $chunks[0];

            $duration = $this->get('doctrine.orm.entity_manager')->find('BugglMainBundle:Duration',$id);
            if(!is_null($duration)){
                $params['duration'] = $duration;
                $durationId = $duration->getId();
            }
        }

        $themeId = 0;
        if(strlen($theme)){
            $chunks = array_reverse(explode('-', $theme));
            $id = $chunks[0];

            $theme = $this->get('doctrine.orm.entity_manager')->find('BugglMainBundle:TripTheme',$id);
            if(!is_null($theme)){
                $params['theme'] = $theme;
                $themeId = $theme->getId();
            }
        }

        $upperLimit = 5;
        $lowerLimit = 0;

        if($upperLimit < $budget){
            $budget = $upperLimit;
        }
        if($lowerLimit > $budget){
            $budget = $lowerLimit;
        }

        $params['budget'] = $budget;
        $params['activity'] = $activity;

        $status = $this->get('buggl_main.constants')->get('published_guide');

        $guides = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide')
                       ->findBySearchCriteria($params,$status,$page,$limit);

        $count = count($guides);

        $breadcrumbs = $this->get('buggl_main.breadcrumbs')->init('result_page')
                            ->setParameters(array('result-text'=>"About $count results"))
                            ->getBreadcrumbs();

        //query everything here
        //for search parameters
        $service = $this->get('buggl_main.entity_repository');

        $status = $this->get('buggl_main.constants')->get('APPROVED_COUNTRY');
        $countries = $service->getRepository('BugglMainBundle:Country')->findAllByStatus($status);

        $status = $this->get('buggl_main.constants')->get('published_category');
        $categories = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:Category')
                           ->findByStatus($status);

        $duration = $service->getRepository('BugglMainBundle:Duration')->findAll();
        $themes = $service->getRepository('BugglMainBundle:TripTheme')->findAll();
        $goodFor = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:GoodFor')->findAll();

        $budget = isset($params['budget']) ? $params['budget'] : 0;

        $authenticated = $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY');

        $totalPages = ceil($count / $limit);
        return $this->render('BugglMainBundle:Frontend\Guide:resultsPage.html.twig',array(
                'guides' => $guides,
                'breadcrumbs' => $breadcrumbs,
                'count' => $totalPages,
                'baseUrl' => $baseSlug,
                'page' => $page,
                'countryId' => $countryId,
                'activityId' => $activity->getId(),
                'countries' => $countries,
                'activities' => $categories,
                'duration' => $duration,
                'themes' => $themes,
                'budget' => $budget,
                'themeId' => $themeId,
                'durationId' => $durationId,
                'goodFor' => $goodFor,
                'authenticated' => $authenticated
            ));
    }

	public function sidebarAction($guide)
	{
		$limit=3;

		$offset=0;
		$status=2;
		$localAuthor = $guide->getLocalAuthor();
		$entityManager = $this->getDoctrine()->getEntityManager();
		$constants = $this->get('buggl_main.constants');

		//NOTE: check this, location to author maybe many:one
		$location = $entityManager->getRepository('BugglMainBundle:Location')->getByLocalAuthor($localAuthor);
		$reviewCount = $entityManager->getRepository('BugglMainBundle:TravelGuideReview')->countReviewsFilteredByLocalAuthor($localAuthor,$constants->get('approved_review'));

        $purchaseInfo = $entityManager->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findBySeller($localAuthor);
        $totalGuideSoldCount = count($purchaseInfo);

		$tripTheme =$guide->getTripTheme();
		$id = $guide->getId();
		$similarGuide = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuide')->findBysimilarGuide($id,$tripTheme,$status,$offset,$limit);
		$countSimilarGuide= count($similarGuide);
		$data = array(
			'guide' => $guide,
			'location' => ($location) ? $location->getCity()->getName() : NULL,
			'reviewCount' => $reviewCount >= $constants->get('DISP_LIM_REVIEW') ? $reviewCount : 0,
			'downloadCount' => $guide->getDlCount() >= $constants->get('DISP_LIM_DOWNLOAD') ? $guide->getDlCount() : 0,
            'purchaseCount' => $guide->getPurchaseCount(),
			'similarGuides' => $similarGuide,
			'countSimilarGuide' => $countSimilarGuide,
            'totalGuideSoldCount' => $totalGuideSoldCount
		);



		return $this->render('BugglMainBundle:Frontend\Guide:sidebar.html.twig',$data);

	}

    public function guideOverviewAction(Request $request)
    {
        $slug = $request->get('slug');
        //echo $slug; 
        $repo = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide');
        $guide = $repo->findOneBySlug($slug);
        $status=$guide->getStatus();
        
        //print_r($guide); die;
		$redirect = $this->checkRedirect($guide);
		if(!is_null($redirect))
			return $redirect;

        $rating = $this->get('buggl_main.rating')->getOverallRating($guide);

        $metas = $this->get('buggl_seo.full_guide')->buildMetaAttributes($guide);
        $data = array(
            'guide' => $guide,
			'localPerks' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuideContent')->findBy(array('e_guide' => $guide, 'type' => \Buggl\MainBundle\Entity\EGuideContent::OVERVIEW_TYPE)),
            'rating' => $rating,
            'metas' => $metas
        );

        // added by NRL
        $forWishlist = $request->get('forWishlist',false);
        $buyer = null;
        $securityContext = $this->get('security.context');
        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY')){
            $buyer = $securityContext->getToken()->getUser();
        }

        if($this->getRequest()->getSession()->has('has_admin_access')){
            $data = array_merge( $data, array(
                'okToRender' => true,
                'purchased' => true,
                'forWishlist' => $forWishlist
            ) );
        }
        else if(!is_null($buyer)){
            $purchased = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($buyer,$guide);
            $data = array_merge( $data, array(
                'okToRender' => true,
                'purchased' => $purchased,
                'ownGuide' => $buyer->getId() == $guide->getLocalAuthor()->getId(),
                'forWishlist' => $forWishlist
            ));
        }
        else{
            $data = array_merge( $data, array(
                'okToRender' => false,
                'forWishlist' => $forWishlist
            ));
        }


        $guideReviewCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:TravelGuideReview')->countGuideReview($guide);

        $data = array_merge( $data , array('reviewCount' => $guideReviewCount));

        return $this->render('BugglMainBundle:Frontend\Guide:guideOverview.html.twig', $data);
    }

    public function localPlacesAction(Request $request)
    {
        $slug = $request->get('slug');
        $repo = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide');
        $guide = $repo->findOneBySlug($slug);
		$redirect = $this->checkRedirect($guide);
		if(!is_null($redirect))
			return $redirect;

		$activeType = $request->get('type');
		$type = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findOneByName(ucwords(str_replace('-',' ',$activeType)));
		
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$purchased = false || $this->getRequest()->getSession()->has('has_admin_access');
		if(!is_null($localAuthor) && !$purchased){
			// NOTE: for stripe
			// $purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PurchaseInfo')->findOneByBuyerAndEguide($localAuthor,$guide);
			$purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($localAuthor,$guide);
			$purchased = !is_null($purchaseInfo) || ($localAuthor != 'anon.' && $guide->getLocalAuthor()->getId() == $localAuthor->getId());
		}
		
		$eguideToSpotDetails = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->getByEGuideAndType($guide,$type);
		$typeIds = $this->get('buggl_main.eguide_and_spots_native_query')->getSpotTypesWithSpotsForGuide($guide);
		$spotTypes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findByIds($typeIds);
		$environmentVars = $this->get('buggl_main.environment_variables');

        $metas = $this->get('buggl_seo.full_guide')->buildMetaAttributes($guide, 'local-secret');
		$sold = $this->get('session')->get('buy-guide-status', false);
		$this->get('session')->remove('buy-guide-status');
        $data = array(
            'guide' => $guide,
            'spotTypes' => $spotTypes,
            'eguideToSpotDetails' => $eguideToSpotDetails,
            'activeType' => $activeType,
            'purchased' => $purchased,
            'googleMapsApiKey' => $environmentVars->getVariable('googleMapsApiKey'),
            'metas' => $metas,
            'sold' => $sold,
        );

        if( $request->isXmlHttpRequest() )
        {
            $html = $this->renderView('BugglMainBundle:Frontend\Guide:localPlacesData.html.twig', $data);

            return new \Symfony\Component\HttpFoundation\JsonResponse(array('html' => $html),200);
        }

        return $this->render('BugglMainBundle:Frontend\Guide:localPlaces.html.twig', $data);
    }

	public function spotAction(Request $request)
	{
        $slug = $request->get('slug');
        $repo = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide');
        $guide = $repo->findOneBySlug($slug);
		$redirect = $this->checkRedirect($guide);
		if(!is_null($redirect))
			return $redirect;

		$spotDetailId = $request->get('spotDetailId',0);
		$spotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:SpotDetail')->findOneById($spotDetailId);
		$eguideToSpotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->getByEGuideAndSpotDetail($guide,$spotDetail);

        if (!$eguideToSpotDetail) {
            throw $this->createNotFoundException('The spot does not exist');
        }
		
		$typeIds = $this->get('buggl_main.eguide_and_spots_native_query')->getSpotTypesWithSpotsForGuide($guide);
		$spotTypes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findByIds($typeIds);
		$activeType = $request->get('type');
		
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$purchased = false  || $this->getRequest()->getSession()->has('has_admin_access');
		if(!is_null($localAuthor) && !$purchased){
			// NOTE: for stripe
			// $purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PurchaseInfo')->findOneByBuyerAndEguide($localAuthor,$guide);
			$purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($localAuthor,$guide);
			$purchased = !is_null($purchaseInfo) || ($localAuthor != 'anon.' && $guide->getLocalAuthor()->getId() == $localAuthor->getId());
		}
		
		
		$metas = $this->get('buggl_seo.full_spot')->buildMetaAttributes($spotDetail);
		
		$data = array(
			'guide' => $guide,

			'eguideToSpotDetail' => $eguideToSpotDetail,
			'spotTypes' => $spotTypes,
			'activeType' => $activeType,
			'purchased' => $purchased,
			'metas' => $metas
		);

		return $this->render('BugglMainBundle:Frontend\Guide:spot.html.twig', $data);
	}

    public function featuredInCountryAction($country)
    {
        $status = $this->get('buggl_main.constants')->get('featured_guide_in_country');
        $guideStatus = $this->get('buggl_main.constants')->get('published_guide');

        $guides = $this->get('buggl_main.entity_repository')
                       ->getRepository('BugglMainBundle:EGuide')
                       ->findFeaturedInCountry($country,$status,$guideStatus);

        return $this->render('BugglMainBundle:Frontend\Guide:featuredInCountry.html.twig',
                    array('guides'=>$guides));
    }

    public function countryInterestAction($country,$baseUrl)
    {
        $interests = $this->get('buggl_main.entity_repository')
                       ->getRepository('BugglMainBundle:CategoryToCountry')
                       ->findByCountry($country);

        return $this->render('BugglMainBundle:Frontend\Guide:countryInterestList.html.twig',
                    array(
                        'interests' => $interests,
                        'baseUrl' => $baseUrl
                    ));
    }

	public function fullGuideAction(Request $request)
	{
		$slug = $request->get('slug');
        $repo = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide');
        $guide = $repo->findOneBySlug($slug);
		$redirect = $this->checkRedirect($guide);
		if(!is_null($redirect))
			return $redirect;

		$itinerary = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findByGuide($guide);

		if(empty($itinerary)){
			return new RedirectResponse($this->generateUrl('buggl_eguide_overview',array('slug' => $guide->getSlug())));
		}
		
		$eguideToSpotDetails = array();

		$firstActive = null;
		$itineraryInfo = array();
		foreach($itinerary as $day){
			$detailsWithFeatureInfo = array();
			foreach($this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->findByItinerary($day) as $detail){
				$eguideToSpotDetail = null;
				if(isset($eguideToSpotDetails[$detail->getId()])){
					$eguideToSpotDetail = $eguideToSpotDetails[$detail->getId()];
				}
				else{
					$eguideToSpotDetail = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->findOneBy(array('eGuide' => $day->getEGuide(), 'spotDetail' => $detail->getSpotDetail()));
					$eguideToSpotDetails[$detail->getId()] = $eguideToSpotDetail;
				}
				
				$isFeatured = false;
				if(!is_null($eguideToSpotDetail)){
					$isFeatured = $eguideToSpotDetail->getIsFeatured();
				}
				
				$detailsWithFeatureInfo[] = array(
					'isFeatured' => $isFeatured,
					'detail' => $detail
				);
			}
			
			$itineraryInfo[$day->getDayNum()] = $detailsWithFeatureInfo;
			if(is_null($firstActive)){
				$firstActive = $day->getDayNum();
			}
		}

		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$purchased = false  || $this->getRequest()->getSession()->has('has_admin_access');
		if(!is_null($localAuthor) && !$purchased){
			// NOTE: for stripe
			// $purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PurchaseInfo')->findOneByBuyerAndEguide($localAuthor,$guide);
			$purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($localAuthor,$guide);
			$purchased = !is_null($purchaseInfo) || ($localAuthor != 'anon.' && $guide->getLocalAuthor()->getId() == $localAuthor->getId());
		}

        $metas = $this->get('buggl_seo.full_guide')->buildMetaAttributes($guide, 'itinerary');
		$data = array(
			'guide' => $guide,
			'itinerary' => $itinerary,
			'itineraryInfo' => $itineraryInfo,
			'firstActive' => $firstActive,
			'purchased' => $purchased,
            'metas' => $metas
		);

        return $this->render('BugglMainBundle:Frontend\Guide:fullGuide.html.twig', $data);
	}

	public function fullGuideMapAction(Request $request)
	{
		$repo = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide');
        $guide = $repo->findOneBy(array('id' => $request->get('guideId')));

		$environmentVars = $this->get('buggl_main.environment_variables');
		$googleMapsApiKey = $environmentVars->getVariable('googleMapsApiKey');

		$rawSpots = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuideToSpot')->getByEguideJoinSpot($guide,3);

		$spots = array();
		foreach($rawSpots as $spot){
			$temp = array();
			$temp[$spot->getOrder()] = $spot;
			$spots[$spot->getDayNum()] = isset($spots[$spot->getDayNum()]) ?  array_merge($spots[$spot->getDayNum()],$temp) : $temp;
		}

		return $this->render('BugglMainBundle:Frontend\Guide:fullGuideMap.html.twig', array('googleMapsApiKey' => $googleMapsApiKey,'spots' => $spots));
	}

    public function paginationAction($page,$count,$baseUrl)
    {
        $range = 5;

        $startMoving = ($range + 1)/2;
        $adjustment = $page - ($range - 1)/2;

        if( $page <= $startMoving ){
            $start = 1;
        }
        else if( $page + $adjustment <= $count ){
            $start = $page - $adjustment;
        }
        else{
            $start = $count + 1 - $range;
        }

        $last = $start - 1 + $range;

        $last = $last <= $count ? $last : $count;

        $prev = "";
        if( $page > 1 ){
            $prev = $this->generateUrl('buggl_guide_results',array('slug' =>$baseUrl.".page=".($page-1)));
        }

        $next = "";
        if( $page < $count ){
            $next = $this->generateUrl('buggl_guide_results',array('slug' =>$baseUrl.".page=".($page+1)));
        }

        return $this->render('BugglMainBundle:Frontend\Guide:pagination.html.twig',
                array(
                    'start' => $start,
                    'last' => $last,
                    'page' => $page,
                    'baseUrl' => $baseUrl.".page=",
                    'prev' => $prev,
                    'next' => $next
                ));
    }

	public function guideNotPublishedAction()
	{
		return $this->render('BugglMainBundle:Frontend\Guide:guideNotPublished.html.twig');
	}

    public function footerGuideAction()
    {
        $limit = 5;

        $guides = $this->get('buggl_main.entity_repository')->getRepository('BugglMainBundle:EGuide')
                       ->findByDLCount($limit);

        return $this->render('BugglMainBundle:Frontend\Main:footerGuide.html.twig',array('guides'=>$guides));
    }

	private function checkRedirect($guide)
	{    //echo $guide->getStatus(); die;
        $userId=1;
        $localAuthor = $this->get('security.context')->getToken()->getUser();
       if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
    // authenticated (NON anonymous)


         $userId = $localAuthor->getId();}
         $g_id=$guide->getisRequestId();
//die;
         //echo $userId; echo $g_id; die;
		$admin = $this->getRequest()->getSession()->has('has_admin_access');
		$constants = $this->get('buggl_main.constants');
        if (!$guide || $guide->getStatus() == $constants->get('deleted')) {
            throw $this->createNotFoundException('The guide does not exist');
        }

		else if($guide->getStatus() != $constants->get('published_guide') && ($guide->getStatus() !=10|| $userId !=$g_id)) {
           
	        $securityContext = $this->get('security.context');
	        if($securityContext->isGranted('IS_AUTHENTICATED_FULLY') || $admin){
				$localAuthor = $this->get('security.context')->getToken()->getUser();
				if($admin || $localAuthor->getId() == $guide->getLocalAuthor()->getId()){
					$this->get('session')->getFlashBag()->add('permanent-notice', "This guide is not yet published other users can't view this yet.");
					return null;
				}
			}

			return $this->forward('BugglMainBundle:Frontend\Guide:guideNotPublished');
		}

		return null;
	}

    /**
     * START: Eguide Preview
     */
    public function eguidePreviewAction(Request $request)
    {
        $eguide_id = $request->get('eguide_id');
        $eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($eguide_id);
        $localAuthor = $eguide->getLocalAuthor();
        
        // get logged in user and make sure that only the owner of the guide or admin have access
        $isAdmin = $this->getRequest()->getSession()->has('has_admin_access');
        // $user = $this->get('security.context')->getToken()->getUser();
        // if(
        //     (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') && !$isAdmin)
        //     || 
        //     ($user->getID() != $localAuthor->getID())
        //     )
        // {
        //     $response = new Response(); 
        //     $response->setStatusCode(404); 
        //     return $response; 
        // }

        $overviewContents = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideContent')->findBy(array('e_guide' => $eguide, 'type' => EGuideContent::OVERVIEW_TYPE));
        
        $coverPhotos = array(
            'cover' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuidePhoto')->findOneBy(array('e_guide' => $eguide, 'type' => EGuidePhoto::COVER_PHOTO, 'status' => 1)),
            'overview' => $this->getDoctrine()->getRepository('BugglMainBundle:EGuidePhoto')->findOneBy(array('e_guide' => $eguide, 'type' => EGuidePhoto::OVERVIEW_PHOTO, 'status' => 1)),
            'local_secret' => $eguidePhoto = $this->getDoctrine()->getRepository('BugglMainBundle:EGuidePhoto')->findOneBy(array('e_guide' => $eguide, 'type' => EGuidePhoto::LOCAL_SECRET_PHOTO, 'status' => 1))
            );

        $localSecrets = array();
        $itineraries = array();
        
        $egtsdObjs = $this->getDoctrine()->getRepository('BugglMainBundle:EGuideToSpotDetail')->getByEGuide($eguide, 0);
        foreach($egtsdObjs as $ls)
        {
            $spotDetail = $ls->getSpotDetail();

            if($spotDetail)
                $localSecrets[] = $spotDetail;
        }

        $itineraries = $this->getDoctrine()->getRepository('BugglMainBundle:Itinerary')->findBy(array('e_guide' => $eguide));
        $itinerarySpotDetails = array();
        $perTimePage = 1;
        foreach($itineraries as $i)
        {
            if( !array_key_exists($i->getDayNum(), $itinerarySpotDetails) ){
                $itinerarySpotDetails[$i->getDayNum()] = array();
            }

            $itsds = $this->getDoctrine()->getRepository('BugglMainBundle:ItineraryToSpotDetail')->findBy(array('itinerary' => $i));
            foreach($itsds as $itsd)
            {
                $periodOfDay = $itsd->getPeriodOfDay();
                if( !array_key_exists($periodOfDay, $itinerarySpotDetails[$i->getDayNum()]) ){
                    $itinerarySpotDetails[$i->getDayNum()][$periodOfDay] = array();
                    $perTimePage = 1;
                }

                if( !array_key_exists('page-'.$perTimePage, $itinerarySpotDetails[$i->getDayNum()][$periodOfDay]) ){
                    $itinerarySpotDetails[$i->getDayNum()][$periodOfDay]['page-'.$perTimePage] = array();
                }
                else if( 2 == count($itinerarySpotDetails[$i->getDayNum()][$periodOfDay]['page-'.$perTimePage]) ) {
                    $perTimePage = $perTimePage + 1;
                    $itinerarySpotDetails[$i->getDayNum()][$periodOfDay]['page-'.$perTimePage] = array();
                }

                $itinerarySpotDetails[$i->getDayNum()][$periodOfDay]['page-'.$perTimePage][] = $itsd->getSpotDetail();
                ksort($itinerarySpotDetails[$i->getDayNum()]);
            }
        }
        
        $beforeYouGo = $this->getDoctrine()->getRepository('BugglMainBundle:BeforeYouGo')->findOneBy(array('e_guide' => $eguide));
        
        return $this->render('BugglMainBundle:LocalAuthor/Eguides/Themes/Default:preview.html.twig',
            array('eguide' => $eguide,
                'overviewContents' => count($overviewContents) ? $overviewContents[0] : null,
                'coverPhotos' => $coverPhotos,
                'localSecrets' => $localSecrets,
                'itineraries' => $itineraries,
                'itinerarySpotDetails' => $itinerarySpotDetails,
                'beforeYouGo' => $beforeYouGo,
                'schedules' => array(1 => 'Morning', 2 => 'Afternoon', 3 => 'Evening')));

    }

    public function createHtmlAction(Request $request)
    {
        $eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($request->get('eguide_id'));
        $this->createHtml($eguide);
        
    }

    public function downloadGuideAction(Request $request)
    {
        $isAdmin = $this->getRequest()->getSession()->has('has_admin_access');
        $user = $this->get('security.context')->getToken()->getUser();
        $eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneBySlug($request->get('slug'));

        // get logged in user
        if(!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') && !$isAdmin)
        {
            $response = new Response(); 
            $response->setStatusCode(404); 
            return $response; 
        }

        if(!$isAdmin)
        {
            $purchaseInfo = $this->getDoctrine()->getRepository('BugglMainBundle:PaypalPurchaseInfo')->findOneByBuyerAndEguide($user,$eguide);
            $purchased = !is_null($purchaseInfo) || ($user != 'anon.' && $eguide->getLocalAuthor()->getId() == $user->getId());

            if(!$purchased){
                $response = new Response(); 
                $response->setStatusCode(404); 
                return $response; 
            }
        }
                
        if($eguide)
        {	
            $pdfDocRoot = $this->container->get('kernel')->getRootdir().'/../web/uploads/eguide_pdf';
            $filename = $eguide->getPdfFilename();
            $pdfFile = $pdfDocRoot . "/" . $eguide->getPdfFilename();
            
            $key = $this->get('buggl_main.constants')->get('EGUIDE_PDF') . $filename;
            $result = $this->get('buggl_aws.wrapper')->download($key);
            if(!is_null($result))
            {
                // only track DL's if not owner and not admin
                if(!$isAdmin and $eguide->getLocalAuthor()->getId() != $user->getId())
                {
					$constants = $this->get('buggl_main.constants');
					$activityEvent = new \Buggl\MainBundle\Event\ActivityEvent($eguide,$user,$eguide->getLocalAuthor(),$constants->get('ACTIVITY_DOWNLOAD_GUIDE'));
					$this->get('event_dispatcher')->dispatch('buggl.activity',$activityEvent);
					
                    // commented as it is the same as above - it is causing a double entry on the activity log
					// $constants = $this->get('buggl_main.constants');
					// $activityEvent = new \Buggl\MainBundle\Event\ActivityEvent($eguide,$user,$eguide->getLocalAuthor(),$constants->get('ACTIVITY_DOWNLOAD_GUIDE'));
					// $this->get('event_dispatcher')->dispatch('buggl.activity',$activityEvent);
					
                    $eguide->setDlCount($eguide->getDlCount() + 1);
                    $this->getDoctrine()->getEntityManager()->persist($eguide);
                    $this->getDoctrine()->getEntityManager()->flush();
                }

                $response = new Response(); 
                $response->setStatusCode(200); 
                $response->setContent( $result['Body'] );
                $response->headers->set('Content-Type', $result['ContentType'] ); 
                $response->headers->set('Content-length', (int)$result['ContentLength'] ); 
                $response->headers->set('Content-Disposition',sprintf('attachment;filename="%s"', $filename)); 
                $response->send(); 
                return $response;
            }
            else 
            {
                // $response = new Response(); 
                // $response->setStatusCode(404); 
                // return $response;
                
                if(!$isAdmin and $eguide->getLocalAuthor()->getId() != $user->getId())
                {
                    $constants = $this->get('buggl_main.constants');
                    $activityEvent = new \Buggl\MainBundle\Event\ActivityEvent($eguide,$user,$eguide->getLocalAuthor(),$constants->get('ACTIVITY_DOWNLOAD_GUIDE'));
                    $this->get('event_dispatcher')->dispatch('buggl.activity',$activityEvent);
                    
                    $eguide->setDlCount($eguide->getDlCount() + 1);
                    $this->getDoctrine()->getEntityManager()->persist($eguide);
                    $this->getDoctrine()->getEntityManager()->flush();
                }
                
                $response = new Response(); 
                $response->setStatusCode(200); 
                $response->setContent(file_get_contents($pdfFile));
                $response->headers->set('Content-Type', 'application/pdf'); 
                $response->headers->set('Content-length', filesize($pdfFile)); 
                $response->headers->set('Content-Disposition',sprintf('attachment;filename="%s"', $filename)); 
                $response->send(); 
                return $response; 
            }

            
        }
        else {
            $response = new Response(); 
            $response->setStatusCode(404); 
            return $response; 
        }

        
    }

    public function guidePublishdByUserAction(Request $request)
    {
        $id = $request->get('id');
       // echo $slug; die;
        $status=$request->get('status');
        $localAuthor = $this->get('security.context')->getToken()->getUser();
        
        $eguide = $this->getDoctrine()->getRepository('BugglMainBundle:EGuide')->findOneById($id); 
         if($eguide->getisRequestId()==$localAuthor->getId())
         {
            if($status=='publish')
            {
         $eguide->setStatus(2);
            }
            else
            {
         $eguide->setStatus(10);       
            }
        $this->getDoctrine()->getEntityManager()->persist($eguide);
        $this->getDoctrine()->getEntityManager()->flush();
         
         }
        $this->get('session')->getFlashBag()->add('Successful', "This guide is  published.");
      return new RedirectResponse($this->generateUrl('buggl_eguide_overview',array('slug' => $eguide->getSlug())));
    }


}