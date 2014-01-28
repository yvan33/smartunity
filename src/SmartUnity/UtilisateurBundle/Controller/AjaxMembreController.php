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
                p('onFire');
                $listeQuestion = $questionRepository->getQuestionsOnFireForUser($nbParPage, $page, $membreId);
                $nbQuestions = $questionRepository->getNombreQuestionsOnFireForUser($membreId);
            } else if ($type == 'last') {
                p('last');
                $listeQuestion = $questionRepository->getLastQuestionsForUser($nbParPage, $page, $membreId);
                $nbQuestions = $questionRepository->getNombreLastQuestionsForUser($membreId);
            } else if ($type == 'reponses') {
                p('reponses');
                $listeQuestion = $questionRepository->getValidatedQuestionsForUser($nbParPage, $page, $membreId);
                $nbQuestions = $questionRepository->getNombreValidatedQuestionsForUser($membreId);
            } else {
                throw new \Exception('Error: Wrong parameter for "type" on AjaxController:getQuestions');
            }
        } elseif ($route == 'smart_unity_membre_reponses') {
           
            $listeQuestion = $questionRepository->getQuestionsAnsweredByUser($nbParPage, $page, $membreId);
            $nbQuestions = $questionRepository->getNombreQuestionsAnsweredByUser($membreId);
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

        if ($listeQuestion[0] != null) {
            foreach ($listeQuestion as $Question) { //On parcourt toutes les questions, on les liste dans le tableau de sortie
                $bestReponse = '';
                $idBestReponse = '';
                $auteurBestreponse = '';
                $dateBestReponse = '';

                $idBestReponse = $reponseRepository->getBestReponse($Question->getId());
                if ($idBestReponse['repId'] !== false) {

                    foreach ($Question->getReponses() as $reponse) {
                        if ($reponse->getId() == $idBestReponse['repId']) {
                            $bestReponse = $reponse->getDescription();
                            $auteurBestreponse = $reponse->getMembre()->getUsername();
                            $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i');
                            break;
                        }
                    }
                }


                array_push($returnArray, array(
                    'id' => $Question->getId(),
                    'sujet' => $Question->getSujet(),
                    'description' => $Question->getDescription(),
                    'date' => $Question->getDate()->format('d-m-Y à H:i'),
                    'membre_username' => $Question->getMembre()->getUsername(),
                    'remuneration' => $Question->getRemuneration(),
                    'nb_reponses' => $Question->getReponses()->count(),
                    'best_reponse' => $bestReponse,
                    'auteur_best_reponse' => $auteurBestreponse,
                    'date_best_reponse' => $dateBestReponse,
                    'slug' => $Question->getSlug(),
                    'count_soutien' => $Question->getSoutienMembres()->count(),
                    'soutenue' => $Question->getSoutienMembres()->contains($this->getUser()),
                ));
            }
        }
        p($returnArray);

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

        //Appel au repository
        if ($type == 'certified') {
            p('1');

            // $listeQuestion = $questionRepository->getQuestionsAnsweredByUser($nbParPage, $page, $membreId);
            // $nbQuestions = $questionRepository->getNombreQuestionsAnsweredByUser($membreId);  
            $listeQuestion = $questionRepository->getQuestionswithAnwersCertifiedForUser($nbParPage, $page, $membreId);
            p('2');

            $nbQuestions = $questionRepository->getNombreQuestionswithAnwersCertifiedForUser($membreId);
        } else if ($type == 'validated') {
            p('11');

            // $listeQuestion = $questionRepository->getQuestionsAnsweredByUser($nbParPage, $page, $membreId);
            // $nbQuestions = $questionRepository->getNombreQuestionsAnsweredByUser($membreId);  
            $listeQuestion = $questionRepository->getQuestionswithAnwersValidatedForUser($nbParPage, $page, $membreId);
            p('22');

            $nbQuestions = $questionRepository->getNombreQuestionswithAnwersValidatedForUser($membreId);
        } else if ($type == 'reponses') {
            $listeQuestion = $questionRepository->getQuestionsAnsweredByUser($nbParPage, $page, $membreId);
            $nbQuestions = $questionRepository->getNombreQuestionsAnsweredByUser($membreId);  
        } else {
            throw new \Exception('Error: Wrong parameter for "type" on AjaxController:getQuestions');
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

        if ($listeQuestion[0] != null) {
            foreach ($listeQuestion as $Question) { //On parcourt toutes les questions, on les liste dans le tableau de sortie
                $bestReponse = '';
                $idBestReponse = '';
                $auteurBestreponse = '';
                $dateBestReponse = '';

                $idBestReponse = $reponseRepository->getBestReponse($Question->getId());
                if ($idBestReponse['repId'] !== false) {

                    foreach ($Question->getReponses() as $reponse) {
                        if ($reponse->getId() == $idBestReponse['repId']) {
                            $bestReponse = $reponse->getDescription();
                            $auteurBestreponse = $reponse->getMembre()->getUsername();
                            $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i');
                            break;
                        }
                    }
                }
                array_push($returnArray, array(
                    'id' => $Question->getId(),
                    'sujet' => $Question->getSujet(),
                    'description' => $Question->getDescription(),
                    'date' => $Question->getDate()->format('d-m-Y à H:i'),
                    'membre_username' => $Question->getMembre()->getUsername(),
                    'remuneration' => $Question->getRemuneration(),
                    'nb_reponses' => $Question->getReponses()->count(),
                    'best_reponse' => $bestReponse,
                    'auteur_best_reponse' => $auteurBestreponse,
                    'date_best_reponse' => $dateBestReponse,
                    'slug' => $Question->getSlug(),
                    'count_soutien' => $Question->getSoutienMembres()->count(),
                    'soutenue' => $Question->getSoutienMembres()->contains($this->getUser()),
                ));
            }
        }
        //SORTIE: JSON du tableau de sortie
        return new Response(json_encode($returnArray));
    }
}

