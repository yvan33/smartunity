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

        if ($request->getMethod =="Post") {

            $em=$this->getDoctrine()->getManager();
            $membre=$em->getRepository('SmartunityApp:Membre')->find($request->request->get('id')) ;
            $membre->setprefmp($request->request->get('mp'));
            $em->flush();
        }

        return $this->redirect($this->show('register.html.twig'));

    }
}
