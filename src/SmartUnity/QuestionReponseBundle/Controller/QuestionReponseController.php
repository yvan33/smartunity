<?php

namespace SmartUnity\QuestionReponseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class QuestionReponseController extends Controller
{
    public function indexAction()
    {
        // afficher 3 onglets, avec quelques questions... de la page accueil

        //Redirection vers la liste des questions (pour l'instant)
        return $this->redirect( $this->generateUrl('smart_unity_question_reponse_list_of_question') );
    }

    public function displayListOfQuestionAction($type, $page)
    {
        // Capable d'afficher les 3 listes paginées dans un twig dédié à l'affichage d'une liste de questions...
    	
        //Afficher le TWIG liste Questions

        //Les données seront récupérees depuis la BDD via l'Ajax controller, appelé dans le Twig par jQuery.
        //L'AjaxController contiendra toutes les commandes nécéssaires pour récupérer les données et les renvoyer
        //en JSON. Il exploites les Repositery.

        //Pour les user sans javascript, il faut prévoir du contenu des l'ouverture de la page
        //Donc on le charge...
        //Si javascript il y a, on passera pas l'AjaxController pour
        //charger le reste

        $repository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:question');

        $listeQuestion = $repository->findBy(array(), 
                                        array('date'=>'desc'),
                                        5,
                                        0);

        $test='blabla';

        foreach($listeQuestion as $Question){
            $test.=$Question->getSlug();
        }


        $template = sprintf('SmartUnityQuestionReponseBundle:Display:ListeQuestion.html.twig');

        return $this->render($template, array(
                'contentTest'=>$test
            ));
    }
    
    public function displayQuestionAction()
    {   
        //Affichage de LA question avec liste réponses
    	
        //return $this->render('SmartUnityQuestionReponseBundle:Display:question.html.twig');
        return new Response('display question QuestionReponses');
    }

    public function addQuestionAction()
    {
    	
        //return $this->render('SmartUnityQuestionReponseBundle:Question:Add.html.twig');
        return new Response('add question QuestionReponses');
    }

    public function addReponseAction()
    {
        //lancé depuis une iFrame fancybox
        // --> créer un dossier iFrame dans les vues du QuestionReponseBundle
    	
        //return $this->render('SmartUnityQuestionReponseBundle:Question:Add.html.twig');
        return new Response('add reponse QuestionReponses');
    }

    public function validationReponseAction()
    {
    	// return $this->;//pointer vers l'affichage de la question
        return new Response('validation QuestionReponses');
    }

    public function certificationReponseAction()
    {
		// return $this->;//pointer vers l'affichage de la question
        return new Response('certification QuestionReponses');
    }

    public function addCommentaireQuestionAction()
    {
        //IDEM add réponse
        return new Response('add com question QuestionReponses');
    }

    public function addCommentaireReponseAction()
    {
        //IDEM add réponse
        return new Response('add com reponse QuestionReponses');
    }
}