<?php

namespace Buggl\MainBundle\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
{
	protected $router;
  	protected $security;

	public function __construct(Router $router, SecurityContext $security)
	{
        $this->router = $router;
        $this->security = $security;
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token)
	{
		$session = $request->getSession();
		if ($session->has('redirect')) {
			$url = $session->remove('redirect');
		} else {
			$url = $request->headers->get('referer');
		}



		// check for homepage login
		$homepageUrl = $this->router->generate('buggl_homepage',array(),true);
		$url =  $homepageUrl == $url ? $this->router->generate('local_author_dashboard') : $url;

		// // check for write a guide
		// $writeGuideUrl = $this->router->generate('buggl_write_a_guide',array(),true);
		// $url = $writeGuideUrl == $url ? $this->router->generate('add_travel_guide_info') : $url;

		$response = new RedirectResponse($url);

		return $response;
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		$session = $request->getSession();
		if (!$session->has('redirect')) {
			$session->set('redirect', $request->headers->get('referer'));
		}

       	$url = $this->router->generate('login');

		$response = new RedirectResponse($url);

		$request->getSession()->setFlash('error',$exception->getMessage());

		return $response;
	}
}
