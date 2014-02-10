<?php

namespace SmartUnity\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;

class AjaxMembreController extends Controller {

    public function getQuestionsAction($type, $page, $nbParPage, $membreId, $route) {
        //Récupération des repositories pour les réponses (meilleure réponse) et questions
        $questionRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:question');

        $reponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:reponse');

        if ($route == 'smart_unity_membre_questions') {
           
            //Appel au repository
            if ($type == 'onFire') {
                $listeQuestion = $questionRepository->getQuestionsOnFireForUser($nbParPage, $page, $membreId);
                $nbQuestions = $questionRepository->getNombreQuestionsOnFireForUser($membreId);
            } else if ($type == 'last') {
                $listeQuestion = $questionRepository->getLastQuestionsForUser($nbParPage, $page, $membreId);
                $nbQuestions = $questionRepository->getNombreLastQuestionsForUser($membreId);
            } else if ($type == 'reponses') {
                $listeQuestion = $questionRepository->getValidatedQuestionsForUser($nbParPage, $page, $membreId);
                $nbQuestions = $questionRepository->getNombreValidatedQuestionsForUser($membreId);
            } else {
                throw new \Exception('Error: Wrong parameter for "type" on AjaxController:getQuestions');
            }
        } 



        //Initalisation du tableau de retour
        $returnArray = array();
        array_push($returnArray, array(//Première ligne du tableau contient des infos sur la requête
            'type' => $type,
            'nbParPage' => $nbParPage,
            'page' => $page,
            'nbQuestions' => $nbQuestions,
            'nbPages' => ceil($nbQuestions / $nbParPage),
            'slug' => '_infos'
        ));

                if($listeQuestion[0] != null){
            foreach($listeQuestion as $Question){ //On parcourt toutes les questions, on les liste dans le tableau de sortie


                $descriptionBestReponse = null;
                $auteurBestreponse= null;
                $dateBestReponse = null;
                $is_certif_question = false;
                $is_validated_question = false;


if ($questionRepository->isQuestionValid($Question->getId())){    
    foreach($Question->getReponses() as $reponse){
        if ($reponse->getDateCertification() instanceof \DateTime){
            $auteurBestreponse = $reponse->getMembre()->getUsername();
            $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i');
            $is_certif_question = true;
            $descriptionBestReponse = $reponse->getDescription();
            break;
        }

        else if($reponse->getDateValidation() instanceof \DateTime ){
            $auteurBestreponse = $reponse->getMembre()->getUsername();
            $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i');
            $is_validated_question = true;
            $descriptionBestReponse = $reponse->getDescription();
            break;
        }
        // else {
            // throw new \Exception("Erreur sur une question");
            // }
    }
}

else{

                $bestReponse = $reponseRepository->getBestReponse($Question->getId());
                
                if ($bestReponse !== false){

                    foreach($Question->getReponses() as $reponse){
                        if($reponse->getId() == $bestReponse['repId']){
                            $descriptionBestReponse = $reponse->getDescription();
                            $auteurBestreponse = $reponse->getMembre()->getUsername();
                            $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i'); 
                            break;
                        }
                    }
                }
            }    
                array_push($returnArray, array(
                    'id'=>$Question->getId(),
                    'sujet'=>$Question->getSujet(),
                    'description'=>$Question->getDescription(),
                    'date'=>$Question->getDate()->format('d-m-Y à H:i'),
                    'membre_username'=>$Question->getMembre()->getUsername(),
                    'remuneration'=>$Question->getRemuneration(),
                    'nb_reponses'=>$Question->getReponses()->count(),
                    'best_reponse'=>$descriptionBestReponse,
                    'auteur_best_reponse'=>$auteurBestreponse,
                    'is_validated_question'=>$is_validated_question,
                    'is_certif_question'=>$is_certif_question,
                    'date_best_reponse'=>$dateBestReponse,
                    'slug'=>$Question->getSlug(),
                    'count_soutien'=>$Question->getSoutienMembres()->count(),
                    'soutenue'=>$Question->getSoutienMembres()->contains($this->getUser())
                ));

            }
        }


        //SORTIE: JSON du tableau de sortie
        return new Response(json_encode($returnArray));
    }

