<?php

namespace SmartUnity\QuestionReponseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

class QuestionReponseController extends Controller {

    public function indexAction() {
        // afficher 3 onglets, avec quelques questions... de la page accueil
        //Redirection vers la liste des questions (pour l'instant)
        return $this->redirect($this->generateUrl('smart_unity_question_reponse_list_of_question'));
    }

    public function displayListOfQuestionAction($type, $page, Request $request) {


        $nbParPage = 20;

        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }


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


        $response = $this->forward('SmartUnityQuestionReponseBundle:Ajax:getQuestions', array(
            'type' => $type,
            'page' => $page,
            'nbParPage' => $nbParPage
        ));

        //Suppression de l'en tête HTTP et décodage du JSON

        $cleanJSON = explode('[', $response, 3);
        $listeQuestions = json_decode('[' . $cleanJSON[1]);
        //Le tableau JSON contient une ligne d'entête qui contient les infos à propos de
        //la requête pour vérifier son authenticité... 
        //On récupère des infos utiles pour la pagination..
        $nbPages = $listeQuestions[0]->nbPages;

        if ($page > $nbPages)
            $page = 1;

        //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
        if ($listeQuestions[0]->type == $type && $listeQuestions[0]->nbParPage == $nbParPage && $listeQuestions[0]->page == $page) {
            unset($listeQuestions[0]);
        } else {
            throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityQuestionReponseBundle:AjaxController');
        }


        //Génération de la pagination en statique (si pas de JS)
        $pagination = array();
        if ($page != 1) {
            array_push($pagination, array('<<', '1', '-4'));
            array_push($pagination, array('<', $page - 1, '-4'));
        }
        for ($i = -2; $i < 3; $i++) {
            if (($page + $i) >= 1 && ($page + $i) <= $nbPages) {

                array_push($pagination, array($page + $i, $page + $i, $i));
            }
        }
        if ($page < $nbPages) {
            array_push($pagination, array('>', $page + 1, '3'));
            array_push($pagination, array('>>', $nbPages, '4'));
        }

/////Creation du formulaires pour les filtres
        $QuestionRecherche = new \SmartUnity\AppBundle\Entity\Question();
        $formQuestion = $this->createForm('smartunity_filtres_repondre', $QuestionRecherche, array(
            'action' => $this->generateUrl('smart_unity_question_reponse_repondre_questions')));

        $template = sprintf('SmartUnityQuestionReponseBundle:Display:ListeQuestion.html.twig');
        return $this->render($template, array(
                    'error' => $error,
                    'page' => $page,
                    'type' => $type,
                    'nbPages' => $nbPages,
                    'listeQuestions' => $listeQuestions,
                    'countListe' => count($listeQuestions),
                    'nbParPage' => $nbParPage,
                    'pagination' => $pagination,
                    'formQuestion' => $formQuestion->createView()
        ));
    }

    public function searchDemanderQuestionAction(Request $request) {

        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }


        $question = $this->getRequest()->query->get('q');
        $page = $this->getRequest()->query->get('p');
        $nbParPage = 10;

        $finder = $this->container->get('fos_elastica.finder.smartunity.question');



//Création query principale pour la pagination.        
        $mainQuery = new \Elastica\Query();
        $mainQuery->setSize($nbParPage);
        $mainQuery->setFrom(($page - 1) * $nbParPage);
//FIN Création query principale pour la pagination.
//Création de la boolean query et ajout des boosts pour les questions certifiées et validées.        
        $queryBool = new \Elastica\Query\Bool();
        $subValidatedQueryTerm = new \Elastica\Query\Term();
        $subValidatedQueryTerm->setTerm('isValidatedQuestion', true, 1);
        $subCertifiedQueryTerm = new \Elastica\Query\Term();
        $subCertifiedQueryTerm->setTerm('isCertifiedQuestion', true, 2);
        $queryBool->addShould($subValidatedQueryTerm);
        $queryBool->addShould($subCertifiedQueryTerm);
//FIN  Création de la boolean query et ajout des boosts pour les questions certifiées et validées. 


        if ($question == '') {

            $subQueryMatchAll = new \Elastica\Query\MatchAll();
            $queryBool->addMust($subQueryMatchAll);
        } else {
            $sujetQuery = new \Elastica\Query\Match;
            $sujetQuery->setFieldQuery('sujet', $question);

            $descriptionQuery = new \Elastica\Query\Match;
            $descriptionQuery->setFieldQuery('description', $question);
//    $descriptionQuery->setFieldParam('description', 'analyzer', 'custom_french_analyzer'); 

            $queryBool->addShould($sujetQuery);
            $queryBool->addShould($descriptionQuery);
        }
        $mainQuery->setQuery($queryBool);

        $nbQuestions = count($finder->find($queryBool, 1000000));
        $nbPages = ceil($nbQuestions / $nbParPage);

//        $resultSet = $finder->findHybrid(new Elastica\Query($query->toArray()));

        $resultSet = $finder->find($mainQuery);
        $listeQuestions = $this->generateSearchResults($resultSet);

        //Génération de la pagination en statique (si pas de JS)
        $pagination = array();
        if ($page != 1) {
            array_push($pagination, array('<<', '1', '-4'));
            array_push($pagination, array('<', $page - 1, '-3'));
        }
        for ($i = -2; $i < 3; $i++) {
            if (($page + $i) >= 1 && ($page + $i) <= $nbPages) {
                array_push($pagination, array($page + $i, $page + $i, $i));
            }
        }
        if ($page < $nbPages) {
            array_push($pagination, array('>', $page + 1, '3'));
            array_push($pagination, array('>>', $nbPages, '4'));
        }

///Construction du formulaire pour les filtres avec comme valeur par défaut la recherche     
        $QuestionRecherche = new \SmartUnity\AppBundle\Entity\Question();

        $formQuestion = $this->createForm('smartunity_filtres_repondre', $QuestionRecherche, array(
            'action' => $this->generateUrl('smart_unity_question_reponse_repondre_questions')));

        $template = sprintf('SmartUnityQuestionReponseBundle:Display:Recherche.html.twig');
        return $this->render($template, array(
                    'error' => $error,
                    'requete' => $question,
                    'page' => $page,
                    'nbPages' => $nbPages,
                    'listeQuestions' => $listeQuestions,
                    'countListe' => $nbQuestions,
                    'nbParPage' => $nbParPage,
                    'pagination' => $pagination,
                    'formQuestion' => $formQuestion->createView(),
        ));
    }

    public function searchRepondreQuestionAction(Request $request, $page) {

        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }
        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }

        $QuestionRecherche = new \SmartUnity\AppBundle\Entity\Question();

        $formQuestion = $this->createForm('smartunity_filtres_repondre', $QuestionRecherche, array(
            'action' => $this->generateUrl('smart_unity_question_reponse_repondre_questions')));

        $nbParPage = 10;
        $finder = $this->container->get('fos_elastica.finder.smartunity.question');
        $formQuestion->bind($this->getRequest());

