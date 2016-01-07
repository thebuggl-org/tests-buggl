<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\FormError;

use Buggl\MainBundle\Form\Type\LocalInterestType;
use Buggl\MainBundle\Form\Type\LocalAuthorType;
use Buggl\MainBundle\Form\Type\TravelInfoType;
use Buggl\MainBundle\Form\Type\ProfileType;
use Buggl\MainBundle\Entity\LocalPassion;
use \Buggl\MainBundle\Entity\LocalAuthor;
use Buggl\MainBundle\Entity\TravelInfo;
use Buggl\MainBundle\Interfaces\BugglSecuredPage;
use Buggl\MainBundle\Entity\EGuideRequest;

class ProfileController extends Controller
{

	public function profileAction(Request $request)
	{
		
		 $localAuthor = $this->get('security.context')->getToken()->getUser();
		$slug = $request->get('slug');

		$localAuthor = $this->get('buggl_main.entity_repository')
		                    ->getRepository('BugglMainBundle:LocalAuthor')
		                    ->findOneBy(array('slug' => $slug));

		if(is_null($localAuthor) || $localAuthor->getIsLocalAuthor() == 0){
			throw $this->createNotFoundException('Local Author not found');
		}
		$securityContext = $this->get('security.context');

		$user = $securityContext->getToken()->getUser();
		$newEGuideRequestCount = $this->getDoctrine()
			                     ->getEntityManager()
                                 ->getRepository('BugglMainBundle:MessageToUser')
                                 ->countRequestByStatus($localAuthor,array('0'=>'0'));
		$instanceOf = $user instanceof \Buggl\MainBundle\Entity\LocalAuthor;

		$editable = $instanceOf ? $localAuthor->getId() == $user->getId() : false;

		if($editable){
			$entityManager = $this->getDoctrine()->getEntityManager();
			$profile = $localAuthor->getProfile();
			$localInterests = $entityManager->getRepository('BugglMainBundle:LocalPassion')->getByLocalAuthor($localAuthor);
			$travelInfo = $entityManager->getRepository('BugglMainBundle:TravelInfo')->getByLocalAuthor($localAuthor);
			$socialMedia = $entityManager->getRepository('BugglMainBundle:SocialMedia')->findByLocalAuthor($localAuthor);

			$data = array(
				'localInterests' => $localInterests,
				'travelInfo'     => $travelInfo,
				'profile'        => $profile,
				'newRequestCount'=>$newEGuideRequestCount,
				'socialMedia'    => $socialMedia
			);

			return $this->render('BugglMainBundle:LocalAuthor\Profile:profile.html.twig', $data);
		}

		return $this->forward('BugglMainBundle:Frontend\LocalAuthor:profile',array('localAuthor' =>$localAuthor ));
	}

	/*
	 * Profile Info starts here
	 */

	public function updateProfileAction(Request $request)
	{
		$data['status'] = "ERROR";
		$updateFor = $request->get('updateFor');
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$profile = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Profile')->getByLocalAuthor($localAuthor, true);
		if(is_null($profile->getLocalAuthor())){
			$profile->setLocalAuthor($localAuthor);
		}

		$form = $this->getProfileForm($updateFor, $profile);
		$validForm = false;
		$validLocation = true;

		if ($request->getMethod() == 'POST') {
	        $form->bind($request);
			$validForm = $form->isValid();
			if($validForm){
				$profileService = $this->get('buggl_main.profile_service');
				if($updateFor == 'guideInfo' || $updateFor == 'location'){
					//validate location here
					$data = $request->request->get('Profile');
					$validLocation = $this->validateCountryAndCity($data['localAuthor'],$form->get('localAuthor'));
					if($validLocation){
						$localAuthorService = $this->get('buggl_main.local_author_service');
						$params = $request->request->all();
						$location = $localAuthorService->updateLocalAuthorLocation($localAuthor,$params['Profile']['localAuthor']['country'],$params['Profile']['localAuthor']['city']);
					}
				}
				$profile = $profileService->updateProfile($profile,$localAuthor);
				
				$streetCreditService = $this->get('buggl_main.street_credit');
				$streetCreditService->updateProfileStatus($localAuthor);
				
				$data['content'] = $this->renderView('BugglMainBundle:LocalAuthor\Profile:profileInfo.html.twig', array('renderFor' => $updateFor));
				$data['status'] = "SUCCESS";
				$data['updateId'] = $updateFor;
			}
			
			if(!$validForm || !$validLocation){
				$data['status'] = "ERROR";
				$defaultCountry = '';
				$defaultCountryId = '';
				$defaultCity = '';
				if($updateFor == 'guideInfo'){
					$localAuthor = $this->get('security.context')->getToken()->getUser();
					$location = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Location')->getByLocalAuthor($localAuthor);
					$defaultCountry .= $location->getCity()->getCountry()->getName();
					$defaultCountryId .= $location->getCity()->getCountry()->getId();
					$defaultCity .= $location->getCity()->getName();
				}
				$data['content'] = $this->renderView('BugglMainBundle:LocalAuthor\Profile:profileForm.html.twig', array('form' => $form->createView(), 'buildFor' => $updateFor, 'profile' => $profile, 'defaultCountry' => $defaultCountry, 'defaultCountryId' => $defaultCountryId, 'defaultCity' => $defaultCity ));
			}
	    }

		return new JsonResponse($data,200);
	}
	
