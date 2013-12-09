<?php

namespace SmartUnity\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\ExpressionLanguage\Expression;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;
include_once(__DIR__ . '/../../../../web/phpconsole/install.php');


class AjaxController extends Controller
{

	public function getQuestionsAction($type, $page, $nbParPage, $membreId){
		
        //Récupération des repositories pour les réponses (meilleure réponse) et questions
		$questionRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:question');

        $reponseRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:reponse');

        //Appel au repository

        if ($type == 'onFire'){
            p($membreId);
            $listeQuestion = $questionRepository->getQuestionsOnFireForUser($nbParPage, $page, $membreId);

            $nbQuestions = $questionRepository->getNombreQuestionsOnFire();
        }else if ($type == 'last'){
            
            $listeQuestion = $questionRepository->getLastQuestions($nbParPage, $page);
            
            //p($listeQuestion);
            $nbQuestions = $questionRepository->getNombreLastQuestions();
        }else if ($type == 'reponses'){
            $listeQuestion = $questionRepository->getValidatedQuestions($nbParPage, $page);
           //             p("3");
         
            //p($listeQuestion);
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

        if($listeQuestion[0] != null){
            foreach($listeQuestion as $Question){ //On parcourt toutes les questions, on les liste dans le tableau de sortie

                $bestReponse = '';
                $idBestReponse = '';
                $auteurBestreponse= '';
                $dateBestReponse = '';

                $idBestReponse = $reponseRepository->getBestReponse($Question->getId());
                if ($idBestReponse['repId'] !== false){

                    foreach($Question->getReponses() as $reponse){
                        if($reponse->getId() == $idBestReponse['repId']){
                            $bestReponse = $reponse->getDescription();
                            $auteurBestreponse = $reponse->getMembre()->getUsername();
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
                    'membre_username'=>$Question->getMembre()->getUsername(),
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
                        'membre_username'=>$commentaire->getMembre()->getUsername()
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


                $membre = $reponse[0]->getMembre();

                $smartReponses = $reponseRepository->getNbCertifForUser($membre->getId());
                $nb_questions_membre = $questionRepository->getNbQuestionsForUser($membre->getId());

                //Ajour d'une réponse dans le tableau de sortie
                array_push($returnArray, array(
                    'id'=>$reponse[0]->getId(),
                    'description'=>$reponse[0]->getDescription(),
                    'date'=>$reponse[0]->getDate()->format('d-m-Y à H:i'),
                    'up_vote'=> (int) $reponse['upVote'],
                    'down_vote'=> (int) $reponse['downVote'],
                    'membre_username'=>$membre->getUsername(),
                    'membre_reputation'=>$membre->getReputation(),
                    'commentaires'=>$commentairesReturn,
                    'is_certif'=>$isCertif,
                    'is_validated'=>$isValid,
                    'is_voted'=>$isVoted,
                    'smart_reponses'=> (int) $smartReponses,
                    'nb_questions_membre'=> (int) $nb_questions_membre,
                    'points_membre'=> (int) $membre->getCagnotte()
                ));
            }
        }

        return new Response(json_encode($returnArray));

    }


    public function getSearchAction(){

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


    public function setUpVoteAction($reponse, Request $request){
        
        //Check si le user est loggé ou pas
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {

            return new Response(json_encode(array(
                'status'=>'error',
                'error'=>'NOT_LOGGED',
                'error_msg'=>'Vous devez être connécté pour pouvoir voter.'
            )));

        }else{//User pas loggé

            $noteReponseRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:noteReponse');
            $reponseRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:reponse');

            $notes = $noteReponseRepository->findBy(array(
                'membre'=>$user,
                'reponse'=>$reponse
            ));

            if(count($notes)>0){
                return new Response(json_encode(array(
                    'status'=>'error',
                    'error'=>'ALREADY_VOTED',
                    'error_msg'=>'Vous avez déjà voté!'
                )));
            }else{

                $reponseEntity=$reponseRepository->findById($reponse);

                if(count($reponseEntity)<1){
                    return new Response(json_encode(array(
                        'status'=>'error',
                        'error'=>'REPONSE_NOT_EXISTS',
                        'error_msg'=>'Cette réponse n\'éxiste pas!'
                    )));
                }

                $newNoteReponse = new \SmartUnity\AppBundle\Entity\noteReponse();
                $newNoteReponse->setNote(1);
                $newNoteReponse->setMembre($user);
                $newNoteReponse->setReponse($reponseEntity[0]);

                $em = $this->getDoctrine()->getManager();
                $em->persist($newNoteReponse);
                $em->flush();

                return new Response(json_encode(array(
                    'status'=>'ok',
                    'msg'=>'Votre vote à été pris en compte!'
                )));

            }


        }
    }

    public function setDownVoteAction($reponse){
        //Check si le user est loggé ou pas
        $user = $this->container->get('security.context')->getToken()->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {

            return new Response(json_encode(array(
                'status'=>'error',
                'error'=>'NOT_LOGGED',
                'error_msg'=>'Vous devez être connécté pour pouvoir voter.'
            )));

        }else{//User pas loggé

            $noteReponseRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:noteReponse');
            $reponseRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:reponse');

            $notes = $noteReponseRepository->findBy(array(
                'membre'=>$user,
                'reponse'=>$reponse
            ));

            if(count($notes)>0){
                return new Response(json_encode(array(
                    'status'=>'error',
                    'error'=>'ALREADY_VOTED',
                    'error_msg'=>'Vous avez déjà voté!'
                )));
            }else{

                $reponseEntity=$reponseRepository->findById($reponse);

                if(count($reponseEntity)<1){
                    return new Response(json_encode(array(
                        'status'=>'error',
                        'error'=>'REPONSE_NOT_EXISTS',
                        'error_msg'=>'Cette réponse n\'éxiste pas!'
                    )));
                }

                $newNoteReponse = new \SmartUnity\AppBundle\Entity\noteReponse();
                $newNoteReponse->setNote(-1);
                $newNoteReponse->setMembre($user);
                $newNoteReponse->setReponse($reponseEntity[0]);

                $em = $this->getDoctrine()->getManager();
                $em->persist($newNoteReponse);
                $em->flush();

                return new Response(json_encode(array(
                    'status'=>'ok',
                    'msg'=>'Votre vote à été pris en compte!'
                )));

            }


        }
    }


}