<?php

namespace SmartUnity\QuestionReponseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Model\UserInterface;

class AjaxController extends Controller {

    public function getQuestionsAction($type, $page, $nbParPage) {

        //Récupération des repositories pour les réponses (meilleure réponse) et questions
        $questionRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:question');

        $reponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:reponse');

        //Appel au repository

        if ($type == 'onFire') {
            $listeQuestion = $questionRepository->getQuestionsOnFire($nbParPage, $page);
            $nbQuestions = $questionRepository->getNombreQuestionsOnFire();
        } else if ($type == 'last') {
            $listeQuestion = $questionRepository->getLastQuestions($nbParPage, $page);
            $nbQuestions = $questionRepository->getNombreLastQuestions();
        } else if ($type == 'reponses') {
            $listeQuestion = $questionRepository->getValidatedQuestions($nbParPage, $page);
            $nbQuestions = $questionRepository->getNombreValidatedQuestions();
        } else {
            throw new \Exception('Error: Wrong parameter for "type" on AjaxController:getQuestions');
        }

        if (ceil($nbQuestions / $nbParPage) == 0) {
            $nbPages = 1;
        } else {
            $nbPages = ceil($nbQuestions / $nbParPage);
        }


        //Initalisation du tableau de retour
        $returnArray = array();
        array_push($returnArray, array(//Première ligne du tableau contient des infos sur la requête
            'type' => $type,
            'nbParPage' => $nbParPage,
            'page' => $page,
            'nbQuestions' => $nbQuestions,
            'nbPages' => $nbPages,
            'slug' => '_infos'
        ));

        if ($listeQuestion[0] != null) {
            foreach ($listeQuestion as $Question) { //On parcourt toutes les questions, on les liste dans le tableau de sortie
                $descriptionBestReponse = null;
                $auteurBestreponse = null;
                $dateBestReponse = null;
                $is_certif_question = false;
                $is_validated_question = false;
                $remunerationQuestion=$Question->getRemuneration() + $Question->getSupplementRemuneration();

                if ($Question->getIsValidatedQuestion()) {
                    foreach ($Question->getReponses() as $reponse) {
                        if ($reponse->getDateCertification() instanceof \DateTime) {
                            $auteurBestreponse = $reponse->getMembre()->getUsername();
                            $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i');
                            $is_certif_question = true;
                            $descriptionBestReponse = $reponse->getDescription();
                            break;
                        } else if ($reponse->getDateValidation() instanceof \DateTime) {
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
                } else {

                    $bestReponse = $reponseRepository->getBestReponse($Question->getId());

                    if ($bestReponse !== false) {

                        foreach ($Question->getReponses() as $reponse) {
                            if ($reponse->getId() == $bestReponse['repId']) {
                                $descriptionBestReponse = $reponse->getDescription();
                                $auteurBestreponse = $reponse->getMembre()->getUsername();
                                $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i');
                                break;
                            }
                        }
                    }
                }
                array_push($returnArray, array(
                    'id' => $Question->getId(),
                    'sujet' => $Question->getSujet(),
                    'description' => $Question->getDescription(),
                    'date' => $Question->getDate()->format('d-m-Y à H:i'),
                    'membre_username' => $Question->getMembre()->getUsername(),
                    'remuneration' => $remunerationQuestion,
                    'nb_reponses' => $Question->getReponses()->count(),
                    'best_reponse' => $descriptionBestReponse,
                    'auteur_best_reponse' => $auteurBestreponse,
                    'is_validated_question' => $is_validated_question,
                    'is_certif_question' => $is_certif_question,
                    'date_best_reponse' => $dateBestReponse,
                    'slug' => $Question->getSlug(),
                    'count_soutien' => $Question->getSoutienMembres()->count(),
                    'soutenue' => $Question->getSoutienMembres()->contains($this->getUser())
                ));
            }
        }

        //SORTIE: JSON du tableau de sortie
        return new Response(json_encode($returnArray));
    }

    public function getReponsesAction($slug, $tri, $page, $nbParPage) {

        $reponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:reponse');

        $questionRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:question');

        $commentaireReponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:commentaireReponse');
        
        $avatarRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:avatar');

        //Récupération de l'id de la question à partir du Slug
        //Utilisatioin des meta-fonctions du repository
        $Question = $questionRepository->findOneBySlug($slug);

        if ($Question == null) {
            throw new NotFoundHttpException("Cette question n'a pas encore été posée!");
        } else {
            $QuestionId = $Question->getId();
        }

        //Récupération de la liste des réponses
        $listeReponse = $reponseRepository->getReponsesWithVotes($QuestionId, $page, $nbParPage, $tri);
        $nbReponses = $reponseRepository->getNbReponses($QuestionId);

        if (ceil($nbReponses / $nbParPage) == 0) {
            $nbPages = 1;
        } else {
            $nbPages = ceil($nbReponses / $nbParPage);
        }

        $returnArray = array();
        array_push($returnArray, array(//Première ligne du tableau contient des infos sur la requête
            'nbParPage' => $nbParPage,
            'page' => (int) $page,
            'nbReponses' => (int) $nbReponses,
            'nbPages' => $nbPages,
            'slug' => $slug,
            'tri' => $tri
        ));


        $isup = 0;
        $isdown = 0;
        $upvote_global = 0;
        $downvote_global = 0;

        //On parcourt les réponses
        if ($listeReponse[0] != null) {

            foreach ($listeReponse as $reponse) {

                $commentairesReturn = array();

                //Récupération des commentaires
                $commentaires = $commentaireReponseRepository->findBy(array('reponse' => $reponse,
                    'signaler' => 0), array('date' => 'asc'));

                //Remplissage du tableau de sortie commentaires
                foreach ($commentaires as $commentaire) {
                    array_push($commentairesReturn, array(
                        'id' => $commentaire->getId(),
                        'description' => $commentaire->getDescription(),
                        'date' => $commentaire->getDate()->format('d-m-Y à H:i'),
                        'membre_username' => $commentaire->getMembre()->getUsername(),
                        'membre_id' => $commentaire->getMembre()->getId()
                    ));
                }
                
                $isCertif = false;
                $dateCertification = false;
                if ($reponse[0]->getDateCertification() != null) {
                    $isCertif = true;
                    $dateCertification = $reponse[0]->getDateCertification()->format('d-m-Y à H:i');
                }

                $isValid = false;
                $dateValidation = false;
                if ($reponse[0]->getDateValidation() != null) {
                    $isValid = true;
                    $dateValidation = $reponse[0]->getDateValidation()->format('d-m-Y à H:i');
                }

                $getNoteReponses = $reponse[0]->getNoteReponses();
                $isVoted = false;

                foreach ($getNoteReponses as $noteReponse) {
                    if ($noteReponse->getMembre() == $this->getUser()) {
                        $isVoted = true;
                        break;
                    }
                }

                $upvote = (int) $reponse['upVote'];
                $downvote = (int) $reponse['downVote'];

                if ($upvote > ($upvote_global_ref = &$upvote_global)) {
                    $isup = $reponse[0]->getId();
                    $upvote_global_ref = $upvote;
                } elseif ($upvote == ($upvote_global_ref = &$upvote_global)) {
                    $isup = '';
                }
                if ($downvote < ($downvote_global_ref = &$downvote_global)) {
                    $isdown = $reponse[0]->getId();
                    $downvote_global_ref = $downvote;
                } elseif ($downvote == ($downvote_global_ref = &$downvote_global)) {
                    $isdown = '';
                }

                $membre = $reponse[0]->getMembre();

                $smartReponses = $reponseRepository->getNbCertifForUser($membre->getId());
                $nb_questions_membre = $questionRepository->getNbQuestionsForUser($membre->getId());
                $nb_reponses_membre = $reponseRepository->getNbReponsesForUser($membre->getId());
                $avatar = $avatarRepository->find($membre->getId());

                if (isset($avatar)) {
                    $avatar = $avatar->getWebPath();
                }
                //Ajour d'une réponse dans le tableau de sortie
                array_push($returnArray, array(
                    'id' => $reponse[0]->getId(),
                    'description' => $reponse[0]->getDescription(),
                    'date' => $reponse[0]->getDate()->format('d-m-Y à H:i'),
                    'up_vote' => $upvote,
                    'down_vote' => $downvote,
                    'membre_username' => $membre->getUsername(),
                    'membre_id' => $membre->getId(),
                    'membre_reputation' => $membre->getReputation(),
                    'commentaires' => $commentairesReturn,
                    'is_certif' => $isCertif,
                    'is_validated' => $isValid,
                    'is_voted' => $isVoted,
                    'smart_reponses' => (int) $smartReponses,
                    'nb_questions_membre' => (int) $nb_questions_membre,
                    'nb_reponses_membre' => (int) $nb_reponses_membre,
                    'points_membre' => (int) $membre->getCagnotte(),
                    'avatar' => $avatar,
                    'date_validation' => $dateValidation,
                    'date_certification' => $dateCertification
                ));
            }
        }
        $array2 = array(
            'isdown' => $isdown,
            'isup' => $isup
        );

        array_push($returnArray, $array2);

        return new Response(json_encode($returnArray));
    }

    public function getSearchAction() {

        $question = $this->getRequest()->query->get('q');


        $finder = $this->container->get('fos_elastica.finder.smartunity.question');
        $resultSet = $finder->findHybrid(urldecode($question));

        $html = '';
        $html .= '<html lang="en"><head></head><body>';
        $html.= 'score     ------    sujet<br/><br/>';

        foreach ($resultSet as $result) {
            $html.= $result->getResult()->getScore() . ' ------ ';
            $html.= $result->getTransformed()->getSujet() . ' ------ ';
            $html.= $result->getTransformed()->getMembre()->getNom();
            $html.= '<br/>';
        }

        $html.= '</body></html>';

        return new Response($html);
    }

    public function setUpVoteAction($reponseId) {

        //Check si le user est loggé ou pas
        $user = $this->container->get('security.context')->getToken()->getUser();
        $reponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:reponse');
        $reponse = $reponseRepository->findById($reponseId);
        
        if (!is_object($user) || !$user instanceof UserInterface) {

            return new Response(json_encode(array(
                        'status' => 'error',
                        'error' => 'NOT_LOGGED',
                        'error_msg' => 'Vous devez être connecté pour pouvoir voter.'
            )));
        } elseif ($reponse[0]->getMembre()->getId() == $user->getId()) {//User loggé mais meme utilisateur = répondant
            return new Response(json_encode(array(
                        'status' => 'error',
                        'error' => 'LOGGED_SAME_USER',
                        'error_msg' => 'Vous ne pouvez pas voter pour votre réponse.'
            )));
        } else {
            $noteReponseRepository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('SmartUnityAppBundle:noteReponse');

            $notes = $noteReponseRepository->findBy(array(
                'membre' => $user,
                'reponse' => $reponseId
            ));

            if (count($notes) > 0) {
                return new Response(json_encode(array(
                            'status' => 'error',
                            'error' => 'ALREADY_VOTED',
                            'error_msg' => 'Vous avez déjà voté!'
                )));
            } else {

                if (count($reponse) < 1) {
                    return new Response(json_encode(array(
                                'status' => 'error',
                                'error' => 'REPONSE_NOT_EXISTS',
                                'error_msg' => 'Cette réponse n\'éxiste pas!'
                    )));
                }

                $newNoteReponse = new \SmartUnity\AppBundle\Entity\noteReponse();
                $newNoteReponse->setNote(1);
                $newNoteReponse->setMembre($user);
                $newNoteReponse->setReponse($reponse[0]);

                $repondant = $reponse[0]->getMembre();
                $reputation = $repondant->getReputation();
                $repondant->setReputation($reputation + 1);

                $em = $this->getDoctrine()->getManager();
                $em->persist($newNoteReponse);
                $em->persist($repondant);
                $em->flush();

                return new Response(json_encode(array(
                            'status' => 'ok',
                            'msg' => 'Votre vote à été pris en compte!'
                )));
            }
        }
    }

    public function setDownVoteAction($reponseId) {
        //Check si le user est loggé ou pas
        $user = $this->container->get('security.context')->getToken()->getUser();
        $reponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:reponse');
        $reponse = $reponseRepository->findById($reponseId);

        if (!is_object($user) || !$user instanceof UserInterface) { //User pas loggé
            return new Response(json_encode(array(
                        'status' => 'error',
                        'error' => 'NOT_LOGGED',
                        'error_msg' => 'Vous devez être connécté pour pouvoir voter.'
            )));
        } elseif ($reponse[0]->getMembre()->getId() == $user->getId()) {//User loggé mais meme utilisateur = répondant
            return new Response(json_encode(array(
                        'status' => 'error',
                        'error' => 'LOGGED_SAME_USER',
                        'error_msg' => 'Vous ne pouvez pas voter pour votre réponse.'
            )));
        } else { //User loggé et bon
            $noteReponseRepository = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('SmartUnityAppBundle:noteReponse');


            $notes = $noteReponseRepository->findBy(array(
                'membre' => $user,
                'reponse' => $reponseId
            ));

            if (count($notes) > 0) {
                return new Response(json_encode(array(
                            'status' => 'error',
                            'error' => 'ALREADY_VOTED',
                            'error_msg' => 'Vous avez déjà voté!'
                )));
            } else {



                if (count($reponse) < 1) {
                    return new Response(json_encode(array(
                                'status' => 'error',
                                'error' => 'REPONSE_NOT_EXISTS',
                                'error_msg' => 'Cette réponse n\'éxiste pas!'
                    )));
                }

                $newNoteReponse = new \SmartUnity\AppBundle\Entity\noteReponse();
                $newNoteReponse->setNote(-1);
                $newNoteReponse->setMembre($user);
                $newNoteReponse->setReponse($reponse[0]);
                $repondant = $reponse[0]->getMembre();
                $reputation = $repondant->getReputation();
                $repondant->setReputation($reputation - 1);

                $em = $this->getDoctrine()->getManager();
                $em->persist($newNoteReponse);
                $em->persist($repondant);
                $em->flush();

                return new Response(json_encode(array(
                            'status' => 'ok',
                            'msg' => 'Votre vote à été pris en compte!'
                )));
            }
        }
    }

}