	/*
	 * temporary, quickfix
	 */
	private function validateCountryAndCity($params, $form)
	{
		$valid = true;
		
		$country = null;
		if($params['country'] != ''){
			$country = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Country')->findOneByName($params['country']);
		}
		
		if(is_null($country)){
			$form->get('country')->addError(new FormError('Please enter a valid country'));	
			$valid = false;
		}
		
		if($params['city'] == ''){
			$form->get('city')->addError(new FormError('Required'));	
			$valid = false;
		}
		
		return $valid;
	}

	public function getProfileFormAction(Request $request)
	{
		$buildFor = $request->get('buildFor');

		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$profile = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Profile')->getByLocalAuthor($localAuthor,true);
		if(is_null($profile->getLocalAuthor())){
			$profile->setLocalAuthor($localAuthor);
		}

		$form = $this->getProfileForm($buildFor, $profile);

		$defaultCountry = '';
		$defaultCountryId = '';
		$defaultCity = '';
		if($buildFor == 'guideInfo' || $buildFor == 'location'){
			$localAuthor = $this->get('security.context')->getToken()->getUser();
			$location = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Location')->getByLocalAuthor($localAuthor);
			if(!is_null($location)){
				$defaultCountry .= $location->getCity()->getCountry()->getName();
				$defaultCountryId .= $location->getCity()->getCountry()->getId();
				$defaultCity .= $location->getCity()->getName();
			}
		}

		return $this->render('BugglMainBundle:LocalAuthor\Profile:profileForm.html.twig', array('form' => $form->createView(), 'buildFor' => $buildFor, 'profile' => $profile, 'defaultCountry' => $defaultCountry, 'defaultCountryId' => $defaultCountryId, 'defaultCity' => $defaultCity ));
	}

	private function getProfileForm($buildFor, $profile)
	{
		$form = $this->createForm(new ProfileType($buildFor), $profile);

		return $form;
	}

	/*
	 * Profile Info ends here
	 */

	/*
	 * Basic Info starts here
	 */

	public function updateLocalAuthorAction(Request $request)
	{
		$data['status'] = "ERROR";
		$updateFor = $request->get('updateFor');
		$form = $this->getLocalAuthorForm($updateFor);

		if ($request->getMethod() == 'POST') {
	        $form->bind($request);
			if($form->isValid()){
				$localAuthorService = $this->get('buggl_main.local_author_service');
				//this is for location
				if($updateFor == 3){
					$params = $request->request->get('LocalAuthor');
					$location = $localAuthorService->updateLocalAuthorLocation($form->getData(),$params['country'],$params['city']);
					$data['content'] = $location->getCity()->getName(true);
				}
				else{
					$localAuthor = $localAuthorService->updateLocalAuthor($form->getData());
					$data['content'] = $localAuthor->getFirstName().' '.$localAuthor->getLastName();
				}
				$data['status'] = "SUCCESS";
				$data['updateId'] = $updateFor;
			}
	    }
		else{
			return $this->render('BugglMainBundle:LocalAuthor\Profile:addLocalInterestForm.html.twig', array('localInterestForm' => $localInterestForm->createView(), 'photoPath' => null));
		}

		return new JsonResponse($data,200);
	}

