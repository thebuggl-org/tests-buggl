<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

class BetaInviteController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{

	public function indexAction(Request $request)
	{
		$active = $request->get('type');

		$data = array(
			'activeTab' => $active
		);

		return $this->render('BugglMainBundle:Admin\BetaInvite:index.html.twig',$data);
	}

	public function inviteAction(Request $request)
	{
		$data = array();
		$emailForm = $this->createFormBuilder()
		             	  ->add('email', 'email', array('constraints' => (array(new NotBlank(array('message' => 'Required')), new Email()))))
		             	  ->getForm();

		if($request->isMethod('POST')){
			$emailForm->bind($request);
			if($emailForm->isValid()){
				$betaInviteService = $this->get('buggl_main.beta_invite_service');
				$user = $this->get('security.context')->getToken()->getUser();
				$betaInvite = $betaInviteService->saveBetaInvite($emailForm->getData(),$user);
				$betaInviteService->sendBetaInviteEmail($betaInvite);
				$data['status'] = 'SUCCESS';
				$this->get('session')->getFlashBag()->add('success', "You have successfully invited ".$betaInvite->getEmail().".");
			}
			else{
				$data['status'] = 'ERROR';
				$data['html'] = $this->renderView('BugglMainBundle:Admin\BetaInvite:form.html.twig',array('form' => $emailForm->createView()));
			}
		}
		else{
			$data['html'] = $this->renderView('BugglMainBundle:Admin\BetaInvite:form.html.twig',array('form' => $emailForm->createView()));
		}

		return new JsonResponse($data,200);
	}

	public function inviteListAction(Request $request)
	{
		$active = $request->get('type');
		$constants = $this->get('buggl_main.constants');

		$limit = $constants->get('beta_invite_pagination');
		$currentPage = $request->get('currentPage',1);

		$offset =  $currentPage > 0 ? ($currentPage-1) * $limit : 0;
		$invites = $this->getDoctrine()->getEntityManager()
										  ->getRepository('BugglMainBundle:BetaInvite')
										  ->retrieveInvitesByStatus($constants->get('BETA_INVITE_'.strtoupper($active)),$offset,$limit);

		$softPageLimit = 8;
		$hardPageLimit = 12;

		$data = array(
			'activeTab' => $active,
			'invites' => $invites,
			'itemLimit' => $limit,
			'currentPage' => $currentPage,
			'softPageLimit' => $softPageLimit,
			'hardPageLimit' => $hardPageLimit,
			'dataUrl' => $this->generateUrl('admin_beta_invite_pagination',array('type'=>$active))
		);

		return $this->render('BugglMainBundle:Admin\BetaInvite:list.html.twig', $data);
	}

	public function resendAction(Request $request)
	{
		$id = $request->get('id',0);

		$invite = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:BetaInvite')->findOneById($id);
		if(!is_null($invite)){
			$betaInviteService = $this->get('buggl_main.beta_invite_service');
			$betaInviteService->sendBetaInviteEmail($invite, true);
			$this->get('session')->getFlashBag()->add('success', "You have successfully resent invitaion to ".$invite->getEmail().".");
		}
		else{
			$this->get('session')->getFlashBag()->add('error', "There was an error. The request could not be processed succesfully.");
		}

		return new RedirectResponse($this->generateUrl('admin_beta_invite_list',array('type'=>'pending')));
	}

	public function deleteAction(Request $request)
	{
		$id = $request->get('id',0);

		$invite = $this->getDoctrine()->getEntityManager()->getRepository('BugglMainBundle:BetaInvite')->findOneById($id);
		if(!is_null($invite)){
			$this->get('session')->getFlashBag()->add('success', "You have successfully deleted invaitaion to ".$invite->getEmail().".");
			$betaInviteService = $this->get('buggl_main.beta_invite_service');
			$betaInviteService->deleteBetaInvite($invite);
		}
		else{
			$this->get('session')->getFlashBag()->add('error', "There was an error. The request could not be processed succesfully.");
		}

		return new RedirectResponse($this->generateUrl('admin_beta_invite_list',array('type'=>'pending')));
	}
}