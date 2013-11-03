<?php

namespace SmartUnity\UtilisateurBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {

        $user = $this->container->get('security.context')->getToken()->getUser();
       
        $form_pref=$this->createForm('smartunity_user_preference', $user);
//        $form_infos=$this->createForm('smartunity_user_informations', $user);
/*        $em = $this->getDoctrine()->getEntityManager();
        $q = $em->getRepository('SmartUnityAppBundle:question')->find(1);
         $question=count($q);
        $r=$q->getreponses();
        // $question=count($r);*/
        return $this->render('SmartUnityUtilisateurBundle:Profile:show.html.twig', array('form_pref'=> $form_pref->createView()));
    }

    public function indexWithEditInfosAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $form_pref=$this->createForm('smartunity_user_preference', $user);
        $form_infos=$this->createForm('smartunity_user_informations', $user);


        return $this->render('SmartUnityUtilisateurBundle:Profile:edit.html.twig', array('form_pref'=> $form_pref->createView(),'form_infos'=> $form_infos->createView() ));
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
    
    public function setInfosAction(){

            $em=$this->getDoctrine()->getManager();
            $user = $this->container->get('security.context')->getToken()->getUser();
            $form=$this->createForm('smartunity_user_informations', $user);
            $form->bind($this->getRequest());

           if($form->isValid()){

            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush(); 
            }
        
        return $this->redirect($this->generateUrl('smart_unity_utilisateur_homepage'));

    }
    
}
