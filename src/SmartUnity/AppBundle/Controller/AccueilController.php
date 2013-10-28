<?php

namespace SmartUnity\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

class AccueilController extends Controller
{
    public function indexAction()
    {
        
        return $this->render('SmartUnityAppBundle::Accueil.html.twig');
        
    }
    
}
