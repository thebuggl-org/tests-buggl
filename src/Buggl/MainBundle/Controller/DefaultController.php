<?php

namespace Buggl\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BugglMainBundle:Default:index.html.twig', array('name' => $name));
    }
}