	public function getLocalAuthorFormAction(Request $request)
	{
		$buildFor = $request->get('buildFor');
		$form = $this->getLocalAuthorForm($buildFor);

		$defaultValue = '';
		if($buildFor == 3){
			$localAuthor = $this->get('security.context')->getToken()->getUser();
			$location = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Location')->getByLocalAuthor($localAuthor);
			$defaultValue .= $location->getCity()->getId();
		}

		return $this->render('BugglMainBundle:LocalAuthor\Profile:localAuthorForm.html.twig', array('form' => $form->createView(), 'buildFor' => $buildFor, 'defaultValue' => $defaultValue));
	}

	/*
	private function updateLocalAuthor($form, $updateFor)
	{
		$data = array();

		$data['updateId'] = $updateFor;
		$localAuthorService = $this->get('buggl_main.local_author_service');
		//this is for location
		if($updateFor == 3){
			$location = $localAuthorService->updateLocalAuthorLocation($form->getData());
		}
		else{
			$localAuthor = $localAuthorService->updateLocalAuthor($form->getData());
			$data['firstName'] = $localAuthor->getFirstName();
			$data['lastName'] = $localAuthor->getLastName();
			$data['email'] = $localAuthor->getEmail();
		}
		$data['status'] = "SUCCESS";

		return $data;
	}
	*/

	/*
	 * Gets appropriate form for updating LocalAuthor info
	 */
	private function getLocalAuthorForm($buildFor)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$form = $this->createForm(new LocalAuthorType($buildFor), $localAuthor);