//récupération des champs de formulaire
        $marque = $formQuestion->get('marque')->getData();
        $os = $formQuestion->get('os')->getData();
        $typeQuestion = $formQuestion->get('typeQuestion')->getData();
        $motCle = $formQuestion->get('motCle')->getData();

//Création query générale
        $mainQuery = new \Elastica\Query();
        $mainQuery->setSize($nbParPage);
        $mainQuery->setFrom(($page - 1) * $nbParPage);
//FIN  Création query générale
//
//  
//Création de la boolean query et ajout des boosts pour les questions certifiées et validées.        
        $queryBool = new \Elastica\Query\Bool();
        $subValidatedQueryTerm = new \Elastica\Query\Term();
        $subValidatedQueryTerm->setTerm('isValidatedQuestion', true, 1);
        $subCertifiedQueryTerm = new \Elastica\Query\Term();
        $subCertifiedQueryTerm->setTerm('isCertifiedQuestion', true, 2);
        $queryBool->addShould($subValidatedQueryTerm);
        $queryBool->addShould($subCertifiedQueryTerm);
//FIN  Création de la boolean query et ajout des boosts pour les questions certifiées et validées.         
//Création de la query quasi générale        
        if (is_null($marque) && is_null($os) && is_null($typeQuestion) && is_null($motCle)) {
            $subQueryMatchAll = new \Elastica\Query\MatchAll();
            $queryBool->addMust($subQueryMatchAll);
        } else {

            if (isset($marque)) {
                $marque = $marque->getNom();
                $marqueQuery = new \Elastica\Query\Match;
                $marqueQuery->setFieldQuery('marque.nom', $marque);
                $nestedMarqueQuery = new \Elastica\Query\Nested;
                $nestedMarqueQuery->setPath('marque');
                $nestedMarqueQuery->setQuery($marqueQuery);
                $queryBool->addMust($nestedMarqueQuery);
            }

            if (isset($os)) {
                $os = $os->getNom();
                $osQuery = new \Elastica\Query\Match;
                $osQuery->setFieldQuery('os.nom', $os);
                $nestedOsQuery = new \Elastica\Query\Nested;
                $nestedOsQuery->setPath('os');
                $nestedOsQuery->setQuery($osQuery);
                $queryBool->addMust($nestedOsQuery);
            }

            if (isset($typeQuestion)) {
                $typeQuestion = $typeQuestion->getNom();
                $typeQuestionQuery = new \Elastica\Query\Match;
                $typeQuestionQuery->setFieldQuery('typeQuestion.nom', $typeQuestion);
                $nestedTypeQuestionQuery = new \Elastica\Query\Nested;
                $nestedTypeQuestionQuery->setPath('typeQuestion');
                $nestedTypeQuestionQuery->setQuery($typeQuestionQuery);
                $queryBool->addMust($nestedTypeQuestionQuery);
            }

            if (isset($motCle)) {
                $sujetQuery = new \Elastica\Query\Match;
                $sujetQuery->setFieldQuery('sujet', $motCle);
                $descriptionQuery = new \Elastica\Query\Match;
                $descriptionQuery->setFieldQuery('description', $motCle);
                $queryBool->addShould($sujetQuery);
                $queryBool->addShould($descriptionQuery);
            }
        }

        $mainQuery->setQuery($queryBool);
        $nbQuestions = count($finder->find($queryBool, 1000000));
        $nbPages = ceil($nbQuestions / $nbParPage);
        $resultSet = $finder->find($mainQuery);

        $listeQuestions = $this->generateSearchResults($resultSet);


