<?php

namespace SmartUnity\QuestionReponseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        if ($type == 'onFire'){
            $listeQuestion = $questionRepository->getQuestionsOnFire($nbParPage, $page);
            $nbQuestions = $questionRepository->getNombreQuestionsOnFire();
        }else if ($type == 'last'){
            $listeQuestion = $questionRepository->getLastQuestions($nbParPage, $page);
            $nbQuestions = $questionRepository->getNombreLastQuestions();
        }else if ($type == 'reponses'){
            $listeQuestion = $questionRepository->getValidatedQuestions($nbParPage, $page);
            $nbQuestions = $questionRepository->getNombreValidatedQuestions();
        }else{
            throw new \Exception('Error: Wrong parameter for "type" on AjaxController:getQuestions');
        }


        

        //Initalisation du tableau de retour
        $returnArray=array();
        array_push($returnArray, array( //Première ligne du tableau contient des infos sur la requête
            'type' => $type,
            'nbParPage'=>$nbParPage,
            'page'=>$page,
            'nbQuestions'=>$nbQuestions,
            'nbPages'=>ceil($nbQuestions / $nbParPage),
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
                        $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i');
                        break;
                    }
                }

            }


            array_push($returnArray, array(
            	'id'=>$Question->getId(),
            	'sujet'=>$Question->getSujet(),
            	'description'=>$Question->getDescription(),
            	'date'=>$Question->getDate()->format('d-m-Y à H:i'),
                'membre_nom'=>$Question->getMembre()->getNom(),
                'membre_prenom'=>$Question->getMembre()->getPrenom(),
                'remuneration'=>$Question->getRemuneration(),
                'nb_reponses'=>$Question->getReponses()->count(),
                'best_reponse'=>$bestReponse,
                'auteur_best_reponse'=>$auteurBestreponse,
                'date_best_reponse'=>$dateBestReponse,
                'slug'=>$Question->getSlug(),
                'count_soutien'=>$Question->getSoutienMembres()->count(),
                'soutenue'=>$Question->getSoutienMembres()->contains($this->getUser())
            ));

        }

        //SORTIE: JSON du tableau de sortie
        return new Response(json_encode($returnArray));
	}



    public function getReponsesAction($slug, $tri, $page, $nbParPage)
    {

        $reponseRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:reponse');

        $questionRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:question');

        $commentaireReponseRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:commentaireReponse');


        //Récupération de l'id de la question à partir du Slug
        //Utilisatioin des meta-fonctions du repository
        $Question = $questionRepository->findOneBySlug($slug);

        if($Question == null){
            throw new NotFoundHttpException("Cette question n'a pas encore été posée!");
            exit();
        }else{
            $QuestionId = $Question->getId();
        }

        //Récupération de la liste des réponses
        $listeReponse = $reponseRepository->getReponsesWithVotes($QuestionId, $page, $nbParPage, $tri);

        $nbReponses = $reponseRepository->getNbReponses($QuestionId);

        $returnArray=array();
        array_push($returnArray, array( //Première ligne du tableau contient des infos sur la requête
            'nbParPage'=>$nbParPage,
            'page'=> (int) $page,
            'nbReponses'=> (int) $nbReponses,
            'nbPages'=>ceil($nbReponses / $nbParPage),
            'slug'=>$slug,
            'tri'=>$tri
        ));


        //On parcourt les réponses
        if($listeReponse[0] != null){
            foreach($listeReponse as $reponse){


                $commentairesReturn = array();

                //Récupération des commentaires
                $commentaires = $commentaireReponseRepository->findBy(array('reponse' => $reponse),
                                                                        array('date' => 'asc'));
                
               
                //Remplissage du tableau de sortie commentaires
                foreach($commentaires as $commentaire){
                    array_push($commentairesReturn, array(
                        'description'=>$commentaire->getDescription(),
                        'date'=>$commentaire->getDate()->format('d-m-Y à H:i'),
                        'membre_nom'=>$commentaire->getMembre()->getNom()
                    ));
                }

                $isCertif=false;
                if ($reponse[0]->getDateCertification() != null)
                    $isCertif = true;

                $isValid=false;
                if ($reponse[0]->getDateValidation() != null)
                    $isValid = true;

                $getNoteReponses = $reponse[0]->getNoteReponses();
                $isVoted = false;

                foreach($getNoteReponses as $noteReponse){
                    if($noteReponse->getMembre() == $this->getUser()){
                        $isVoted = true;
                        break;
                    }
                }

                //Ajour d'une réponse dans le tableau de sortie
                array_push($returnArray, array(
                    'id'=>$reponse[0]->getId(),
                    'description'=>$reponse[0]->getDescription(),
                    'date'=>$reponse[0]->getDate()->format('d-m-Y à H:i'),
                    'up_vote'=> (int) $reponse['upVote'],
                    'down_vote'=> (int) $reponse['downVote'],
                    'membre_nom'=>$reponse[0]->getMembre()->getNom(),
                    'membre_reputation'=>$reponse[0]->getMembre()->getReputation(),
                    'commentaires'=>$commentairesReturn,
                    'is_certif'=>$isCertif,
                    'is_validated'=>$isValid,
                    'is_voted'=>$isVoted
                ));
            }
        }

        return new Response(json_encode($returnArray));

    }


}