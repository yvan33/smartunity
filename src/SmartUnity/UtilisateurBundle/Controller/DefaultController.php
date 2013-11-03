<?php

namespace SmartUnity\UtilisateurBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {

        $user = $this->container->get('security.context')->getToken()->getUser();
       
        $form_pref=$this->createForm('smartunity_user_preference', $user);
//        $form_infos=$this->createForm('smartunity_user_informations', $user);
        $em = $this->getDoctrine()->getEntityManager();
/*        $repo = $em->getRepository('SmartUnityAppBundle:question');
        $query=$repo->CreateQueryBuilder('q')
            ->join('q.reponsevalidee', 'r', 'WITH' ,'r->getMembre()->getId(); = :membre' )
            ->addSelect('r')
            ->setParameter('membre', $user->getId());
            // ->getQuery();
        $queries=$query->getQuery();
        $a=$queries->getResult();
        $res=count($a->getRemuneration());*/

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
            $useer = $this->container->get('security.context')->getToken()->getUser();
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
    public function calculParam($user){


    }
    
}
