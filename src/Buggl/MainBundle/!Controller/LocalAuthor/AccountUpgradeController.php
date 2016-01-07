<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AccountUpgradeController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
	public function upgradeAction(Request $request)
	{
		$redirect = $request->get('redirect');
		// echo $redirect;
		$user = $this->get('security.context')->getToken()->getUser();
		// echo $user->getID();
		if( $user AND !$user->getIsLocalAuthor() )
		{
			$user->setIsLocalAuthor(true);

			$em = $this->getDoctrine()->getEntityManager();
			$em->persist($user);
			$em->flush();
		}
		
		// $route = $this->getRefererRoute();
		// echo $route;
		return $this->redirect($redirect);
		// exit;
		// if( "buggl_write_a_guide" == $route )
		// 	return new RedirectResponse( $this->generateUrl('add_travel_guide_info') );
		// else 
		// 	return new RedirectResponse( $this->generateUrl('local_author_dashboard') );

		
	}

	// private function getRefererRoute()
	// {
	// 	$request = $this->getRequest();

	// 	//look for the referer route
	// 	$referer = $request->headers->get('referer');
	// 	$lastPath = substr($referer, strpos($referer, $request->getBaseUrl()));
	// 	$lastPath = str_replace($request->getBaseUrl(), '', $lastPath);

	// 	$matcher = $this->get('router')->getMatcher();
	// 	$parameters = $matcher->match($lastPath);
	// 	$route = $parameters['_route'];

	// 	return $route;
	// }
}
