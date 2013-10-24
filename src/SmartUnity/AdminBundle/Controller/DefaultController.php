<?php

namespace SmartUnity\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SmartUnityAdminBundle:Default:index.html.twig');
    }
}
