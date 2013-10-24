<?php

namespace SmartUnity\BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SmartUnityBoutiqueBundle:Default:index.html.twig');
    }
}
