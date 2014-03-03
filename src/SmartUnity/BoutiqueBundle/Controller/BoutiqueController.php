<?php

namespace SmartUnity\BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BoutiqueController extends Controller
{
    public function indexAction()
    {
           $giftRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:gift');
        $gifts = $giftRepository->findAll();
        
        return $this->render('SmartUnityBoutiqueBundle::boutique.html.twig');
//        return $this->render('SmartUnityBoutiqueBundle::boutique2.html.twig',array(
//                    'gifts' => $gifts));
    }

    public function confirmGiftAction()
    {
        
        return $this->render('SmartUnityBoutiqueBundle::giftConfirmation.html.twig');
    }
}
