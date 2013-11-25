<?php

namespace SmartUnity\UtilisateurBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;

class UtilisateurController extends Controller
{
    public function indexAction(Request $request)
    {

        $user = $this->container->get('security.context')->getToken()->getUser();
       
        $form_pref=$this->createForm('smartunity_user_preference', $user);
        $em = $this->getDoctrine()->getEntityManager();
        
        $membreRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:membre');

        $userid=$user->getId();
        $smartreponse = $membreRepository->getSmartReponses($userid);  
        $remuneration = 0;
        $remuneration= $membreRepository->getRemuneration($userid);
        //$remuneration = 0;
        return $this->render('SmartUnityUtilisateurBundle:Profile:show.html.twig', array('form_pref'=> $form_pref->createView(),
                                                                                        'smartrep'=> $smartreponse,
                                                                                        'remuneration'=> $remuneration 
                                                                                    ));
    }

    public function indexWithEditInfosAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();

        $form_pref=$this->createForm('smartunity_user_preference', $user);
        $form_infos=$this->createForm('smartunity_user_informations', $user);


        $membreRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:membre');

        $userid=$user->getId();
        $smartreponse = $membreRepository->getSmartReponses($userid); 
        $remuneration = $membreRepository->getRemuneration($userid); 
        return $this->render('SmartUnityUtilisateurBundle:Profile:edit.html.twig', array('form_infos'=> $form_infos->createView(),'form_pref'=> $form_pref->createView(), 'smartrep'=> $smartreponse, 'remuneration'=> $remuneration));
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
