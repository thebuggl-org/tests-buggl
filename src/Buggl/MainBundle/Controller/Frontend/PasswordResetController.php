<?php

namespace Buggl\MainBundle\Controller\Frontend;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints\Email;
use Buggl\MainBundle\Event\PasswordResetEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

class PasswordResetController extends Controller
{
    public function indexAction(Request $request)
	{
		$context = $request->get('userType');
		$form = $this->createFormBuilder()
		             ->add('email', 'email', array('constraints' => new Email()))
		             ->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	        if ($form->isValid()) {
				$passwordResetService = $this->get('buggl_main.password_reset_service');
				$constants = $this->get('buggl_main.constants');
				$data = $form->getData();
				$user = $user = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:LocalAuthor')->findOneByEmail($data['email']);
				if(is_null($user)){
					$form->get('email')->addError(new FormError('The email address does not exist or is not registered.'));
				}
				else{
					$passwordResetData = array(
						'user' => $user,
						'email' => $data['email']
					);

					$passwordResetInfo = $passwordResetService->savePasswordResetInfo($passwordResetData);

					$event = new PasswordResetEvent($passwordResetInfo,$data['email']);
	                $this->get('event_dispatcher')->dispatch('buggl.password_reset_request',$event);

					return new RedirectResponse($this->generateUrl('buggl_password_reset_request_sent',array('requestId' => $passwordResetInfo->getId())));
				}
			}
		}

		return $this->render('BugglMainBundle:Frontend/PasswordReset:passwordResetRequest.html.twig', array('form' => $form->createView()));
	}

	public function resetPasswordRequestSentAction(Request $request)
	{
		$requestId = $request->get('requestId');

		$passwordResetInfo = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:PasswordResetInfo')->findOneBy(array('id' => $requestId));

		if(is_null($passwordResetInfo) || $passwordResetInfo->getToken() == ''){
			return new RedirectResponse($this->generateUrl('buggl_homepage'));
		}

		$user = $passwordResetInfo->getUser();
		$recipientEmail = $user->getEmail();

		return $this->render('BugglMainBundle:Frontend/PasswordReset:passwordResetRequestSent.html.twig', array('recipientEmail' => $recipientEmail, 'requestId' => $requestId));
	}

	public function resetPasswordAction(Request $request)
	{
		$token = urldecode($request->get('token'));

		$passwordResetService = $this->get('buggl_main.password_reset_service');
		if(!$passwordResetService->validateToken($token)){
			return new RedirectResponse($this->generateUrl('buggl_password_reset_invalid'));
		}

		$form = $this->createFormBuilder()
		             ->add('email', 'email', array('constraints' => new Email(array('message' => 'Not a valid email address.'))))
					 ->add('password', 'repeated', array('type' => 'password', 'options' => array('max_length' => 20), 'invalid_message' => 'Passwords did not match!'))
		             ->getForm();

		if ($request->getMethod() == 'POST') {
			$form->bindRequest($request);
	        if ($form->isValid()) {
				$data = $form->getData();
				if($passwordResetService->matchEmails($token,$data['email'])){
					$encoder = new MessageDigestPasswordEncoder();
					$data['password'] = $encoder->encodePassword($data['password'],'');

					$constants = $this->get('buggl_main.constants');
					$user = $passwordResetService->updatePassword($data,$constants->get('LOCAL_AUTHOR'));
					$passwordResetService->invalidatePasswordResetInfo($token);

					$token = new UsernamePasswordToken($user, null, $user->getFireWall(), $user->getRoles());
					$this->get('security.context')->setToken($token);

					try {
				        $request = $this->container->get('request')->getSession()->set('_security_secured_area', serialize($token));
				    } catch(InactiveScopeException $e) {}

					return new RedirectResponse($this->generateUrl('local_author_dashboard'));
				}
				else{
					$form->get('email')->addError(new FormError('The email address does not match the email you used to request password reset.'));
				}
			}
		}

		return $this->render('BugglMainBundle:Frontend/PasswordReset:passwordReset.html.twig', array('form' => $form->createView()));
	}

	public function resendRequestAction(Request $request)
	{
		$requestId = $request->get('requestId');
		$passwordResetInfo = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:PasswordResetInfo')->findOneBy(array('id' => $requestId));

		if(is_null($passwordResetInfo) || $passwordResetInfo->getToken() == ''){
			throw $this->createNotFoundException('The page does not exist');
		}

		$passwordResetService = $this->get('buggl_main.password_reset_service');
		$user = $passwordResetInfo->getUser();

		$event = new PasswordResetEvent($passwordResetInfo,$user->getEmail());
        $this->get('event_dispatcher')->dispatch('buggl.password_reset_request',$event);

		return new RedirectResponse($this->generateUrl('buggl_password_reset_request_sent',array('requestId' => $passwordResetInfo->getId())));
	}

	public function invalidTokenAction(Request $request)
	{
		$userType = $request->get('userType');

		return $this->render('BugglMainBundle:Frontend/PasswordReset:invalidToken.html.twig',array('userType' => $userType));
	}
}