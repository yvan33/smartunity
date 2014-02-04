<?php

namespace SmartUnity\QuestionReponseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use Elastica;


class QuestionReponseController extends Controller {

    public function indexAction() {
        // afficher 3 onglets, avec quelques questions... de la page accueil
        //Redirection vers la liste des questions (pour l'instant)
        return $this->redirect($this->generateUrl('smart_unity_question_reponse_list_of_question'));
    }

    public function displayListOfQuestionAction($type, $page, Request $request) {


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


        $nbParPage = 10;

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
        if ($listeQuestions[0]->type == $type && $listeQuestions[0]->nbParPage == $nbParPage && $listeQuestions[0]->page == $page)
            unset($listeQuestions[0]);
        else
            throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityQuestionReponseBundle:AjaxController');


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



        $template = sprintf('SmartUnityQuestionReponseBundle:Display:ListeQuestion.html.twig');
        return $this->render($template, array(
                    'error' => $error,
                    'page' => $page,
                    'type' => $type,
                    'nbPages' => $nbPages,
                    'listeQuestions' => $listeQuestions,
                    'countListe' => count($listeQuestions),
                    'nbParPage' => $nbParPage,
                    'pagination' => $pagination
        ));
    }

    public function searchQuestionAction(Request $request) {

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



        $reponseRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:reponse');

        $question = $this->getRequest()->query->get('q');
        $page = $this->getRequest()->query->get('p');
        $nbParPage = 10;

        $finder = $this->container->get('fos_elastica.finder.smartunity.question');


          if ($question == '') { 
          $query = new \Elastica\Query\MatchAll(); 
//          $query->addParam('from', $nbParPage * ($page - 1));
//          $query->addParam('size', $nbParPage);
          }
          else{
    $sujetQuery = new \Elastica\Query\Match;
    $sujetQuery->setFieldQuery('sujet', $question);
//    $sujetQuery->setFieldParam('sujet', 'analyzer', 'custom_french_analyzer');        
    
    $descriptionQuery = new \Elastica\Query\Match;
    $descriptionQuery->setFieldQuery('description', $question);
//    $descriptionQuery->setFieldParam('description', 'analyzer', 'custom_french_analyzer'); 
    
    $query = new \Elastica\Query\Bool();
    $query->addShould($sujetQuery);
    $query->addShould($descriptionQuery);
          }
          
//        $queryString = '{
//                "query" : {';
//
//        if ($question == '') {
//            $queryString .= '"match_all": {}';
//        } else {
//            $queryString .= '"query_string" : {
//                        "query" : "' . urldecode($question) . '",
//                        "fields": ["sujet^5","description"],
//                        "analyzer": "snowball"
//                    }';
//        }
//
//        $queryString .= '},
//                "from" : "' . $nbParPage * ($page - 1) . '",
//                "size" : "' . $nbParPage . '"
//                }';
//        
//    
//        $query = new Elastica\Query\Builder($queryString);

        
        

        $nbQuestions = count($finder->find($query));
                p($nbQuestions);

        $nbPages = ceil($nbQuestions / $nbParPage);

//        $resultSet = $finder->findHybrid(new Elastica\Query($query->toArray()));
        $resultSet = $finder->find($query);
        
        $listeQuestions = array();
        foreach ($resultSet as $result) {


//            $Question = $result->getTransformed();
            $Question = $result;


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

            array_push($listeQuestions, array(
                'id' => $Question->getId(),
                'sujet' => $Question->getSujet(),
                'description' => $Question->getDescription(),
                'date' => $Question->getDate()->format('d-m-Y à H:i'),
                'membre_username' => $Question->getMembre()->getUsername(),
                'membre_id' => $Question->getMembre()->getId(),
                'remuneration' => $Question->getRemuneration(),
                'nb_reponses' => $Question->getReponses()->count(),
                'best_reponse' => $bestReponse,
                'auteur_best_reponse' => $auteurBestreponse,
                'date_best_reponse' => $dateBestReponse,
                'slug' => $Question->getSlug(),
                'count_soutien' => $Question->getSoutienMembres()->count(),
                'soutenue' => $Question->getSoutienMembres()->contains($this->getUser())
            ));

            /*
              $html.= $result->getResult()->getScore();
             */
        }

        //Génération de la pagination en statique (si pas de JS)
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
                    'requete' => $question,
                    'page' => $page,
                    'nbPages' => $nbPages,
                    'listeQuestions' => $listeQuestions,
                    'countListe' => $nbQuestions,
                    'nbParPage' => $nbParPage,
                    'pagination' => $pagination
        ));
    }

    public function displayReponseAction(Request $request, $slug, $tri, $page, $haveAddedAnswer, $haveEditedQuestion, $haveEditedReponse) {

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
        $cleanJSON = explode('[', $response,2);
        $listeReponses = json_decode('[' . $cleanJSON[1]);
        //Le tableau JSON contient une ligne d'entête qui contient les infos à propos de
        //la requête pour vérifier son authenticité... 
        //On récupère des infos utiles pour la pagination..
        $nbPages = $listeReponses[0]->nbPages;
        $nbReponses = $listeReponses[0]->nbReponses;
        $taille= count($listeReponses);
        $voteReponses=$listeReponses[($taille-1)];

        if ($page > $nbPages)
            $page = 1;

        //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
        if ($listeReponses[0]->slug == $slug && $listeReponses[0]->nbParPage == $nbParPage && $listeReponses[0]->page == $page && $listeReponses[0]->tri == $tri)
        {
            unset($listeReponses[0]);
            unset($listeReponses[($taille-1)]);
        }
        else
        {
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

        $isValidated = $questionRepository->isQuestionValid($question->getId());
        $isCertif = $questionRepository->isQuestionCertif($question->getId());
        $isAnswered = FALSE;
        $membre = $question->getMembre();

        $smartReponses = $reponseRepository->getNbCertifForUser($membre->getId());
        $nb_questions_membre = $questionRepository->getNbQuestionsForUser($membre->getId());

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
                 ->add('description', 'textarea', array(
                    'label' => false,
                    'attr' => array('placeholder' => 'Tapez votre réponse ici...')
                ))
                ->add('valider', 'submit')
                ->getForm();

        $formEditReponse = $this->createFormBuilder()
                ->add('description', 'textarea', array(
                    'label' => false,
                ))
                ->add('valider', 'submit')
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
                    ->add('soutenir', 'submit')
                    ->getForm();
        } else {
            $formSoutien = $this->createFormBuilder()->getForm();
        }

        $isup_rep= $voteReponses->isup;
        $isdown_rep= $voteReponses->isdown;
       


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
                    'membre_id' => $membre->getId(),
                    'avatar' => $avatar,
                    'formCommentaireQuestion' => $formCommentaireQuestion->createView(),
                    'formCommentaireReponse' => $formCommentaireReponse->createView(),
                    'formReponse' => $formReponse->createView(),
                    'formEditReponse' => $formEditReponse->createView(),
                    'formSoutien' => $formSoutien->createView(),
                    'haveAddedAnswer' => $haveAddedAnswer,
                    'haveEditedQuestion' => $haveEditedQuestion,
                    'haveEditedReponse' => $haveEditedReponse,
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
                                'name'  => 'basicstyles',
                                'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                                ),
                            array(
                                 'name' => 'clipboardundo',
                                 'groups' => array('clipboard', 'undo'),
                                 'items' => array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ) ),
                            array(
                                'name' => 'paragraph',
                                'groups' => array('list', 'indent', 'align'),
                                'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',)),
                            array(
                                 'name' => 'links',
                                  'items' => array('Link', 'Unlink','-', 'Image','Smiley','SpecialChar')
                                 ),
                            array(
                                 'name' => 'styles', 
                                 'items' => array('Font', 'FontSize'),
                                 ),
                            array(
                                'name' => 'colors',
                                'items' =>array('TextColor', 'BGColor')
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
                    'empty_value' => 'Choisissez',
                    'empty_data' => NULL))
                ->add('modele', 'entity', array(
                    'class' => 'SmartUnityAppBundle:modele',
                    'property' => 'nom',
                    'required' => false,
                    'empty_value' => 'Choisissez',
                    'empty_data' => NULL))
                ->add('os', 'entity', array(
                    'class' => 'SmartUnityAppBundle:os',
                    'property' => 'nom',
                    'required' => false,
                    'empty_value' => 'Choisissez',
                    'empty_data' => NULL))
                ->add('typeQuestion', 'entity', array(
                    'class' => 'SmartUnityAppBundle:typeQuestion',
                    'property' => 'nom',
                    'empty_value' => 'Choisissez une option',
                    'required' => true))
                ->add('remuneration', 'integer', array('attr' => array('min' => 10, 'max' => ($dotationMax))))
                ->add('save', 'submit', array('label' => 'Poser ma question'))
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {
            $formQuestion->bind($this->getRequest());

            if ($formQuestion->isValid()) {
                $newQuestion->setMembre($user);
                $newQuestion->setSignaler(false);

                $newQuestion->setDate(new \DateTime(date("Y-m-d H:i:s"))); //date locale
                // $str = $formQuestion->get('sujet')->getData();
                // $search = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
                // $replace = array('s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', 'a', 'a', 'e', 'E');
                // $str = str_ireplace($search, $replace, strtolower(trim($str)));
                // $str = preg_replace('/[^\w\d\-\ ]/', '', $str);
                // $str = str_replace(' ', '-', $str);
                // $slug= preg_replace('/\-{2,}', '-', $str);

                $newQuestion->setSlug($this->slugify($formQuestion->get('sujet')->getData()));

                // $newQuestion->addSoutien($user);
                $cagnotte = $user->getCagnotte() - $formQuestion->get('remuneration')->getData() + 10;
                if ($cagnotte >= 0) {
                    $user->setCagnotte($cagnotte);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($newQuestion);
                    $em->flush();

                    return new Response('Votre question a bien été ajoutée');
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
                                'name'  => 'basicstyles',
                                'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                                ),
                            array(
                                 'name' => 'clipboardundo',
                                 'groups' => array('clipboard', 'undo'),
                                 'items' => array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ) ),
                            array(
                                'name' => 'paragraph',
                                'groups' => array('list', 'indent', 'align'),
                                'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',)),
                            array(
                                 'name' => 'links',
                                  'items' => array('Link', 'Unlink','-', 'Image','Smiley','SpecialChar')
                                 ),
                            array(
                                 'name' => 'styles', 
                                 'items' => array('Font', 'FontSize'),
                                 ),
                            array(
                                'name' => 'colors',
                                'items' =>array('TextColor', 'BGColor')
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
                    'empty_value' => 'Choisissez',
                    'empty_data' => NULL))
                ->add('modele', 'entity', array(
                    'class' => 'SmartUnityAppBundle:modele',
                    'property' => 'nom',
                    'required' => false,
                    'empty_value' => 'Choisissez',
                    'empty_data' => NULL))
                ->add('os', 'entity', array(
                    'class' => 'SmartUnityAppBundle:os',
                    'property' => 'nom',
                    'required' => false,
                    'empty_value' => 'Choisissez',
                    'empty_data' => NULL))
                ->add('typeQuestion', 'entity', array(
                    'class' => 'SmartUnityAppBundle:typeQuestion',
                    'property' => 'nom',
                    'empty_value' => 'Choisissez une option',
                    'required' => true))
                ->add('remuneration', 'integer', array('attr' => array('min' => $ancienneDotation, 'max' => $dotationMax)))
                ->add('save', 'submit', array('label' => 'Modifier ma question'))
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {


            $formEditQuestion->bind($this->getRequest());

            if ($formEditQuestion->isValid()) {

//                $question->setSlug($this->slugify($formEditQuestion->get('sujet')->getData()));
                $nouvelleDotation = $formEditQuestion->get('remuneration')->getData();
                $cagnotte = $user->getCagnotte() - $nouvelleDotation - $ancienneDotation;
                $user->setCagnotte($cagnotte);
                $question->setDateModification(new \DateTime(date("Y-m-d H:i:s")));

                $em = $this->getDoctrine()->getManager();
                $em->persist($question);
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug, 'haveEditedQuestion' => '1')));
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Frame:EditQuestion.html.twig', array(
                    'formEditQuestion' => $formEditQuestion->createView(),
                    'dotationMax' => $dotationMax
        ));
    } 

    public function slugify($str) {
        $search = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $replace = array('s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', 'a', 'a', 'e', 'E');
        $str = str_ireplace($search, $replace, strtolower(trim($str)));
        $str = preg_replace('/[^\w\d\-\ ]/', '', $str);
        $str = str_replace(' ', '-', $str);
        return preg_replace('/\-{2,}/', '-', $str);
    }

    public function addReponseAction($slug) {
        $newReponse = new \SmartUnity\AppBundle\Entity\Reponse();
        $formReponse = $this->createFormBuilder($newReponse)
               // ->add('description', 'textarea')
               /* ->add('description', 'ckeditor', array(
                    'config' => array(
                        'toolbar' => array(
                            array(
                                'name'  => 'basicstyles',
                                'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'),
                                ),
                            array(
                                 'name' => 'clipboardundo',
                                 'groups' => array('clipboard', 'undo'),
                                 'items' => array('Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ) ),
                            array(
                                'name' => 'paragraph',
                                'groups' => array('list', 'indent', 'align'),
                                'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock',)),
                            array(
                                 'name' => 'links',
                                  'items' => array('Link', 'Unlink','-', 'Image','Smiley','SpecialChar')
                                 ),
                            array(
                                 'name' => 'styles', 
                                 'items' => array('Font', 'FontSize'),
                                 ),
                            array(
                                'name' => 'colors',
                                'items' =>array('TextColor', 'BGColor')
                                ),
                         ),
                        'uiColor' => '#ffffff',
                        'removePlugins' => 'elementspath'
                        ),
                    'required' => true))*/
                ->add('valider', 'submit')
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {
            $formReponse->bind($this->getRequest());
            if ($formReponse->isValid()) {
                $user = $this->getUser();
                $newReponse->setMembre($user);

                $newReponse->setDate(new \DateTime(date("Y-m-d H:i:s"))); //date locale

                $newReponse->setDateValidation(NULL);
                $newReponse->setDateCertification(NULL);

                $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);
                $newReponse->SetQuestion($question);
                $newReponse->setSignaler(false);

                $membreQuestion = $question->getMembre();
                $prefRepmembre = $membreQuestion->getPrefRep();
                $mailMembreQuestion = $membreQuestion->getEmail();

                if ($prefRepmembre == true) {

                    //Envoi du mail
                    $sujetQuestion = $question->getSujet();
                    $sujetMail = "Réponse à votre question : " . $sujetQuestion . "sur smartunity.fr";
                    $expediteurMail = "";
                    $contenu = "";
                    $message = \Swift_Message::newInstance()
                            ->setContentType('text/html')
                            ->setSubject($sujetMail)
                            ->setFrom($expediteurMail)
                            ->setTo($mailMembreQuestion)
                            ->setBody($contenu);
                    $this->get('mailer')->send($message);
                }

                $em = $this->getDoctrine()->getManager();
                $em->persist($newReponse);
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug, 'haveAddedAnswer' => '1')));
            }
            throw new \Exception('Votre réponse n\'a pas pu être ajoutée');
        }
        return "";
    }
    
    public function editReponseAction($id, $slug) {
        $reponse = $this->getDoctrine()->getRepository('SmartUnityAppBundle:reponse')->find($id);

        $formEditReponse = $this->createFormBuilder($reponse)
                ->add('description', 'textarea', array(
                    'label' => false,
                ))
                ->add('valider', 'submit')
                ->getForm();   

        if ($this->getRequest()->getMethod() == 'POST') {


            $formEditReponse->bind($this->getRequest());

            if ($formEditReponse->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($reponse);
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug, 'haveEditedReponse' => '1')));
            }
        }
        return new \Exception('Votre réponse n\'a pas pu être ajoutée');
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
                    $questionSlug = $reponse[0]->getQuestion()->getSlug();

                    $reponse[0]->setDateValidation(new \DateTime(date("Y-m-d H:i:s")));
                    $repondant = $reponse[0]->getMembre();
                    $repondant->setReputation($repondant->getReputation() +50);       
                            
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($reponse[0]);
                    $em->persist($repondant);
                    $em->flush();

                    
                    $prefRepValideeMembre = $repondant->getPrefRepValidee();
                    $mailMembreReponse = $repondant->getEmail();

                    if ($prefRepValideeMembre == true) {

                        //Envoi du mail
                        $sujetQuestion = $reponse[0]->getQuestion()->getSujet();
                        $sujetMail = "Votre réponse à la question : " . $sujetQuestion . "sur smartunity.fr a été validée";
                        $expediteurMail = "";
                        $contenu = "";
                        $message = \Swift_Message::newInstance()
                                ->setContentType('text/html')
                                ->setSubject($sujetMail)
                                ->setFrom($expediteurMail)
                                ->setTo($mailMembreReponse)
                                ->setBody($contenu);
                        $this->get('mailer')->send($message);
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

        if (count($reponse) > 0) { //S'il y a des réponses
            if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedException();
            } else if ($reponse[0]->getDateValidation() == null) { //Si la question n'a pas été validée
                throw new \Exception('Cette réponse n\'est pas encore validée!');
            } else { //Si tout va bien
                $questionSlug = $reponse[0]->getQuestion()->getSlug();

                $reponse[0]->setDateCertification(new \DateTime(date("Y-m-d H:i:s")));
                
                $repondant = $reponse[0]->getMembre();
                $repondant->setReputation($repondant->getReputation() +50); 
                
                $em = $this->getDoctrine()->getManager();
                $em->persist($reponse[0]);
                $em->persist($repondant);
                
                $em->flush();

                $prefRepCertifieemembre = $repondant->getPrefRepCertifiee();
                $mailMembreReponse = $repondant->getEmail();

                if ($prefRepCertifieemembre == true) {

                    //Envoi du mail
                    $sujetQuestion = $reponse[0]->getQuestion()->getSujet();
                    $sujetMail = "Votre réponse à la question : " . $sujetQuestion . "sur smartunity.fr a été certifiée";
                    $expediteurMail = "";
                    $contenu = "";
                    $message = \Swift_Message::newInstance()
                            ->setContentType('text/html')
                            ->setSubject($sujetMail)
                            ->setFrom($expediteurMail)
                            ->setTo($mailMembreReponse)
                            ->setBody($contenu);
                    $this->get('mailer')->send($message);
                }

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $questionSlug)));
            }
        } else {
            throw new \Exception('La reponse passée en paramètre n\'éxiste pas!');
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


                $em = $this->getDoctrine()->getManager();
                $em->persist($newCommentaireQuestion);
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            }
        }
