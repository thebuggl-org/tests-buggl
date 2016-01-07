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

class AdminLoginHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface
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
		$url = $request->headers->get('referer');
		
		// TODO: FIX THIS
        $request->getSession()->set('buggl_beta_authenticated', true);
        $request->getSession()->set('beta_invite_email','admin@buggl.com');
        $request->getSession()->set('beta_invite_token','token');
		$request->getSession()->set('has_admin_access',1);

		$response = new RedirectResponse($url);

		return $response;
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
        $url = $this->router->generate('admin_home');
        
		$response = new RedirectResponse($url);

		$request->getSession()->setFlash('error',$exception->getMessage());

		return $response;
	}
}
