<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Buggl\MainBundle\Form\Type\StaticContentType;
use Buggl\MainBundle\Entity\StaticContent;

class ContentController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
    public function indexAction()
    {
		$staticPages = $this->getDoctrine()->getRepository('BugglMainBundle:StaticContent')->findAll();

		$form = $this->createForm(new StaticContentType(), new StaticContent());

		$data = array(
			'staticPages' => $staticPages,
			'form' => $form->createView()
		);

        return $this->render('BugglMainBundle:Admin/Content:content.html.twig', $data);
    }



	public function EditAction(Request $request)
	{
		$id=$request->get('contentId',0);
		$staticContent = $this->getDoctrine()->getRepository('BugglMainBundle:StaticContent')->findOneById($id);

		if(is_null($staticContent))
			$staticContent = new StaticContent();

			$form = $this->createForm(new StaticContentType(), $staticContent);

			$status = 'error';
		if($request->isMethod('POST'))
		{
		    $form->bind($request);
			if($form->isValid())
			{
				$staticContent = $form->getData();
				$this->getDoctrine()->getEntityManager()->persist($staticContent);
				$this->getDoctrine()->getEntityManager()->flush();
				$status = 'success';
				return $this->redirect($this->generateUrl('admin_content'));
			}
		}

		 return $this->render('BugglMainBundle:Admin/Content:contentEdit.html.twig',array('form'=>$form->createView(),'status'=>$status,'actionUrl'=>$this->generateUrl('admin_content_edit',array('contentId'=>$id)))
		);
	}

	public function addAction(Request $request)
	{

		$form = $this->createForm(new StaticContentType(), new StaticContent());

		$status = 'error';
		if($request->isMethod('POST')){
	        $form->bind($request);
			if($form->isValid()){
				$staticContent = $form->getData();
				$this->getDoctrine()->getEntityManager()->persist($staticContent);
				$this->getDoctrine()->getEntityManager()->flush();
				$status = 'success';
				return $this->redirect($this->generateUrl('admin_content'));
			 
			}
		}

	  return $this->render('BugglMainBundle:Admin/Content:contentAdd.html.twig',array('form'=>$form->createView(), 'status'=>$status,'actionUrl'=>$this->generateUrl('admin_content_add'))
		);

	}


	public function deleteAction(Request $request)
	{
		$id = $request->get('contentId',0);
		$staticContent = $this->getDoctrine()->getRepository('BugglMainBundle:StaticContent')->findOneById($id);
		if(!is_null($staticContent)){
			$this->getDoctrine()->getEntityManager()->remove($staticContent);
			$this->getDoctrine()->getEntityManager()->flush();
		}

		return new RedirectResponse($this->generateUrl('admin_content'));
	}
}