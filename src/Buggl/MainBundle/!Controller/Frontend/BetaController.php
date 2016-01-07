<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class for beta related controllers
 */
class BetaController extends Controller
{
    /**
     * Controller for beta invite login
     * @param Request  $request
     *
     * @return Response
     */
    public function betaLoginAction(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $email = $request->request->get('email');
            $token = $request->request->get('token');
			$agree = $request->request->get('agree');
			
			if(!is_null($agree) && $agree == 'on'){
	            $service = $this->get('buggl_main.beta_login_service');
	            $service->authenticate($email, $token);

	            if ($service->isAllowed()) {
	                $url = $this->generateUrl('buggl_homepage');

	                return new RedirectResponse($url);
	            } else {
	                $request->getSession()->setFlash('error', 'Not allowed',false);
	            }
			}
			else{
	            $request->getSession()->setFlash('error', 'You must agree to the terms and conditions.',false);
			}
        }

        return $this->render('BugglMainBundle:Beta:login.html.twig');
    }
	
	public function requestTokenAction(Request $request)
	{
		$builder = $this->createFormBuilder();
		$builder->add('firstName', 'text',array('constraints' => array(new NotNull(array('message'=>'Required')))));
		$builder->add('lastName', 'text',array('constraints' => array(new NotNull(array('message'=>'Required')))));
		$builder->add('email', 'email',array('constraints' => array(new NotNull(array('message'=>'Required')),new Email(array('message'=>'Invalid email address.')))));
		$form = $builder->getForm();
		
		if($request->isMethod('POST')){
			$form->bind($request);
			if($form->isvalid()){
				$registrationService = $this->get('buggl_main.registration_service');
				$registrationService->sendBetaTokenRequest($form->getData());
				$request->getSession()->setFlash('success', "Your request has been sent. We'll respond to it shortly.",true);
				return new RedirectResponse($this->generateUrl('buggl_beta_login'));
			}
			else{
				$request->getSession()->setFlash('error', 'Not allowed',false);
			}
		}
		
		return $this->render('BugglMainBundle:Beta:requestToken.html.twig', array('form' => $form->createView()));
	}
}