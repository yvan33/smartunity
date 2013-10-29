<?php

namespace SmartUnity\QuestionReponseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class QuestionReponseController extends Controller
{
    public function indexAction()
    {
        // afficher 3 onglets, avec quelques questions... de la page accueil

        //return $this->render('SmartUnityQuestionReponseBundle:Default:index.html.twig');
        return new Response('index QuestionReponses');
    }

    public function displayListOfQuestionAction()
    {
        // Capable d'afficher les 3 listes paginées dans un twig dédié à l'affichage d'une liste de questions...
    	
        //return $this->render('SmartUnityQuestionReponseBundle:Display:listOfQuestion.html.twig');
        return new Response('display list of questions QuestionReponses');
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