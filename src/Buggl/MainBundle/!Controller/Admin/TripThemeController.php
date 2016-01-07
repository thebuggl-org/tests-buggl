<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * controller related to trip themes
 *
 * @author    Vincent Farly G. Taboada <farly.taboada@goabroad.com>
 *
 * @copyright 2013 February (c) Buggl.com
 */
class TripThemeController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{

    /**
     * default controller shows list of trip themes
     *
     * @return Response html page
     */
    public function indexAction()
    {
        return $this->render('BugglMainBundle:Admin/TripTheme:triptheme.html.twig');
    }


    /**
     * controller for saving new trip theme
     *
     * @return JsonResponse newly saved trip theme
     */
    public function saveAction()
    {
        $request = $this->getRequest();
        $service = $this->get('buggl_main.trip_theme');
        $template = 'BugglMainBundle:Admin\TripTheme:form.html.twig';

        if ($request->getMethod() == 'POST') {
            $form = $this->createFormBuilder()
                     ->add('name', 'text',
                         array(
                            'constraints'=>new \Symfony\Component\Validator\Constraints\NotBlank()
                     ))
                     ->add('status','checkbox',array('required' => false))
                     ->getForm();
            $form->bind($request);

            $valid = $form->isValid();

            if ($valid) {
                $data = $service->add($form->getData());
            } else {
                $html = $this->renderView(
                    $template, array('form'=>$form->createView()));
                $data = array('success' => $valid, 'html' => $html);
            }
        } else {
            $id = $request->get('id');
            $name = trim($request->get('qString'));

            $data = $service->update($id, $name);
        }

        $response = new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);

        return $response;
    }

    /**
     * gets form for trip theme
     *
     * @return JsonResponse html form
     */
    public function addFormAction()
    {
        $template = 'BugglMainBundle:Admin\TripTheme:form.html.twig';
        $form = $this->createFormBuilder()
                     ->add('name', 'text',
                         array('constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()))
                     ->add('status','checkbox', array('required' => false))
                     ->getForm();

        $html = $this->renderView($template, array('form' => $form->createView()));

        $data = array('html'=>$html);

        return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
    }

    /**
     * shows trip themes
     *
     * @return Response html page
     */
    public function listTripThemeAction()
    {
        $template = 'BugglMainBundle:Admin/TripTheme:list.html.twig';

        $tripThemes = $this->get('buggl_main.entity_repository')
                           ->getRepository('BugglMainBundle:TripTheme')
                           ->findAll();

        $data  = array('lists'=>$tripThemes);

        return $this->render($template, $data);
    }

    /**
     * toggles status
     * @param  Request $request []
     * @return JSON             []
     */
    public function toggleAction(Request $request)
    {
        $id = $request->get('id');
        $currentStatus  = $request->get('status');

        $word = $currentStatus ? 'publish' : 'unpublish';
        $currentStatus = $currentStatus ? 0 : 1;

        $data = array(
            'word' => $word,
        );

        $entityManager = $this->getDoctrine()->getEntityManager();
        $tripTheme = $entityManager->find('BugglMainBundle:TripTheme',$id);

        if (is_null($tripTheme)) {
            $data['success'] = false;
            return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
        }

        try {
            $tripTheme->setStatus($currentStatus);
            $entityManager->flush();

            $data['success'] = true;
            $data['status'] = $currentStatus;
        } catch(Exception $e) {
            $data['success'] = false;
        }

        return new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);
    }
}