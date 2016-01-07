<?php

namespace Buggl\MainBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\RedirectResponse;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->forward('BugglMainBundle:Admin/Dashboard:index');
    }

    public function getCountryOptionsAction()
    {

        $data = array(
                'countries' => $this->get('buggl_main.entity_repository')
                                    ->getRepository('BugglMainBundle:Country')
                                    ->findAll()
            );

        return $this->render('BugglMainBundle:Admin/Main:countryOptions.html.twig',$data);
    }

    public function getCategoryOptionsAction()
    {

        $data = array(
                'categories' => $this->get('buggl_main.entity_repository')
                                     ->getRepository('BugglMainBundle:Category')
                                     ->findAll()
            );

        return $this->render('BugglMainBundle:Admin/Main:categoryOptions.html.twig',$data);
    }
}