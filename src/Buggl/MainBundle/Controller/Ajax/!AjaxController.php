<?php

namespace Buggl\MainBundle\Controller\Ajax;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\JsonResponse;


class AjaxController extends Controller 
{
	public function getCountryCitiesAction(Request $request)
	{
		$countryID = $request->get('countryID');

		$repo = $this->getDoctrine()->getRepository('BugglMainBundle:City');
		$cities = $repo->getByCountryID($countryID, true);
		
		return new JsonResponse($cities,200);
	}

	public function getCountryCategoriesAction(Request $request)
	{
		$countryID = $request->get('countryID');

		$repo = $this->getDoctrine()->getRepository('BugglMainBundle:CategoryToCountry');
		$categoryToCountry = $repo->findByCountry($countryID);
		
		$response = array();
		foreach ($categoryToCountry as $obj)
		{
			$category = $obj->getCategory();
			$response[] = array('id' => $category->getId(), 'name' => $category->getName());
		}

		return new JsonResponse($response,200); 
	}

	public function getSpotCategoriesAction(Request $request)
	{
		$type = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findOneById($request->get('type_id'));
		$localAuthor = $this->get('security.context')->getToken()->getUser();

		// $categories = $this->getDoctrine()->getRepository('BugglMainBundle:SpotCategory')->findBy(array('spotType' => $type, 'status' => 1));
		$categories = $this->getDoctrine()->getRepository('BugglMainBundle:SpotCategory')->findBySpotType($type, $localAuthor);
		$response = array();
		foreach($categories as $iObj)
		{
			$response[] = array('id' => $iObj->getId(), 'name' => $iObj->getName());
		}

		return new JsonResponse($response, 200);
	}

	public function getSpotLikesAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$type = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findOneById($request->get('type_id'));
		// $likes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotLike')->findBy(array('spotType' => $type, 'status' => 1));
		$likes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotLike')->findBySpotType($type, $localAuthor);
		$response = array();
		foreach($likes as $iObj)
		{
			$response[] = array('id' => $iObj->getId(), 'name' => $iObj->getName());
		}

		return new JsonResponse($response, 200);
	}

	public function getSpotLikesAndCategoriesAction(Request $request)
	{
		$type = $this->getDoctrine()->getRepository('BugglMainBundle:SpotType')->findOneById($request->get('type_id'));
		$categories = $this->getDoctrine()->getRepository('BugglMainBundle:SpotCategory')->findBySpotType($type);
		$likes = $this->getDoctrine()->getRepository('BugglMainBundle:SpotLike')->findBySpotType($type);
	}

	public function getCountryListAction(Request $request)
	{
		$term = $request->get('term');
		$countries = $this->getDoctrine()->getRepository('BugglMainBundle:Country')->searchByName($term);
		$response = array();
		if(count($countries))
		{
			foreach($countries as $iObj)
			{
				$response[] = array('label' => $iObj->getName(), 'id' => $iObj->getId());
			}
		}
		else {
			$response[] = array('label' => 'No Record Found', 'id' => 0);	
		}
		
		
		return new JsonResponse($response, 200);
	}

	public function getInterestsAction(Request $request)
	{
		$term = $request->get('term');
		$interests = $this->getDoctrine()->getRepository("BugglMainBundle:Category")->searchByName($term);
		$response = array();
		foreach($interests as $iObj)
		{
			$response[] = array('label' => $iObj->getName(), 'id' => $iObj->getId());
		}
		return new JsonResponse($response, 200);
	}

	public function googleCustomSearchAction(Request $request)
	{
		$form = $request->query->all();
		echo "<pre>";
		var_dump($form);
		// exit;
		$get_variables = "";
		$cnt = 0;
		foreach($form as $key => $value)
		{
			$x = ($cnt == 0) ? "?" : "&";
			$get_variables .= "$x".$key . "=" . $value;
			$cnt++;
		}

		// $ch = curl_init();
    	$url = "https://www.googleapis.com/customsearch/v1";
    	echo $url . $get_variables;
    	exit;
	    curl_setopt($ch, CURLOPT_URL, $url . $get_variables);
	    // curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //CURL doesn't like google's cert
	    // curl_setopt($ch, CURLOPT_POST, true);
	    // curl_setopt($ch, CURLOPT_POSTFIELDS, $form);
	    
	    // if(is_array($headers))
	    // {
	    //   curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	    // }
	    
	    $response = curl_exec($ch);
	    var_dump($response);
	    $code = curl_getinfo($ch,CURLINFO_HTTP_CODE);
	    
	    curl_close($ch);
	    
	    // return array('body'=>$response,'code'=>$code);

		return new JsonResponse($response, 200);
	}
	public function checkCountryAction(Request $request)
	{
		$term = $request->get('items');
		$countries = $this->getDoctrine()->getRepository('BugglMainBundle:Country')->findOneByCountry($term);
		$response = array();
		if($countries)
		{
				$response[] = array('label' => $countries->getName(), 'id' => $countries->getId());
		}
		else {
			$response[] = array('label' => 'No Record Found', 'id' => 0);	
		}
		
		
		return new JsonResponse($response, 200);
	}

}