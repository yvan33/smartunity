<?php

namespace SmartUnity\QuestionReponseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{

	public function getQuestionsAction($type, $page, $nbParPage){
		
        //Récupération des repositories pour les réponses (meilleure réponse) et questions
		$questionRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:question');

        $reponseRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:reponse');


        //Appel au repository


        echo $questionRepository->getValidatedQuestions($nbParPage, $page)[0]->getSlug();
        echo $questionRepository->getValidatedQuestions($nbParPage, $page)[0]->getMembre()->getNom();
        exit();

        $listeQuestion = $questionRepository->findBy(array(), 
                                        array('date'=>'desc'),
                                        $nbParPage,
                                        ($page - 1) * $nbParPage);

        $nbQuestions = $questionRepository->getNombreQuestions(); //nombre de questions (pagination)


        //Initalisation du tableau de retour
        $returnArray=array();
        array_push($returnArray, array( //Première ligne du tableau contient des infos sur la requête
            'type' => $type,
            'nbParPage'=>$nbParPage,
            'page'=>$page,
            'nbQuestions'=>$nbQuestions,
            'slug'=>'_infos'
        ));

        foreach($listeQuestion as $Question){ //On parcourt toutes les questions, on les liste dans le tableau de sortie

            $reponse = '';
            $bestReponse = '';
            $idBestReponse = '';
            $auteurBestreponse= '';
            $dateBestReponse = '';

            $idBestReponse = $reponseRepository->getBestReponse($Question->getId());
            if ($idBestReponse['repId'] !== false){

                foreach($Question->getReponses() as $reponse){
                    if($reponse->getId() == $idBestReponse['repId']){
                        $bestReponse = $reponse->getDescription();
                        $auteurBestreponse = $reponse->getMembre()->getPrenom() . ' ' . $reponse->getMembre()->getNom();
                        $dateBestReponse = $reponse->getDate();
                        break;
                    }
                }

            }


            array_push($returnArray, array(
            	'id'=>$Question->getId(),
            	'sujet'=>$Question->getSujet(),
            	'description'=>$Question->getDescription(),
            	'date'=>$Question->getDate(),
                'membre_nom'=>$Question->getMembre()->getNom(),
                'membre_prenom'=>$Question->getMembre()->getPrenom(),
                'remuneration'=>$Question->getRemuneration(),
                'nb_reponses'=>$Question->getReponses()->count(),
                'best_reponse'=>$bestReponse,
                'auteur_best_reponse'=>$auteurBestreponse,
                'date_best_reponse'=>$dateBestReponse,
                'slug'=>$Question->getSlug()
            ));

        }

        //SORTIE: JSON du tableau de sortie
        return new Response(json_encode($returnArray));
	}



}