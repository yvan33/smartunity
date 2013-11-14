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
        //Pour les user sans javascript, il faut prévoir du contenu dès l'ouverture de la page
        //donc on le charge... via l'Ajax Controller! 
        //Si javascript il y a, on passera pas l'AjaxController pour charger le reste.

        //DONC :

        //On récupère la réponse du controleur Ajax (pour avaoir une réponse au cas ou)


        $nbParPage = 5;

        $response = $this->forward('SmartUnityQuestionReponseBundle:Ajax:getQuestions', array(
            'type'  => $type,
            'page' => $page,
            'nbParPage'=>$nbParPage
        ));

        //Suppression de l'en tête HTTP et décodage du JSON
        $cleanJSON = explode('[', $response, 2);
        $listeQuestions = json_decode('[' . $cleanJSON[1]);


        //Le tableau JSON contient une ligne d'entête qui contient les infos à propos de
        //la requête pour vérifier son authenticité... 

        //On récupère des infos utiles pour la pagination..
        $nbPages = $listeQuestions[0]->nbPages;

        
        //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
        if($listeQuestions[0]->type==$type && $listeQuestions[0]->nbParPage==$nbParPage && $listeQuestions[0]->page==$page)
            unset($listeQuestions[0]);
        else
            throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityQuestionReponseBundle:AjaxController');


        //Génération de la pagination en statique (si pas de JS)
        $pagination = array();
        if($page!=1){
            array_push($pagination, array('<<', '1', '-4'));
            array_push($pagination, array('<', $page - 1, '-4'));
        }
        for ($i=-2; $i<3; $i++){
            if( ($page + $i) >= 1  &&  ($page + $i) <= $nbPages )
                array_push($pagination, array($page + $i, $page + $i, $i));
        }
        if($page < $nbPages){
            array_push($pagination, array('>', $page + 1, '3'));
            array_push($pagination, array('>>', $nbPages, '4'));
        }

        

        $template = sprintf('SmartUnityQuestionReponseBundle:Display:ListeQuestion.html.twig');
        return $this->render($template, array(
            'page'=>$page,
            'type'=>$type,
            'nbPages'=>$nbPages,
            'listeQuestions'=>$listeQuestions, 
            'nbParPage'=>$nbParPage,
            'pagination'=>$pagination
        ));
        

    }



    public function searchQuestionAction()
    {

        $question =  $this->getRequest()->query->get('q');


        $finder = $this->container->get('fos_elastica.finder.smartunity.question');
        $resultSet = $finder->findHybrid(urldecode($question));

        $html = '';
        $html .= '<html lang="en"><head></head><body>';
        $html.= 'score     ------    sujet<br/><br/>';

        foreach($resultSet as $result){
            $html.= $result->getResult()->getScore() . ' ------ ';
            $html.= $result->getTransformed()->getSujet() . ' ------ ';
            $html.= $result->getTransformed()->getMembre()->getNom();
            $html.=  '<br/>';
        }

        $html.=  '</body></html>';
        return new Response($html);
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