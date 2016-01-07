<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
// use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * controller related to activity/interest
 *
 * @author    Vincent Farly G. Tabaoda <farly.taboada@goabroad.com>
 *
 * @copyright 2013 (c)  Buggl.com
 *
 * @version   Release: February
 */
class ActivityController extends Controller implements \Buggl\MainBundle\Interfaces\BugglSecuredPage
{
    /**
     * default controller activity/interest
     * @return Response html page
     */
    public function indexAction()
    {
        $template = 'BugglMainBundle:Admin/Main:activities.html.twig';

        $status = $this->get('buggl_main.constants')
                       ->get('published_category');

        $categories = $this->get('buggl_main.entity_repository')
                           ->getRepository('BugglMainBundle:Category')
                           ->findByStatusOrderByName($status);

        return $this->render($template,
            array('status' => 'published' , 'lists' => $categories));
    }

    /**
     * shows unpublished activity/interest
     *
     * @return Response html page
     */
    public function unpublishedAction()
    {
        $status = $this->get('buggl_main.constants')
                       ->get('unpublished_category');

        $categories = $this->get('buggl_main.entity_repository')
                           ->getRepository('BugglMainBundle:Category')
                           ->findByStatusOrderByName($status);

        return $this->render('BugglMainBundle:Admin/Main:activities.html.twig',
            array('status' => 'unpublished' , 'lists' => $categories));
    }

    /**
     * shows activty/interest added by local author
     *
     * @return Response html twig
     */
    public function localAuthorAddedAction()
    {
        $categories = $this->get('buggl_main.entity_repository')
                           ->getRepository('BugglMainBundle:Category')
                           ->findCustomAdded();

        return $this->render('BugglMainBundle:Admin/Main:activities.html.twig',
            array('status' => 'local author added' , 'lists' => $categories));
    }

    /**
     * publish/unpublish activiy/interest
     *
     * @return RedirectResponse
     */
    public function publishAction()
    {
        $request = $this->getRequest();

        $activityId = $request->get('id');
        $publish = $request->get('publish');

        $entityManager = $this->getDoctrine()->getEntityManager();

        $category = $this->get('doctrine.orm.entity_manager')
                         ->find('BugglMainBundle:Category', $activityId);

        $constants = $this->get('buggl_main.constants');

        $isPublish = $publish ? $constants->get('published_category') : $constants->get('unpublished_category');

        $category->setIsPublished($isPublish);

        $entityManager->persist($category);
        $entityManager->flush();

        $url = $publish ?
                $this->generateUrl('admin_activity_interest') :
                $this->generateUrl('admin_activity_interest_unpublished');

        return new RedirectResponse($url);
    }

    /**
     * saves activity/interest
     *
     * @return JsonResponse returns info of activity in json format
     */
    public function saveActivityAction()
    {
        $request = $this->getRequest();
        $service = $this->get('buggl_main.category');

        if ($request->getMethod()  == 'POST') {
            $form = $this->createFormBuilder()
                     ->add('name', 'text',
                         array(
                            'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()
                         ))
                     ->add('is_published', 'checkbox',
                         array(
                            'required' => false
                         ))
                     ->getForm();

            $form->bind($request);

            $valid = $form->isValid();

            if ($valid) {
                $data = $service->add($form->getData());
            } else {
                $template = 'BugglMainBundle:Admin\Main:activityForm.html.twig';
                $params = array('form'=>$form->createView());

                $html = $this->renderView($template, $params);
                $data = array('success' => $valid, 'html' => $html);
            }
        } else {
            $categoryId = $request->get('id');
            $name = trim($request->get('qString'));

            $data = $service->update($categoryId, $name);
        }
        $response = new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);

        return $response;
    }

    /**
     * gets form
     *
     * @return JsonResponse [html form in json format]
     */
    public function addFormAction()
    {
        $template = 'BugglMainBundle:Admin\Main:activityForm.html.twig';
        $form = $this->createFormBuilder()
                     ->add('name', 'text',
                         array(
                            'constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()
                         ))
                     ->add('is_published', 'checkbox')
                     ->getForm();

        $html = $this->renderView($template, array('form'=>$form->createView()));

        $data = array('html' => $html);

        $response = new \Symfony\Component\HttpFoundation\JsonResponse($data, 200);

        return $response;
    }
    // public function listActivitiesAction($status)
    // {
    //     $categories = $this->get('buggl_main.entity_repository')
    //                        ->getRepository('BugglMainBundle:Category')
    //                        ->findByStatusOrderByName($status);

    //     return $this->render('BugglMainBundle:Admin/Main:activityList.html.twig',array('lists' => $categories));
    // }
}