//         Génération de la pagination en statique (si pas de JS)
        $pagination = array();
        if ($page != 1) {
            array_push($pagination, array('<<', '1', '-4'));
            array_push($pagination, array('<', $page - 1, '-3'));
        }
        for ($i = -2; $i < 3; $i++) {
            if (($page + $i) >= 1 && ($page + $i) <= $nbPages)
                array_push($pagination, array($page + $i, $page + $i, $i));
        }
        if ($page < $nbPages) {
            array_push($pagination, array('>', $page + 1, '3'));
            array_push($pagination, array('>>', $nbPages, '4'));
        }

        $template = sprintf('SmartUnityQuestionReponseBundle:Display:Recherche.html.twig');

        return $this->render($template, array(
                    'error' => $error,
                    'page' => $page,
                    'nbPages' => $nbPages,
                    'requete' => '',
                    'listeQuestions' => $listeQuestions,
                    'countListe' => $nbQuestions,
                    'nbParPage' => $nbParPage,
                    'pagination' => $pagination,
                    'formQuestion' => $formQuestion->createView(),
        ));
    }

    private function generateSearchResults($resultSet) {

        $listeQuestions = array();
        $reponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:reponse');
        $questionRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:question');
        foreach ($resultSet as $Question) {


            $descriptionBestReponse = null;
            $auteurBestreponse = null;
            $dateBestReponse = null;
            $is_certif_question = false;
            $is_validated_question = false;
            $remunerationQuestion = $Question->getRemuneration() + $Question->getSupplementRemuneration();

            if ($Question->getIsValidatedQuestion()) {
                foreach ($Question->getReponses() as $reponse) {
                    if ($reponse->getDateCertification() instanceof \DateTime) {
                        $auteurBestreponse = $reponse->getMembre()->getUsername();
                        $dateBestReponse = $reponse->getDate()->format('d-m-Y à H:i');
                        $is_certif_question = true;
                        $is_validated_question = true;
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
            array_push($listeQuestions, array(
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
        return $listeQuestions;
    }

    public function displayReponseAction(Request $request, $slug, $tri, $page) {

        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }

        //Affichage de LA question avec liste réponses
        //Fonctionne de la même manière que displayListOfQuestionAction()
        $nbParPage = 10;

        $response = $this->forward('SmartUnityQuestionReponseBundle:Ajax:getReponses', array(
            'slug' => $slug,
            'page' => $page,
            'nbParPage' => $nbParPage,
            'tri' => $tri
        ));

        if (strpos($response, '404 Not Found') !== false)
            throw new NotFoundHttpException("Cette question n'a pas encore été posée!");


        //Suppression de l'en tête HTTP et décodage du JSON
        $cleanJSON = array();
        $listeReponses = array();
        $rebuildJSON = '';
        $cleanJSON = explode('[', $response, 2);
        $listeReponses = json_decode('[' . $cleanJSON[1]);
        //Le tableau JSON contient une ligne d'entête qui contient les infos à propos de
        //la requête pour vérifier son authenticité... 
        //On récupère des infos utiles pour la pagination..
        $nbPages = $listeReponses[0]->nbPages;
        $nbReponses = $listeReponses[0]->nbReponses;
        $taille = count($listeReponses);
        $voteReponses = $listeReponses[($taille - 1)];

        if ($page > $nbPages)
            $page = 1;

        //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
        if ($listeReponses[0]->slug == $slug && $listeReponses[0]->nbParPage == $nbParPage && $listeReponses[0]->page == $page && $listeReponses[0]->tri == $tri) {
            unset($listeReponses[0]);
            unset($listeReponses[($taille - 1)]);
        } else {
            throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityQuestionReponseBundle:AjaxController');
        }


        //Génération de la pagination en statique (si pas de JS)
        $pagination = array();
        if ($page != 1) {
            array_push($pagination, array('<<', '1', '-4'));
            array_push($pagination, array('<', $page - 1, '-4'));
        }
        for ($i = -2; $i < 3; $i++) {
            if (($page + $i) >= 1 && ($page + $i) <= $nbPages)
                array_push($pagination, array($page + $i, $page + $i, $i));
        }
        if ($page < $nbPages) {
            array_push($pagination, array('>', $page + 1, '3'));
            array_push($pagination, array('>>', $nbPages, '4'));
        }

        $questionRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:question');
        $reponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:reponse');
        $avatarRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:avatar');

        $question = $questionRepository->findOneBySlug($slug);

        $isValidated = $question->getIsValidatedQuestion();

        $isCertif = $question->getIsCertifiedQuestion();
        $isAnswered = FALSE;
        $membre = $question->getMembre();

        $smartReponses = $reponseRepository->getNbCertifForUser($membre->getId());
        $nb_questions_membre = $questionRepository->getNbQuestionsForUser($membre->getId());
        $nb_reponses_membre = $reponseRepository->getNbReponsesForUser($membre->getId());

        $avatar = $avatarRepository->find($membre->getId());
        if (isset($avatar)) {
            $avatar = $avatar->getWebPath();
        }

// Creation des formulaires        
        $newCommentaireQuestion = new \SmartUnity\AppBundle\Entity\CommentaireQuestion();
        $formCommentaireQuestion = $this->createFormBuilder($newCommentaireQuestion)
                ->add('description', 'textarea', array('label' => false))
                ->getForm();

        $newCommentaireReponse = new \SmartUnity\AppBundle\Entity\CommentaireReponse();
        $formCommentaireReponse = $this->createFormBuilder($newCommentaireReponse)
                ->add('description', 'textarea', array('label' => false))
                ->getForm();

        $newReponse = new \SmartUnity\AppBundle\Entity\Reponse();
        $formReponse = $this->createFormBuilder($newReponse)
                ->add('description', 'ckeditor', array(
                    'config' => array(
                        'toolbar' => array(
                            array(
                                'name' => 'basicstyles',
                                'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                            ),
                            array(
                                'name' => 'paragraph',
                                'groups' => array('list', 'indent', 'align'),
                                'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',)),
                            array(
                                'name' => 'links',
                                'items' => array('Link', 'Unlink', '-', 'Image', 'Smiley', 'SpecialChar')
                            ),
                            array(
                                'name' => 'styles',
                                'items' => array('Font', 'FontSize'),
                            ),
                            array(
                                'name' => 'colors',
                                'items' => array('TextColor' , 'BGColor'),
                            ),
                        ),
                        'extraPlugins' => 'simpleuploads',
                        'uiColor' => '#ffffff',
                        'removePlugins' => 'elementspath',
                    ),
                    'required' => true))
                ->add('valider', 'submit')
                ->getForm();

        $formEditReponse = $this->createFormBuilder()
                ->add('description', 'ckeditor', array(
                    'config' => array(
                        'toolbar' => array(
                            array(
                                'name' => 'basicstyles',
                                'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                            ),
                            array(
                                'name' => 'paragraph',
                                'groups' => array('list', 'indent', 'align'),
                                'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',)),
                            array(
                                'name' => 'links',
                                'items' => array('Link', 'Unlink', '-', 'Image', 'Smiley', 'SpecialChar')
                            ),
                            array(
                                'name' => 'styles',
                                'items' => array('Font', 'FontSize'),
                            ),
                            array(
                                'name' => 'colors',
                                'items' => array('TextColor', 'BGColor')
                            ),
                        ),
                        'extraPlugins' => 'simpleuploads',
                        'uiColor' => '#ffffff',
                        'removePlugins' => 'elementspath'
                    ),
                    'required' => true))
                ->add('valider', 'submit')
                ->getForm();
        $formDeleteReponse = $this->createFormBuilder()
                ->add('oui', 'submit')
                ->getForm();

        if (true === $this->get('security.context')->isGranted('ROLE_USER')) {
            $user = $this->container->get('security.context')->getToken()->getUser();

            foreach ($listeReponses as $reponse) {
                if ($reponse->membre_id == $user->getId()) {
                    $isAnswered = TRUE;
                    break;
                }
            }


            $formSoutien = $this->createFormBuilder()
                    ->setAction($this->generateUrl('smart_unity_question_reponse_add_soutien_question', array('slug' => $slug)))
                    ->add('soutien', 'integer', array('attr' => array('min' => 0, 'max' => ($user->getCagnotte()))))
                    ->add('valider', 'submit')
                    ->getForm();
        } else {
            $formSoutien = $this->createFormBuilder()->getForm();
        }

        $isup_rep = $voteReponses->isup;
        $isdown_rep = $voteReponses->isdown;


        $template = sprintf('SmartUnityQuestionReponseBundle:Display:Reponse.html.twig');
        return $this->render($template, array(
                    'error' => $error,
                    'nbReponses' => $nbReponses,
                    'nbPages' => $nbPages,
                    'tri' => $tri,
                    'page' => $page,
                    'slug' => $slug,
                    'is_certif' => $isCertif,
                    'is_validated' => $isValidated,
                    'listeReponses' => $listeReponses,
                    'pagination' => $pagination,
                    'nbParPage' => $nbParPage,
                    'question' => $question,
                    'smart_reponses' => (int) $smartReponses,
                    'nb_questions_membre' => (int) $nb_questions_membre,
                    'nb_reponses_membre' => (int) $nb_reponses_membre,
                    'membre_id' => $membre->getId(),
                    'avatar' => $avatar,
                    'formCommentaireQuestion' => $formCommentaireQuestion->createView(),
                    'formCommentaireReponse' => $formCommentaireReponse->createView(),
                    'formReponse' => $formReponse->createView(),
                    'formEditReponse' => $formEditReponse->createView(),
                    'formDeleteReponse' => $formDeleteReponse->createView(),
                    'formSoutien' => $formSoutien->createView(),
					'is_answered_by_user' => $isAnswered,
                    'isup' => $isup_rep,
                    'isdown' => $isdown_rep
        ));
    }

    public function addQuestionAction() {
        $newQuestion = new \SmartUnity\AppBundle\Entity\Question();

        $newQuestion->setRemuneration(10);
        $user = $this->getUser();

        $dotationMax = $user->getCagnotte() + 10;

        $formQuestion = $this->createFormBuilder($newQuestion)
                ->add('sujet', 'textarea', array(
                    'required' => true))
                ->add('description', 'ckeditor', array(
                    'config' => array(
                        'toolbar' => array(
                            array(
                                'name' => 'basicstyles',
                                'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                            ),
                            array(
                                'name' => 'paragraph',
                                'groups' => array('list', 'indent', 'align'),
                                'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',)),
                            array(
                                'name' => 'links',
                                'items' => array('Link', 'Unlink', '-', 'Image', 'Smiley', 'SpecialChar')
                            ),
                            array(
                                'name' => 'styles',
                                'items' => array('Font', 'FontSize'),
                            ),
                            array(
                                'name' => 'colors',
                                'items' => array('TextColor', 'BGColor')
                            ),
                        ),
                        'uiColor' => '#ffffff',
                        'removePlugins' => 'elementspath'
                    ),
                    'required' => true))
                ->add('marque', 'entity', array(
                    'class' => 'SmartUnityAppBundle:marque',
                    'property' => 'nom',
                    'required' => false,
                    'empty_data' => NULL))
                ->add('os', 'entity', array(
                    'class' => 'SmartUnityAppBundle:os',
                    'property' => 'nom',
                    'required' => false,
                    'empty_data' => NULL))
                ->add('typeQuestion', 'entity', array(
                    'class' => 'SmartUnityAppBundle:typeQuestion',
                    'property' => 'nom',
                    'required' => false))
                ->add('remuneration', 'integer', array('attr' => array('min' => 10, 'max' => ($dotationMax))))
                ->add('save', 'submit', array('label' => 'Poser ma question'))
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {
            $formQuestion->bind($this->getRequest());
            if ($formQuestion->isValid()) {
                $newQuestion->setMembre($user);
                $newQuestion->setSignaler(false);

                $newQuestion->setDate(new \DateTime(date("Y-m-d H:i:s"))); //date locale

                $slug = $this->slugify($formQuestion->get('sujet')->getData());

                $newQuestion->setSlug($slug);
                $cagnotte = $user->getCagnotte() - $formQuestion->get('remuneration')->getData() + 10;

                if ($cagnotte >= 0) {
                    $user->setCagnotte($cagnotte);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($newQuestion);
                    $em->flush();

                    return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
                }
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Frame:AddQuestion.html.twig', array(
                    'formQuestion' => $formQuestion->createView(),
                    'dotationMax' => $dotationMax
        ));
    }

    public function editQuestionAction($slug) {
        $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);
        $user = $this->getUser();
        $ancienneDotation = $question->getRemuneration();
        $dotationMax = $ancienneDotation + $user->getCagnotte();

        $formEditQuestion = $this->createFormBuilder($question)
                ->add('sujet', 'textarea', array(
                    'required' => true))
                ->add('description', 'ckeditor', array(
                    'config' => array(
                        'toolbar' => array(
                            array(
                                'name' => 'basicstyles',
                                'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                            ),
                            array(
                                'name' => 'clipboardundo',
                                'groups' => array('clipboard', 'undo'),
                                'items' => array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo')),
                            array(
                                'name' => 'paragraph',
                                'groups' => array('list', 'indent', 'align'),
                                'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',)),
                            array(
                                'name' => 'links',
                                'items' => array('Link', 'Unlink', '-', 'Image', 'Smiley', 'SpecialChar')
                            ),
                            array(
                                'name' => 'styles',
                                'items' => array('Font', 'FontSize'),
                            ),
                            array(
                                'name' => 'colors',
                                'items' => array('TextColor', 'BGColor')
                            ),
                        ),
                        'uiColor' => '#ffffff',
                        'removePlugins' => 'elementspath'
                    ),
                    'required' => true))
                ->add('marque', 'entity', array(
                    'class' => 'SmartUnityAppBundle:marque',
                    'property' => 'nom',
                    'required' => false,
                    'empty_data' => NULL))
                ->add('os', 'entity', array(
                    'class' => 'SmartUnityAppBundle:os',
                    'property' => 'nom',
                    'required' => false,
                    'empty_data' => NULL))
                ->add('typeQuestion', 'entity', array(
                    'class' => 'SmartUnityAppBundle:typeQuestion',
                    'property' => 'nom',
                    'required' => false))
                ->add('remuneration', 'integer', array(
                    'attr' => array(
                        'class' => 'input-dotation',
                        'min' => '10',
                        'max' => $dotationMax
                    ),
                ))
                ->add('save', 'submit', array('label' => 'Modifier ma question'))
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {


            $formEditQuestion->bind($this->getRequest());

            if ($formEditQuestion->isValid()) {

                $nouvelleDotation = $formEditQuestion->get('remuneration')->getData();

                $cagnotte = $user->getCagnotte() - $nouvelleDotation + $ancienneDotation;
                if ($cagnotte >= 0 && $nouvelleDotation >= 10) {
                    $user->setCagnotte($cagnotte);
                    $question->setDateModification(new \DateTime(date("Y-m-d H:i:s")));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($question);
                    $em->flush();

                    $this->get('session')->getFlashBag()->add(
                            'editedQuestion', 'Votre question a bien été modifiée'
                    );

                    return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
                }
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Frame:EditQuestion.html.twig', array(
                    'formEditQuestion' => $formEditQuestion->createView(),
                    'dotationMax' => $dotationMax
        ));
    }

    public function slugify($str) {

        // transliterate
        if (function_exists('iconv')) {
            $str = iconv('utf-8', 'us-ascii//TRANSLIT', $str);
        }
        $search = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',);
        $replace = array('s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', 'a', 'a', 'e', 'E');
        $str = str_ireplace($search, $replace, strtolower(trim($str)));
        $str = preg_replace('/[^\w\d\-\ ]/', '', $str);
        $str = str_replace(' ', '-', $str);
        $str = preg_replace('/\-{2,}/', '-', $str);
//        
//        ///Test de l'unicité du slug
//        $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($str);
//        if (isset($question)) {
//            return false;
//        }
        return $str;
    }

    public function addReponseAction($slug) {
        $newReponse = new \SmartUnity\AppBundle\Entity\Reponse();
        $formReponse = $this->createFormBuilder($newReponse)
                ->add('description', 'ckeditor', array(
                    'config' => array(
                        'toolbar' => array(
                            array(
                                'name' => 'basicstyles',
                                'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                            ),
                            array(
                                'name' => 'clipboardundo',
                                'groups' => array('clipboard', 'undo'),
                                'items' => array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo')),
                            array(
                                'name' => 'paragraph',
                                'groups' => array('list', 'indent', 'align'),
                                'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',)),
                            array(
                                'name' => 'links',
                                'items' => array('Link', 'Unlink', '-', 'Image', 'Smiley', 'SpecialChar')
                            ),
                            array(
                                'name' => 'styles',
                                'items' => array('Font', 'FontSize'),
                            ),
                            array(
                                'name' => 'colors',
                                'items' => array('TextColor', 'BGColor'),
                                'extraPlugins' => 'simpleuploads'
                            ),
                        ),
                        'extraPlugins' => 'simpleuploads',
                        'uiColor' => '#ffffff',
                        'removePlugins' => 'elementspath',
                    ),
                    'required' => true))
                ->add('valider', 'submit')
                ->getForm();

        $user = $this->getUser();
        $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);

        foreach ($question->getReponses() as $reponse) {
            if ($reponse->getMembre() == $user) {

                $this->get('session')->getFlashBag()->add(
                        'alreadyAnswered', 'Vous avez déjà répondu à cette question! Vous pouvez seulement modifier votre réponse.'
                );

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            }
        }

        if ($this->getRequest()->getMethod() == 'POST') {
            $formReponse->bind($this->getRequest());
            if ($formReponse->isValid()) {

                $newReponse->setMembre($user);

                $newReponse->setDate(new \DateTime(date("Y-m-d H:i:s"))); //date locale

                $newReponse->setDateValidation(NULL);
                $newReponse->setDateCertification(NULL);

                $newReponse->SetQuestion($question);
                $newReponse->setSignaler(false);

                $membreQuestion = $question->getMembre();
                $urlQuestion = $this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug), true);
                $prefRepmembre = $membreQuestion->getPrefRep();
                if ($prefRepmembre == true) {

                    //Envoi du mail
                    $this->get('smart_unity_app.mailer')->newAnswerMessage($membreQuestion, $user, $urlQuestion);
//                    
//                    //Envoi du mail
//                    $urlQuestion = $this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug), true);
//                    $sujetMail = "Vous avez une nouvelle réponse!";
//                    $contenu = $user . " a répondu à votre question sur smartunity.fr. Allez vite consulter la réponse : " . $urlQuestion;
//                    $message = \Swift_Message::newInstance()
//                            ->setContentType('text/html')
//                            ->setSubject($sujetMail)
//                            ->setFrom(array('ne-pas-repondre@smartunity.fr' => 'Smart\'Unity'))
//                            ->setTo($membreQuestion->getEmail())
//                            ->setBody($contenu);
//                    $this->get('mailer')->send($message);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($newReponse);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                        'addedAnswer', 'Votre réponse a bien été ajoutée'
                );

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            }
            throw new \Exception('Votre réponse n\'a pas pu être ajoutée');
        }

        return $this->render('SmartUnityQuestionReponseBundle:Frame:AddReponse.html.twig', array(
                    'formReponse' => $formReponse->createView(),
        ));
    }

    public function editReponseAction($id, $slug) {
        $reponse = $this->getDoctrine()->getRepository('SmartUnityAppBundle:reponse')->find($id);

        $formEditReponse = $this->createFormBuilder($reponse)
                ->add('description', 'ckeditor', array(
                    'config' => array(
                        'toolbar' => array(
                            array(
                                'name' => 'basicstyles',
                                'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                            ),
                            array(
                                'name' => 'paragraph',
                                'groups' => array('list', 'indent', 'align'),
                                'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',)),
                            array(
                                'name' => 'links',
                                'items' => array('Link', 'Unlink', '-', 'Image', 'Smiley', 'SpecialChar')
                            ),
                            array(
                                'name' => 'styles',
                                'items' => array('Font', 'FontSize'),
                            ),
                            array(
                                'name' => 'colors',
                                'items' => array('TextColor', 'BGColor')
                            ),
                        ),
                        'extraPlugins' => 'simpleuploads',
                        'uiColor' => '#ffffff',
                        'removePlugins' => 'elementspath'
                    ),
                    'required' => true))
                ->add('valider', 'submit')
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {

            $formEditReponse->bind($this->getRequest());

            if ($formEditReponse->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($reponse);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                        'editedAnswer', 'Votre réponse a bien été modifiée'
                );

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            }
        }
        return new \Exception('Votre réponse n\'a pas pu être éditée');
    }
     public function SupprimerReponseAction($id, $slug) {
            if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedException();
            }
        $reponse = $this->getDoctrine()->getRepository('SmartUnityAppBundle:reponse')->find($id);

                     
                $em = $this->getDoctrine()->getManager();
                $em->remove($reponse);
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug, 'haveDeletedReponse' => '1')));

    }

    public function validationReponseAction($idReponse) {
        $reponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:reponse');

        $reponse = $reponseRepository->findById($idReponse);

        if (count($reponse) > 0) { //S'il y a des réponses
            $user = $this->container->get('security.context')->getToken()->getUser();

            if (!is_object($user) || !$user instanceof UserInterface) {
                throw new AccessDeniedException();
            } else { //Si tout va bien
                $question = $reponse[0]->getQuestion();

                if ($question->getMembre() != $user) {//TEST SI USER LOGGE A BIEN POSE LA QUESTION
                    throw new \Exception('Vous ne pouvez valider que des réponses à vos questions.');
                } else {

//Modifs sur la base de données                    
                    $reponse[0]->setDateValidation(new \DateTime(date("Y-m-d H:i:s")));
                    $repondant = $reponse[0]->getMembre();

                    $repondant->setReputation($repondant->getReputation() + 50);
                    $repondant->setCagnotte($repondant->getCagnotte() + $question->getRemuneration() + $question->getSupplementRemuneration());
                    $question->setIsValidatedQuestion(true);
                    $urlQuestion = $this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $question->getSlug()), true);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($reponse[0]);
                    $em->persist($repondant);
                    $em->persist($question);

                    $em->flush();


                    $prefRepValideeMembre = $repondant->getPrefRepValidee();
                    if ($prefRepValideeMembre == true) {
//                    //Envoi du mail
                        $this->get('smart_unity_app.mailer')->validatedAnswerMessage($repondant, $user, $question, $urlQuestion);
                    }

                    return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $question->getSlug())));
                }
            }
        } else {
            throw new \Exception('La reponse passée en paramètre n\'éxiste pas!');
        }
    }

    public function certificationReponseAction($idReponse) {
        $reponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:reponse');

        $reponse = $reponseRepository->findById($idReponse);
        $question = $reponse[0]->getQuestion();

        if (count($reponse) > 0) { //S'il y a des réponses
            if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedException();
            } else if ($reponse[0]->getDateValidation() == null) { //Si la question n'a pas été validée
                throw new \Exception('Cette réponse n\'est pas encore validée!');
            } else { //Si tout va bien
                $reponse[0]->setDateCertification(new \DateTime(date("Y-m-d H:i:s")));
                $repondant = $reponse[0]->getMembre();
                $repondant->setReputation($repondant->getReputation() + 50);
                $question->setIsCertifiedQuestion(true);
                $urlQuestion = $this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $question->getSlug()), true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($reponse[0]);
                $em->persist($repondant);
                $em->persist($question);
                $em->flush();

                $prefRepCertifieemembre = $repondant->getPrefRepCertifiee();

                if ($prefRepCertifieemembre == true) {
                    //Envoi du mail
                    $this->get('smart_unity_app.mailer')->certifiedAnswerMessage($repondant, $question, $urlQuestion);
                }

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $question->getSlug())));
            }
        } else {
            throw new \Exception('La reponse passée en paramètre n\'existe pas!');
        }
    }

    public function addCommentaireQuestionAction($slug) {
        $newCommentaireQuestion = new \SmartUnity\AppBundle\Entity\CommentaireQuestion();
        $formCommentaireQuestion = $this->createFormBuilder($newCommentaireQuestion)
                ->add('description', 'textarea', array('label' => false))
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {

            $formCommentaireQuestion->bind($this->getRequest());

            if ($formCommentaireQuestion->isValid()) {
                $user = $this->getUser();
                $newCommentaireQuestion->setMembre($user);
                $newCommentaireQuestion->setDate(new \DateTime(date("Y-m-d H:i:s"))); //date locale
                $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);
                $newCommentaireQuestion->SetQuestion($question);
                $newCommentaireQuestion->setSignaler(false);
                $prefCommentaireMembre = $question->getMembre()->getPrefComm();
                $em = $this->getDoctrine()->getManager();
                $em->persist($newCommentaireQuestion);
                $em->flush();



                $comment = $this->getDoctrine()->getRepository('SmartUnityAppBundle:commentaireQuestion')->getMembresCommentedQuestion($question->getId());

                foreach ($comment as $c) {

                    if ($c[1] != $user->getId() && $question->getMembre() != $user) {

                        $membre = $this->getDoctrine()->getRepository('SmartUnityAppBundle:membre')->find($c[1]);

                        if ($membre->getPrefComm() == true) {
                            //Envoi du mail`
                            $sujetQuestion = $question->getSujet();
                            $sujetMail = "Commentaire à votre question sur smartunity.fr";
                            $urlQuestion = $this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug), true);
                            $contenu = "Bonjour , <br/> Un commentaire a été ajouté à la question que vous avez commentée : \"" . $sujetQuestion . "\" par " . $user->getUsername() . ". <br/>  Rendez-vous sur " . $urlQuestion . " pour le découvrir. <br/><br/>A bientöt sur smartunity.fr ";
                            $mailMembre = $membre->getEmail();
                            $message = \Swift_Message::newInstance()
                                    ->setContentType('text/html')
                                    ->setSubject($sujetMail)
                                    ->setFrom(array('ne-pas-repondre@smartunity.fr' => 'Smart\'Unity'))
                                    ->setTo($mailMembre)
                                    ->setBody($contenu);
                            $this->get('mailer')->send($message);
                        }
                    }
                }
                if ($prefCommentaireMembre == true && $question->getMembre() != $user) {

                    //Envoi du mail`
                    $sujetQuestion = $question->getSujet();
                    $sujetMail = "Commentaire à votre question sur smartunity.fr";
                    $urlQuestion = $this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug), true);
                    $contenu = "Bonjour " . $question->getMembre()->getUsername() . ", <br/> Un commentaire a été ajouté à votre question : \"" . $sujetQuestion . "\" par " . $user->getUsername() . ". <br/>  Rendez-vous sur " . $urlQuestion . " pour le découvrir. <br/><br/>A bientöt sur smartunity.fr ";
                    $mailMembreQuestion = $question->getMembre()->getEmail();
                    $message = \Swift_Message::newInstance()
                            ->setContentType('text/html')
                            ->setSubject($sujetMail)
                            ->setFrom(array('ne-pas-repondre@smartunity.fr' => 'Smart\'Unity'))
                            ->setTo($mailMembreQuestion)
                            ->setBody($contenu);
                    $this->get('mailer')->send($message);
                }

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            } else {
                $this->get('session')->getFlashBag()->add(
                        'ProblemeCommentaire', 'Un problème est survenu'
                );
                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            }
        } else {
            $this->get('session')->getFlashBag()->add(
                    'ProblemeCommentaire', 'Un problème est survenu'
            );
            return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
        }
    }

    public function addCommentaireReponseAction($idReponse, $slug) {

        $newCommentaireReponse = new \SmartUnity\AppBundle\Entity\CommentaireReponse();
        $formCommentaireReponse = $this->createFormBuilder($newCommentaireReponse)
                ->add('description', 'textarea', array('label' => false))
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {
            $formCommentaireReponse->bind($this->getRequest());


            if ($formCommentaireReponse->isValid()) {
                $user = $this->getUser();
                $newCommentaireReponse->setMembre($user);

                $newCommentaireReponse->setDate(new \DateTime(date("Y-m-d H:i:s"))); //date locale
                $reponse = $this->getDoctrine()->getRepository('SmartUnityAppBundle:reponse')->find($idReponse);
                $newCommentaireReponse->setReponse($reponse);

                $newCommentaireReponse->setSignaler(false);
                $em = $this->getDoctrine()->getManager();
                $em->persist($newCommentaireReponse);
                $em->flush();

                $prefCommentaireMembre = $reponse->getMembre()->getPrefComm();

                $idMembresCommentaires = $this->getDoctrine()->getRepository('SmartUnityAppBundle:CommentaireReponse')->getMembresCommentedAnswer($reponse->getId());

                foreach ($idMembresCommentaires as $Commentaire) {
                    if ($Commentaire[1] != $this->getUser()->getId() && $reponse->getMembre() != $user) {

                        $membre = $this->getDoctrine()->getRepository('SmartUnityAppBundle:membre')->find($Commentaire[1]);

                        if ($membre->getPrefComm() == true) {
                            //Envoi du mail`
                            $sujetQuestion = $reponse->getQuestion()->getSujet();
                            $sujetMail = "Commentaire à votre réponse sur smartunity.fr";
                            $urlQuestion = $this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug), true);
                            $contenu = "Bonjour " . $reponse->getMembre()->getUsername() . ", <br/> Un commentaire a été ajouté à la réponse que vous avez également commentée concernant la question : \"" . $sujetQuestion . "\" par " . $user->getUsername() . ". <br/>  Rendez-vous sur " . $urlQuestion . " pour le découvrir. <br/><br/>A bientöt sur smartunity.fr ";
                            $mailMembre = $membre->getEmail();
                            $message = \Swift_Message::newInstance()
                                    ->setContentType('text/html')
                                    ->setSubject($sujetMail)
                                    ->setFrom(array('ne-pas-repondre@smartunity.fr' => 'Smart\'Unity'))
                                    ->setTo($mailMembre)
                                    ->setBody($contenu);
                            $this->get('mailer')->send($message);
                        }
                    }
                }

                if ($reponse->getMembre() != $user && $prefCommentaireMembre == true) {

                    //Envoi du mail`
                    $sujetQuestion = $reponse->getQuestion()->getSujet();
                    $sujetMail = "Commentaire à votre réponse sur smartunity.fr";
                    $urlQuestion = $this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug), true);
                    $contenu = "Bonjour " . $reponse->getMembre()->getUsername() . ", <br/> Un commentaire a été ajouté à votre réponse concernant la question : \"" . $sujetQuestion . "\" par " . $user->getUsername() . ". <br/>  Rendez-vous sur " . $urlQuestion . " pour le découvrir. <br/><br/>A bientöt sur smartunity.fr ";
                    $mailMembreQuestion = $reponse->getMembre()->getEmail();
                    $message = \Swift_Message::newInstance()
                            ->setContentType('text/html')
                            ->setSubject($sujetMail)
                            ->setFrom(array('ne-pas-repondre@smartunity.fr' => 'Smart\'Unity'))
                            ->setTo($mailMembreQuestion)
                            ->setBody($contenu);
                    $this->get('mailer')->send($message);
                }
                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            } else {
                $this->get('session')->getFlashBag()->add(
                        'ProblemeCommentaire', 'Un problème est survenu'
                );
                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            }
        }
        $this->get('session')->getFlashBag()->add(
                'ProblemeCommentaire', 'Un problème est survenu'
        );
        return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
    }

    public function addSoutienQuestionAction($slug) {

        $user = $this->container->get('security.context')->getToken()->getUser();
        $formSoutien = $this->createFormBuilder()
                ->setAction($this->generateUrl('smart_unity_question_reponse_add_soutien_question', array('slug' => $slug)))
                ->add('soutien', 'integer', array(
                    'attr' => array(
                        'min' => 0,
                        'max' => ($user->getCagnotte())
                    )
                ))
                ->add('valider', 'submit')
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {
            $formSoutien->bind($this->getRequest());

            if ($formSoutien->isValid()) {

                $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);

                if ($question->getSoutienMembres()->contains($user)) {
                    $question->setSupplementRemuneration($question->getSupplementRemuneration() + ($formSoutien->get('soutien')->getData()));
                    $user->setCagnotte($user->getCagnotte() - ($formSoutien->get('soutien')->getData()));
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($question);
                    $em->persist($user);
                    $em->flush();
                } else {

                    $question->setSupplementRemuneration($question->getSupplementRemuneration() + ($formSoutien->get('soutien')->getData()));
                    $question->getSoutienMembres()->add($user);
                    $user->setCagnotte($user->getCagnotte() - ($formSoutien->get('soutien')->getData()));
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                }

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            }
        }
        $this->get('session')->getFlashBag()->add(
                'ProblemeCommentaire', 'Un problème est survenu'
        );
        return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
    }

    public function signalerQuestionAction($slug) {

        $formSignaler = $this->createFormBuilder()
                ->setAction($this->generateUrl('smart_unity_question_reponse_signaler_question', array('slug' => $slug)))
                ->add('motif', 'textarea')
                ->add('Signaler', 'submit')
                ->getForm();

        $type = "une question";

        if ($this->getRequest()->getMethod() == 'POST') {
            $formSignaler->bind($this->getRequest());

            if ($formSignaler->isValid()) {
                $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);
                $question->setSignaler(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($question);
                $em->flush();

////////SEND MAIL TO SMARTUNITY                
                $motif = $formSignaler->get('motif')->getData();
                $rapporteur = $this->getUser();
                $sujetQuestion = $question->getSujet();

                $sujetMail = "Question numéro : " . $question->getId() . " signalée ";
                $expediteurMail = $rapporteur->getEmail();
                $contenu = "La question suivante à été signalée : " . $sujetQuestion . "<br/>Par: " . $rapporteur->getUsername() . "<br/>Son id est le : " . $question->getId() . "<br/>Motif: " . $motif;
                $message = \Swift_Message::newInstance()
                        ->setContentType('text/html')
                        ->setSubject($sujetMail)
                        ->setFrom($expediteurMail)
                        ->setTo("contact@smartunity.fr")
                        ->setBody($contenu);
                $this->get('mailer')->send($message);


                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Display:Signaler.html.twig', array(
                    'formSignaler' => $formSignaler->createView(),
                    'type' => $type
        ));
    }

    public function signalerReponseAction($idReponse) {

        $reponse = $this->getDoctrine()->getRepository('SmartUnityAppBundle:reponse')->find($idReponse);
        $questionSlug = $reponse->getQuestion()->getSlug();
        $formSignaler = $this->createFormBuilder()
                ->setAction($this->generateUrl('smart_unity_question_reponse_signaler_reponse', array('idReponse' => $idReponse)))
                ->add('motif', 'textarea')
                ->add('Signaler', 'submit')
                ->getForm();

        $type = "une réponse";
        if ($this->getRequest()->getMethod() == 'POST') {
            $formSignaler->bind($this->getRequest());

            if ($formSignaler->isValid()) {

                $reponse->setSignaler(true);
                $em = $this->getDoctrine()->getManager();
                $em->persist($reponse);
                $em->flush();

                $motif = $formSignaler->get('motif')->getData();
                $rapporteur = $this->getUser();
                $descriptionReponse = $reponse->getDescription();
                $sujetMail = "Réponse numéro : " . $idReponse . " signalée ";
                $expediteurMail = $rapporteur->getEmail();
                $contenu = "La réponse suivante à été signalée : " . $descriptionReponse . "<br/>Par: " . $rapporteur->getEmail() . "<br/>Son id est le : " . $idReponse . "<br/>Motif: " . $motif;
                $message = \Swift_Message::newInstance()
                        ->setContentType('text/html')
                        ->setSubject($sujetMail)
                        ->setFrom($expediteurMail)
                        ->setTo("contact@smartunity.fr")
                        ->setBody($contenu);
                $this->get('mailer')->send($message);

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $questionSlug)));
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Display:Signaler.html.twig', array(
                    'formSignaler' => $formSignaler->createView(),
                    'type' => $type));
    }

    public function signalerCommentaireQuestionAction($idCommentaireQuestion) {
        $formSignaler = $this->createFormBuilder()
                ->add('motif', 'textarea')
                ->add('Signaler', 'submit')
                ->getForm();
        $type = "un commentaire";
        if ($this->getRequest()->getMethod() == 'POST') {
            $formSignaler->bind($this->getRequest());

            if ($formSignaler->isValid()) {
                $commentaireQuestion = $this->getDoctrine()->getRepository('SmartUnityAppBundle:commentaireQuestion')->find($idCommentaireQuestion);
                $commentaireQuestion->setSignaler(true);
                $questionSlug = $commentaireQuestion->getQuestion()->getSlug();

                $em = $this->getDoctrine()->getManager();
                $em->persist($commentaireQuestion);
                $em->flush();


                $sujetQuestion = $commentaireQuestion->getQuestion()->getSujet();
                $sujetMail = "Commentaire question numéro :" . $idCommentaireQuestion . " signalé ";
                $contenu = "Le commentaire sur la question " . $sujetQuestion . "Son id est le : " . $idCommentaireQuestion;
                $message = \Swift_Message::newInstance()
                        ->setContentType('text/html')
                        ->setSubject($sujetMail)
                        ->setFrom(array('ne-pas-repondre@smartunity.fr' => 'Smart\'Unity'))
                        ->setTo("contact@smartunity.fr")
                        ->setBody($contenu);
                $this->get('mailer')->send($message);

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $questionSlug)));
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Display:Signaler.html.twig', array(
                    'formSignaler' => $formSignaler->createView(),
                    'type' => $type));
    }

    public function signalerCommentaireReponseAction($idCommentaireReponse) {
        $formSignaler = $this->createFormBuilder()
                ->add('motif', 'textarea')
                ->add('Signaler', 'submit')
                ->getForm();
        $type = "un commentaire";

        if ($this->getRequest()->getMethod() == 'POST') {
            $formSignaler->bind($this->getRequest());

            if ($formSignaler->isValid()) {
                $commentaireReponse = $this->getDoctrine()->getRepository('SmartUnityAppBundle:commentaireReponse')->find($idCommentaireReponse);
                $commentaireReponse->setSignaler(true);
                $questionSlug = $commentaireReponse->getReponse()->getQuestion()->getSlug();

                $em = $this->getDoctrine()->getManager();
                $em->persist($commentaireReponse);
                $em->flush();

                $sujetReponse = $commentaireReponse->getReponse()->getDescription();
                $sujetMail = "Commentaire réponse numéro :" . $idCommentaireReponse . " signalé ";
                $contenu = "Le commentaire sur la réponse " . $sujetReponse . "Son id est le : " . $idCommentaireReponse;
                $message = \Swift_Message::newInstance()
                        ->setContentType('text/html')
                        ->setSubject($sujetMail)
                        ->setFrom(array('ne-pas-repondre@smartunity.fr' => 'Smart\'Unity'))
                        ->setTo("contact@smartunity.fr")
                        ->setBody($contenu);
                $this->get('mailer')->send($message);

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $questionSlug)));
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Display:Signaler.html.twig', array(
                    'formSignaler' => $formSignaler->createView(),
                    'type' => $type));
    }

}