		return $form;
	}
	/*
	 * Basic Info ends here
	 */

	/*
	 * Local Interest starts here
	 */

	public function addLocalInterestAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$data = array('status' => "ERROR");

		$localInterest = new LocalPassion();
		$form = $this->createForm(new LocalInterestType(), $localInterest);
		$photoForm = $this->getUploadLocalInterestPhoto();
		$action = $this->generateUrl('add_local_interest');
		
		if ($request->getMethod() == 'POST') {
			$form->bind($request);
	        if ($form->isValid()) {
				$data['status'] = 'SUCCESS';
				$profileService = $this->get('buggl_main.profile_service');
				$localInterest = $profileService->saveInterest($localInterest,$localAuthor);
				
				$temp = $this->container->get('kernel')->getRootdir().'/../web/uploads/local_interest_temp/'.$localInterest->getImageFilename();
				$actual = $this->container->get('kernel')->getRootdir().'/../web/uploads/local_interest/'.$localInterest->getImageFilename();
				@rename($temp, $actual);
				
				$data['list'] = $this->renderView('BugglMainBundle:LocalAuthor\Profile:localInterestList.html.twig', array('localInterests' => array($localInterest)));
			}
			else{
				$data['status'] = 'ERROR';
				$photoPath = '/uploads/local_interest_temp/'.$form->getData()->getImageFilename();
				$data['html'] = $this->renderView('BugglMainBundle:LocalAuthor\Profile:localInterestForm.html.twig', array('localInterestForm' => $form->createView(), 'photoForm' => $photoForm->createView(), 'photoPath' => $photoPath, 'action' => $action, 'id' => 0));
			}
		}
		else{
			return $this->render('BugglMainBundle:LocalAuthor\Profile:localInterestForm.html.twig', array('localInterestForm' => $form->createView(), 'photoForm' => $photoForm->createView(), 'photoPath' => null, 'action' => $action, 'id' => 0));
		}

		return new JsonResponse($data,200,array("Content-Type" => "text/plain"));
	}

	public function editLocalInterestAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$localInterest = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalPassion')->findOneBy(array('id' => $request->get('localInterestId',0)));
		$data = array('status' => "ERROR");
		$action = $this->generateUrl('edit_local_interest',array('localInterestId' => $localInterest->getId()));
		$id = $localInterest->getId();

		$form = $this->createForm(new LocalInterestType(), $localInterest);
		$photoForm = $this->getUploadLocalInterestPhoto();
		
		if ($request->getMethod() == 'POST') {
			$form->bind($request);
	        if ($form->isValid()) {
				$data['status'] = 'SUCCESS';
				$profileService = $this->get('buggl_main.profile_service');
				$localInterest = $profileService->saveInterest($localInterest,$localAuthor);
				
				$temp = $this->container->get('kernel')->getRootdir().'/../web/uploads/local_interest_temp/'.$localInterest->getImageFilename();
				$actual = $this->container->get('kernel')->getRootdir().'/../web/uploads/local_interest/'.$localInterest->getImageFilename();
				@rename($temp, $actual);
				
				$data['list'] = $this->renderView('BugglMainBundle:LocalAuthor\Profile:localInterestList.html.twig', array('localInterests' => array($localInterest)));
				$data['idToRemove'] = $localInterest->getId();
			}
			else{
				$data['status'] = 'ERROR';
				$photoPath = '/uploads/local_interest_temp/'.$form->getData()->getImageFilename();
				$data['html'] = $this->renderView('BugglMainBundle:LocalAuthor\Profile:localInterestForm.html.twig', array('localInterestForm' => $form->createView(), 'photoForm' => $photoForm->createView(), 'photoPath' => $photoPath, 'action' => $action, 'id' => $id));
			}
		}
		else{
			return $this->render('BugglMainBundle:LocalAuthor\Profile:localInterestForm.html.twig', array('localInterestForm' => $form->createView(), 'photoForm' => $photoForm->createView(), 'photoPath' => $localInterest->getImageWebPath(), 'action' => $action, 'id' => $id));
		}

		return new JsonResponse($data,200,array("Content-Type" => "text/plain"));
	}

	public function deleteLocalInterestAction(Request $request)
	{
		$localInterestId = $request->get('localInterestId',0);
		$profileService = $this->get('buggl_main.profile_service');
		$profileService->deleteInterestById($localInterestId);

		$data['idToRemove'] = $localInterestId;
		return new JsonResponse($data,200);
	}

	public function uploadLocalInterestPhotoAction(Request $request)
	{
		$data = array(
			'status' => 'ERROR'
		);
		
		$form = $this->getUploadLocalInterestPhoto();
		
		if ($request->isMethod("POST")) {
			$form->bind($request);
			if ($form->isValid()) {
				$files = $request->files->get('form');
				$photo = $files['file'];
				$extension = $photo->guessExtension();
				 // extension cannot be guessed
				if (!$extension)
				    $extension = 'bin';

				$tempPath = 'uploads/local_interest_temp';
		        if(!file_exists($tempPath))
		            mkdir($tempPath,0777,true);

				$uploadRootDir = $this->container->get('kernel')->getRootdir().'/../web/'.$tempPath;
				$filename = sha1(uniqid(mt_rand(), true)).'.'.$extension;
				$photo->move($uploadRootDir, $filename);
		        $webPath = 'http://'.$request->getHost().'/'.$tempPath.'/'.$filename;
				
				$data = array(
					'status' => 'SUCCESS',
					'filename' => $filename,
					'webPath' => $webPath
				);
			}
			else{
				if(strpos($form->getErrorsAsString(),'uploaded file was too large')){
					$data = null;
				}
			}
		}
		
		return new JsonResponse($data,200,array("Content-Type" => "text/plain"));
	}
	
	public function getUploadLocalInterestPhoto()
	{
		return $this->createFormBuilder()->add('file', 'file')->getForm();
	}
	
	
	/*
	 * Local Interest ends here
	 */

	/*
	 * Travel Info starts here
	 */

	public function updateTravelInfoAction(Request $request)
	{
		$fieldId = $request->get('fieldId');

		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$travelInfo = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:TravelInfo')->getByLocalAuthor($localAuthor,true);
		$form = $this->createForm(new TravelInfoType(), $travelInfo);

		if ($request->getMethod() == 'POST') {
			$form->bind($request);
	        if ($form->isValid()) {
				$data['status'] = 'SUCCESS';
				$profileService = $this->get('buggl_main.profile_service');

				$travelInfo = $profileService->updateTravelInfo($travelInfo,$localAuthor);
				$data['html'] = $this->renderView('BugglMainBundle:LocalAuthor/Profile:travelInfo.html.twig', array('fieldId' => $fieldId, 'travelInfo' => $travelInfo));
				$data['idToRemove'] = $fieldId;
			}
			else{
				$data['status'] = 'ERROR';
				// this does not work as expected, we may need to create a service to get form errors
				$data['errors'] = array();
			}
		}

		return new JsonResponse($data,200);

	}

	public function getTravelInfoFormAction(Request $request)
	{
		$data['status'] = 'ERROR';
		$fieldId = $request->get('fieldId');

		$travelInfo = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:TravelInfo')->getByLocalAuthor($this->get('security.context')->getToken()->getUser());
		$travelInfoForm = $this->createForm(new TravelInfoType(), $travelInfo);

		return $this->render('BugglMainBundle:LocalAuthor\Profile:updateTravelInfoForm.html.twig', array('travelInfoForm' => $travelInfoForm->createView(), 'fieldId' => $fieldId));
	}

	public function uploadProfilePicAction(Request $request)
	{
		//var_dump($request->request->all());
		$photo = $request->files->get('profilePic');
		$extension = $photo->guessExtension();
		 // extension cannot be guessed
		if (!$extension)
		    $extension = 'bin';

		$tempPath = 'uploads/profilepic_temp';
        if(!file_exists($tempPath))
            mkdir($tempPath,0777,true);

		$uploadRootDir = $this->container->get('kernel')->getRootdir().'/../web/'.$tempPath;
		$filename = sha1(uniqid(mt_rand(), true)).'.'.$extension;
		$photo->move($uploadRootDir, $filename);
        $webPath = 'http://'.$request->getHost().'/'.$tempPath.'/'.$filename;

        $src = $uploadRootDir . "/" . $filename;
        $size = getimagesize($src);
		$width = $size[0];
		$height = $size[1];

        $response = array('url' => $webPath, 'filename' => $tempPath.'/'.$filename, 'tempPath' => $tempPath, 'width' => $width, 'height' => $height);
        // $response = $request->files->get('travel-guide-photo');
        return new JsonResponse($response,200,array("Content-Type" => "text/plain"));
	}

	public function cropProfilePicAction(Request $request)
	{
		$jpeg_quality = 90;
		$filename = $request->get('filename');
		$x = $request->get('x-coord');
		$y = $request->get('y-coord');
		$targ_w = $request->get('width');
		$targ_h = $request->get('height');
		$pathInfo = pathinfo($filename);

		$width = 285;
		$height = 285;
		$permanentPath = 'uploads/profile_pics';

		$src = $this->container->get('kernel')->getRootdir(). '/../web/'.$filename;
		$img_r = null;
		if($pathInfo['extension'] == 'jpeg')
			$img_r = imagecreatefromjpeg($src);
		else if($pathInfo['extension'] == 'png')
			$img_r = imagecreatefrompng($src);
		else if($pathInfo['extension'] == 'gif')
			$img_r = imagecreatefromgif($src);

		$dst_r = ImageCreateTrueColor($width,$height);
		imagecopyresampled($dst_r,$img_r,0,0,$x,$y,$width,$height,$targ_w,$targ_h);

		$pathInfo = pathinfo($filename);
		$newFilename = sha1(uniqid(mt_rand(), true)).'.'.$pathInfo['extension'];
		$target = $this->container->get('kernel')->getRootdir(). '/../web/'.$permanentPath.'/'.$newFilename;
		imagejpeg($dst_r, $target, $jpeg_quality);

		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$profile = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Profile')->getByLocalAuthor($localAuthor, true);
		$profile->removeUpload();
		$profile->setProfilePic($newFilename);

		$profileService = $this->get('buggl_main.profile_service');
		$profile = $profileService->updateProfile($profile,$localAuthor);
		
		$streetCreditService = $this->get('buggl_main.street_credit');
		$streetCreditService->updateProfileStatus($localAuthor);

		@unlink($src);
        $response = array('filename' => $profile->getImageWebPath(), 'url' => '/'.$profile->getImageWebPath());
		return new JsonResponse($response,200,array("Content-Type" => "text/plain"));
	}

	/*
	 * Travel Info ends here
	 */

	public function localTravelGuidesMapAction(Request $request)
	{
		$localAuthor = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalAuthor')->findOneBy(array('id' => $request->get('localAuthorId',0)));
		$location = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:Location')->getByLocalAuthor($localAuthor);
		$eGuides = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EGuide')->findByLocalAuthor($localAuthor);

		$constants = $this->get('buggl_main.constants');
		$googleMapsApiKey = $constants->get('google_maps_api_key');

		return $this->render('BugglMainBundle:LocalAuthor\Profile:localTravelGuidesMap.html.twig', array('city' => $location->getCity(), 'eGuides' => $eGuides, 'googleMapsApiKey' => $googleMapsApiKey));
	}


	public function SaveEGuideAction($id)
	{

		 $blog = $this->getBlog($blog_id);

        $comment  = new Comment();
        $comment->setBlog($blog);
        $request = $this->getRequest();
        $form    = $this->createForm(new CommentType(), $comment);
        $form->bindRequest($request);

        if ($form->isValid()) {
		            $em = $this->getDoctrine()
		                       ->getEntityManager();
		            $em->persist($comment);
		            $em->flush();

		}
	}

}