//        return $this->render('SmartUnityQuestionReponseBundle:Frame:AddCommentaire.html.twig',array(
//            'formCommentaire'=>$formCommentaire->createView(),
//            'type'=>'Question'));

        return "";
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

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            } else {

                return new Response("cassé");
            }
        }
        return "";
    }

    public function addSoutienQuestionAction($slug) {

        $user = $this->container->get('security.context')->getToken()->getUser();
        $formSoutien = $this->createFormBuilder()
                ->setAction($this->generateUrl('smart_unity_question_reponse_add_soutien_question', array('slug' => $slug)))
                ->add('soutien', 'integer', array('attr' => array('min' => 0, 'max' => ($user->getCagnotte()))))
                ->add('soutenir', 'submit')
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {
            $formSoutien->bind($this->getRequest());

            if ($formSoutien->isValid()) {
                $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);
                $question->setRemuneration($question->getRemuneration() + ($formSoutien->get('soutien')->getData()));
                $question->getSoutienMembres()->add($user);
                $user->setCagnotte($user->getCagnotte() - ($formSoutien->get('soutien')->getData()));

                //ajouté l'utilisateur à la liste de soutien

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $slug)));
            }
        }

        return new Response("cassé");
    }

    public function signalerQuestionAction($slug) {

        $formSignaler = $this->createFormBuilder()
                ->add('motif', 'textarea', array('label' => false))
                ->add('Signaler', 'submit')
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {
            $formSignaler->bind($this->getRequest());

            if ($formSignaler->isValid()) {
                $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);
                $question->setSignaler(true);

                $em = $this->getDoctrine()->getManager();
                $em->persist($question);
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_list_of_question'));
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Display:Signaler.html.twig', array(
                    'formSignaler' => $formSignaler->createView()));
    }

    public function signalerReponseAction($idReponse) {
        $formSignaler = $this->createFormBuilder()
                ->add('Signaler', 'submit')
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {
            $formSignaler->bind($this->getRequest());

            if ($formSignaler->isValid()) {
                $reponse = $this->getDoctrine()->getRepository('SmartUnityAppBundle:reponse')->find($idReponse);
                $reponse->setSignaler(true);
                $questionSlug = $reponse->getQuestion()->getSlug();

                $em = $this->getDoctrine()->getManager();
                $em->persist($reponse);
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $questionSlug)));
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Display:Signaler.html.twig', array(
                    'formSignaler' => $formSignaler->createView()));
    }

    public function signalerCommentaireQuestionAction($idCommentaireQuestion) {
        $formSignaler = $this->createFormBuilder()
                ->add('Signaler', 'submit')
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {
            $formSignaler->bind($this->getRequest());

            if ($formSignaler->isValid()) {
                $commentaireQuestion = $this->getDoctrine()->getRepository('SmartUnityAppBundle:commentaireQuestion')->find($idCommentaireQuestion);
                $commentaireQuestion->setSignaler(true);
                $questionSlug = $commentaireQuestion->getQuestion()->getSlug();

                $em = $this->getDoctrine()->getManager();
                $em->persist($commentaireQuestion);
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $questionSlug)));
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Display:Signaler.html.twig', array(
                    'formSignaler' => $formSignaler->createView()));
    }

    public function signalerCommentaireReponseAction($idCommentaireReponse) {
        $formSignaler = $this->createFormBuilder()
                ->add('Signaler', 'submit')
                ->getForm();

        if ($this->getRequest()->getMethod() == 'POST') {
            $formSignaler->bind($this->getRequest());

            if ($formSignaler->isValid()) {
                $commentaireReponse = $this->getDoctrine()->getRepository('SmartUnityAppBundle:commentaireReponse')->find($idCommentaireReponse);
                $commentaireReponse->setSignaler(true);
                $questionSlug = $commentaireReponse->getReponse()->getQuestion()->getSlug();

                $em = $this->getDoctrine()->getManager();
                $em->persist($commentaireReponse);
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse', array('slug' => $questionSlug)));
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Display:Signaler.html.twig', array(
                    'formSignaler' => $formSignaler->createView()));
    }

}
