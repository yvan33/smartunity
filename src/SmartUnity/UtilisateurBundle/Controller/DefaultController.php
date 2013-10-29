<?php

namespace SmartUnity\UtilisateurBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SmartUnityUtilisateurBundle:Profile:show.html.twig');
    }

    public function setPrefAction(){

        	$request = $this->get('request');
/*        	$user=$this->container->get('security.context')->getToken()->getUser();*/

        if ($request->getMethod() =="POST") {

            $em=$this->getDoctrine()->getManager();            
            $membre=$em->getRepository('SmartUnityAppBundle:Membre')->find($request->request->get('id'));
            $membre->setprefmp($request->request->get('mp'));
            $membre->setprefsmartcafe($request->request->get('smartcafe'));
            $membre->setprefcomm($request->request->get('comm'));
            $membre->setprefrep($request->request->get('rep'));
            $membre->setprefrepValidee($request->request->get('repval'));
            $membre->setprefrepCertifiee($request->request->get('repcert'));
            $em->flush();
        }
        
        return $this->indexAction();

    }
}