//     public function getReponsesAction($slug, $tri, $page, $nbParPage) {

//         $reponseRepository = $this->getDoctrine()
//                 ->getManager()
//                 ->getRepository('SmartUnityAppBundle:reponse');

//         $questionRepository = $this->getDoctrine()
//                 ->getManager()
//                 ->getRepository('SmartUnityAppBundle:question');

//         $commentaireReponseRepository = $this->getDoctrine()
//                 ->getManager()
//                 ->getRepository('SmartUnityAppBundle:commentaireReponse');


//         //Récupération de l'id de la question à partir du Slug
//         //Utilisatioin des meta-fonctions du repository
//         $Question = $questionRepository->findOneBySlug($slug);

//         if ($Question == null) {
//             throw new NotFoundHttpException("Cette question n'a pas encore été posée!");
//             exit();
//         } else {
//             $QuestionId = $Question->getId();
//         }

//         //Récupération de la liste des réponses
//         $listeReponse = $reponseRepository->getReponsesWithVotes($QuestionId, $page, $nbParPage, $tri);

//         $nbReponses = $reponseRepository->getNbReponses($QuestionId);

//         $returnArray = array();
//         array_push($returnArray, array(//Première ligne du tableau contient des infos sur la requête
//             'nbParPage' => $nbParPage,
//             'page' => (int) $page,
//             'nbReponses' => (int) $nbReponses,
//             'nbPages' => ceil($nbReponses / $nbParPage),
//             'slug' => $slug,
//             'tri' => $tri
//         ));


//         //On parcourt les réponses
//         if ($listeReponse[0] != null) {
//             foreach ($listeReponse as $reponse) {


//                 $commentairesReturn = array();

//                 //Récupération des commentaires
//                 $commentaires = $commentaireReponseRepository->findBy(array('reponse' => $reponse), array('date' => 'asc'));

//                 //Remplissage du tableau de sortie commentaires
//                 foreach ($commentaires as $commentaire) {
//                     array_push($commentairesReturn, array(
//                         'description' => $commentaire->getDescription(),
//                         'date' => $commentaire->getDate()->format('d-m-Y à H:i'),
//                         'membre_username' => $commentaire->getMembre()->getUsername()
//                     ));
//                 }

//                 $isCertif = false;
//                 if ($reponse[0]->getDateCertification() != null)
//                     $isCertif = true;

//                 $isValid = false;
//                 if ($reponse[0]->getDateValidation() != null)
//                     $isValid = true;

//                 $getNoteReponses = $reponse[0]->getNoteReponses();
//                 $isVoted = false;

//                 foreach ($getNoteReponses as $noteReponse) {
//                     if ($noteReponse->getMembre() == $this->getUser()) {
//                         $isVoted = true;
//                         break;
//                     }
//                 }


//                 $membre = $reponse[0]->getMembre();

//                 $smartReponses = $reponseRepository->getNbCertifForUser($membre->getId());
//                 $nb_questions_membre = $questionRepository->getNbQuestionsForUser($membre->getId());

//                 //Ajour d'une réponse dans le tableau de sortie
//                 array_push($returnArray, array(
//                     'id' => $reponse[0]->getId(),
//                     'description' => $reponse[0]->getDescription(),
//                     'date' => $reponse[0]->getDate()->format('d-m-Y à H:i'),
//                     'up_vote' => (int) $reponse['upVote'],
//                     'down_vote' => (int) $reponse['downVote'],
//                     'membre_username' => $membre->getUsername(),
//                     'membre_reputation' => $membre->getReputation(),
//                     'commentaires' => $commentairesReturn,
//                     'is_certif' => $isCertif,
//                     'is_validated' => $isValid,
//                     'is_voted' => $isVoted,
//                     'smart_reponses' => (int) $smartReponses,
//                     'nb_questions_membre' => (int) $nb_questions_membre,
//                     'points_membre' => (int) $membre->getCagnotte()
//                 ));
//             }
//         }

//         return new Response(json_encode($returnArray));
//     }


