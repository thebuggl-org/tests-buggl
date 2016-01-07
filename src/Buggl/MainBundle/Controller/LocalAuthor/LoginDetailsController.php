<?php

namespace Buggl\MainBundle\Controller\LocalAuthor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormError;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class LogInDetailsController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{

	public function indexAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$hasOldPassword = !is_null($localAuthor->getPassword());
		$emailVerification = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EmailVerification')->findPendingByUser($localAuthor);
		
		$emailForm = $this->createFormBuilder()
		             		 ->add('email', 'email', array('constraints' => (array(new NotBlank(array('message' => 'Required')), new Email())), 'data' => $localAuthor->getEmail()))
		             		 ->getForm();

		$passwordFormBuilder = $this->createFormBuilder();
		if($hasOldPassword){
			$passwordFormBuilder->add('oldPassword', 'password', array('constraints' => new NotBlank(array('message' => 'Required'))));
		}
		$passwordFormBuilder->add('newPassword', 'repeated', array('type' => 'password', 'options' => array('max_length' => 20, 'constraints' => new NotBlank(array('message' => 'Required'))), 'invalid_message' => 'Passwords did not match!'));
		$passwordForm = $passwordFormBuilder->getForm();

		if($request->isMethod('POST')){
			if($request->get('updateId') == 'email'){
				$emailForm->bindRequest($request);
				if($emailForm->isValid()){
					$data = $emailForm->getData();
					$emailVerificationService = $this->get('buggl_main.email_verification_service');
					$emailVerification = $emailVerificationService->requestEmailUpdate($data['email'],$localAuthor);
					$emailVerificationService->sendRequestEmailUpdateConfirmation($emailVerification);
					
					$this->get('session')->getFlashBag()->add('success', "You have a pending email update request. Please check your email.");
					return new RedirectResponse($this->generateUrl('local_author_login_details'));
				}
			}
			else if($request->get('updateId') == 'password'){
				$passwordForm->bindRequest($request);
				if($passwordForm->isValid()){
					$data = $passwordForm->getData();
					$localAuthorService = $this->get('buggl_main.local_author_service');
					if($hasOldPassword){
						$localAuthor = $localAuthorService->changePassword($localAuthor,$data['oldPassword'],$data['newPassword']);
						if(!$localAuthor){
							$passwordForm->get('oldPassword')->addError(new FormError('Incorrect Password'));
						}
						else{
							$this->get('session')->getFlashBag()->add('success', "Password set.");
							return new RedirectResponse($this->generateUrl('local_author_login_details'));
						}
					}
					else{
						$localAuthor = $localAuthorService->setPassword($localAuthor,$data['newPassword']);
						$this->get('session')->getFlashBag()->add('success', "Password set.");
						return new RedirectResponse($this->generateUrl('local_author_login_details'));
					}
				}
			}
		} 
		$newEGuideRequestCount = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:MessageToUser')->countRequestByStatus($localAuthor,array('0'=>'0'));
		$data = array(
			'emailForm' => $emailForm->createView(),
			'passwordForm' => $passwordForm->createView(),
			'emailVerification' => $emailVerification,
			'newRequestCount' =>$newEGuideRequestCount,
			'hasOldPassword' => $hasOldPassword
		);

		return $this->render('BugglMainBundle:LocalAuthor\LoginDetails:loginDetails.html.twig',$data);
	}

	public function confirmEmailUpdateAction(Request $request)
	{
		$token = urldecode($request->get('token'));
		$user = $this->get('security.context')->getToken()->getUser();

		$emailVerificationService = $this->get('buggl_main.email_verification_service');
		$emailVerification = $emailVerificationService->verifyRequest($token, $user);
		if(!is_null($emailVerification)){
			$emailVerificationService->updateVerifiedEmail($emailVerification);
		}
		else{
			return new RedirectResponse($this->generateUrl('local_author_update_email_failed'));
		}

		return new RedirectResponse($this->generateUrl('local_author_login_details'));
	}

	public function resendEmailUpdateAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$emailVerification = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EmailVerification')->findByUser($localAuthor);
		$emailVerificationService = $this->get('buggl_main.email_verification_service');
		$emailVerificationService->sendRequestEmailUpdateConfirmation($emailVerification);

		return new RedirectResponse($this->generateUrl('local_author_login_details'));
	}

	public function cancelEmailUpdateAction(Request $request)
	{
		$localAuthor = $this->get('security.context')->getToken()->getUser();
		$emailVerification = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:EmailVerification')->findByUser($localAuthor);
		$emailVerificationService = $this->get('buggl_main.email_verification_service');
		$emailVerificationService->cancelRequestEmailUpdate($emailVerification);

		return new RedirectResponse($this->generateUrl('local_author_login_details'));
	}

	public function confirmEmailUpdateFailedAction(Request $request)
	{
		return $this->render('BugglMainBundle:LocalAuthor\LoginDetails:updateEmailFailed.html.twig');
	}
}