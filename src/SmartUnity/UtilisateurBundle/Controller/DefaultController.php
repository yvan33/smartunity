<?php

namespace SmartUnity\UtilisateurBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
       
        $form=$this->createForm('smartunity_user_preference', $user);

        return $this->render('SmartUnityUtilisateurBundle:Profile:show.html.twig', array('form'=> $form->createView()));
    }

    public function setPrefAction(){

            $em=$this->getDoctrine()->getManager();
            $user = $this->container->get('security.context')->getToken()->getUser();
            $form=$this->createForm('smartunity_user_preference', $user);
            $form->bind($this->getRequest());

           if($form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush(); 
            }
        
        return $this->redirect($this->generateUrl('smart_unity_utilisateur_homepage'));

    }
}