    public function getQuestionsAnsweredAction($type, $page, $nbParPage, $membreId, $route) {
        //Récupération des repositories pour les réponses (meilleure réponse) et questions
        $questionRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:question');

        $reponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:reponse');

        if ($route == 'smart_unity_membre_reponses') {
           
            //Appel au repository
            if ($type == 'reponses') {
                $listeQuestion = $questionRepository->getQuestionsAnsweredByUser($nbParPage, $page, $membreId);
                $nbQuestions = $questionRepository->getNombreQuestionsAnsweredByUser($membreId);
            } else if ($type == 'certified') {  
                $listeQuestion = $questionRepository->getQuestionsWithCertifiedAnswersForUser($nbParPage, $page, $membreId);
                $nbQuestions = $questionRepository->getNombreQuestionsWithCertifiedAnswersForUser($membreId);
            } else if ($type == 'validated') {
                $listeQuestion = $questionRepository->getQuestionsWithValidatedAnswersForUser($nbParPage, $page, $membreId);
                $nbQuestions = $questionRepository->getNombreQuestionsWithValidatedAnswersForUser($membreId);
            } else {
                throw new \Exception('Error: Wrong parameter for "type" on AjaxController:getQuestions');
            }
        }

        //Initalisation du tableau de retour
        $returnArray = array();
        array_push($returnArray, array(//Première ligne du tableau contient des infos sur la requête
            'type' => $type,
            'nbParPage' => $nbParPage,
            'page' => $page,
            'nbQuestions' => $nbQuestions,
            'nbPages' => ceil($nbQuestions / $nbParPage),
            'slug' => '_infos'
        ));

if($listeQuestion[0] != null){
            foreach($listeQuestion as $Question){ //On parcourt toutes les questions, on les liste dans le tableau de sortie


                $descriptionBestReponse = null;
                $auteurBestreponse= null;
                $dateBestReponse = null;
                $is_certif_question = false;
                $is_validated_question = false;


if ($questionRepository->isQuestionValid($Question->getId())){    
    foreach($Question->getReponses() as $reponse){
        if ($reponse->getDateCertification() instanceof \DateTime){
            $auteurBestreponse = $reponse->getMembre()->getUsername();
            $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i');
            $is_certif_question = true;
            $descriptionBestReponse = $reponse->getDescription();
            break;
        }

        else if($reponse->getDateValidation() instanceof \DateTime ){
            $auteurBestreponse = $reponse->getMembre()->getUsername();
            $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i');
            $is_validated_question = true;
            $descriptionBestReponse = $reponse->getDescription();
            break;
        }
        // else {
            // throw new \Exception("Erreur sur une question");
            // }
    }
}

else{

                $bestReponse = $reponseRepository->getBestReponse($Question->getId());
                
                if ($bestReponse !== false){

                    foreach($Question->getReponses() as $reponse){
                        if($reponse->getId() == $bestReponse['repId']){
                            $descriptionBestReponse = $reponse->getDescription();
                            $auteurBestreponse = $reponse->getMembre()->getUsername();
                            $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i'); 
                            break;
                        }
                    }
                }
            }    
                array_push($returnArray, array(
                    'id'=>$Question->getId(),
                    'sujet'=>$Question->getSujet(),
                    'description'=>$Question->getDescription(),
                    'date'=>$Question->getDate()->format('d-m-Y à H:i'),
                    'membre_username'=>$Question->getMembre()->getUsername(),
                    'remuneration'=>$Question->getRemuneration(),
                    'nb_reponses'=>$Question->getReponses()->count(),
                    'best_reponse'=>$descriptionBestReponse,
                    'auteur_best_reponse'=>$auteurBestreponse,
                    'is_validated_question'=>$is_validated_question,
                    'is_certif_question'=>$is_certif_question,
                    'date_best_reponse'=>$dateBestReponse,
                    'slug'=>$Question->getSlug(),
                    'count_soutien'=>$Question->getSoutienMembres()->count(),
                    'soutenue'=>$Question->getSoutienMembres()->contains($this->getUser())
                ));

            }
        }


        //SORTIE: JSON du tableau de sortie
        return new Response(json_encode($returnArray));
    }
}
