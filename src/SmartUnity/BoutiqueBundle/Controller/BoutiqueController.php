<?php

namespace SmartUnity\BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BoutiqueController extends Controller
{
    public function indexAction()
    {
        return $this->render('SmartUnityBoutiqueBundle::boutique2.html.twig');
    }
}
