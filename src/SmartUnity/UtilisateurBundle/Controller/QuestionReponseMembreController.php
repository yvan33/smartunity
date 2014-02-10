<?php

namespace SmartUnity\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class QuestionReponseMembreController extends Controller {

    public function displayListOfQuestionAction($type, $page, Request $request, $id) {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        $nbParPage = 20;


        $request = $this->get('request');
        $route = $request->get('_route');


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

        if ($route == 'smart_unity_membre_questions'){ 
            
            $response = $this->forward('SmartUnityUtilisateurBundle:AjaxMembre:getQuestions', array(
                'type' => $type,
                'page' => $page,
                'nbParPage' => $nbParPage,
                'membreId' => $id,
                'route' => $route,
            ));

            //Suppression de l'en tête HTTP et décodage du JSON
            $cleanJSON = explode('[', $response, 2);
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
                throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityUtilisateurBundle:AjaxMembreController');


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

            $em = $this->getDoctrine()->getManager();
            $username = $em->getRepository('SmartUnityAppBundle:membre')->find($id)->getUsername();

            $template = sprintf('SmartUnityUtilisateurBundle:ProfilPublic:QuestionsMembre.html.twig');
            return $this->render($template, array(
                'error' => $error,
                'page' => $page,
                'type' => $type,
                'nbPages' => $nbPages,
                'listeQuestions' => $listeQuestions,
                'countListe' => count($listeQuestions),
                'nbParPage' => $nbParPage,
                'pagination' => $pagination,
                'membreId' => $id,
                'username' => $username,
                'route' => $route,
            ));
        }

        if ($route == 'smart_unity_membre_reponses'){  
            $response = $this->forward('SmartUnityUtilisateurBundle:AjaxMembre:getQuestionsAnswered', array(
                'type' => $type,
                'page' => $page,
                'nbParPage' => $nbParPage,
                'membreId' => $id,
                'route' => $route,
            ));
            // p($response);
            //Suppression de l'en tête HTTP et décodage du JSON
            $cleanJSON = explode('[', $response, 2);
            // p($cleanJSON);
            $listeQuestions = json_decode('[' . $cleanJSON[1]);
            // p($listeQuestions);
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
                throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityUtilisateurBundle:AjaxMembreController');


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

            $em = $this->getDoctrine()->getManager();
            $username = $em->getRepository('SmartUnityAppBundle:membre')->find($id)->getUsername();
 
            $template = sprintf('SmartUnityUtilisateurBundle:ProfilPublic:ReponsesMembre.html.twig');
            return $this->render($template, array(
                'error' => $error,
                'page' => $page,
                'type' => $type,
                'nbPages' => $nbPages,
                'listeQuestions' => $listeQuestions,
                'countListe' => count($listeQuestions),
                'nbParPage' => $nbParPage,
                'pagination' => $pagination,
                'membreId' => $id,
                'username' => $username,
                'route' => $route,
            ));
        }
    }

    // public function displayReponseAction($slug, $page, $tri, Request $request) {

    //     /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
    //     $session = $request->getSession();

    //     // get the error if any (works with forward and redirect -- see below)
    //     if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
    //         $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
    //     } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
    //         $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
    //         $session->remove(SecurityContext::AUTHENTICATION_ERROR);
    //     } else {
    //         $error = '';
    //     }

    //     if ($error) {
    //         // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
    //         $error = $error->getMessage();
    //     }




    //     //Affichage de LA question avec liste réponses
    //     //Fonctionne de la même manière que displayListOfQuestionAction()
    //     $nbParPage = 5;

    //     $response = $this->forward('SmartUnityQuestionReponseBundle:AjaxMembre:getReponses', array(
    //         'slug' => $slug,
    //         'page' => $page,
    //         'nbParPage' => $nbParPage,
    //         'tri' => $tri
    //     ));

    //     if (strpos($response, '404 Not Found') !== false)
    //         throw new NotFoundHttpException("Cette question n'a pas encore été posée!");


    //     //Suppression de l'en tête HTTP et décodage du JSON
    //     $cleanJSON = array();
    //     $listeReponses = array();
    //     $rebuildJSON = '';

    //     $cleanJSON = explode('[', $response, 2);
    //     $listeReponses = json_decode('[' . $cleanJSON[1]);

    //     //Le tableau JSON contient une ligne d'entête qui contient les infos à propos de
    //     //la requête pour vérifier son authenticité... 
    //     //On récupère des infos utiles pour la pagination..
    //     $nbPages = $listeReponses[0]->nbPages;
    //     $nbReponses = $listeReponses[0]->nbReponses;

    //     if ($page > $nbPages)
    //         $page = 1;

    //     //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
    //     if ($listeReponses[0]->slug == $slug && $listeReponses[0]->nbParPage == $nbParPage && $listeReponses[0]->page == $page && $listeReponses[0]->tri == $tri)
    //         unset($listeReponses[0]);
    //     else
    //         throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityUtilisateurBundle:AjaxMembreController');


    //     //Génération de la pagination en statique (si pas de JS)
    //     $pagination = array();
    //     if ($page != 1) {
    //         array_push($pagination, array('<<', '1', '-4'));
    //         array_push($pagination, array('<', $page - 1, '-4'));
    //     }
    //     for ($i = -2; $i < 3; $i++) {
    //         if (($page + $i) >= 1 && ($page + $i) <= $nbPages)
    //             array_push($pagination, array($page + $i, $page + $i, $i));
    //     }
    //     if ($page < $nbPages) {
    //         array_push($pagination, array('>', $page + 1, '3'));
    //         array_push($pagination, array('>>', $nbPages, '4'));
    //     }


    //     $questionRepository = $this->getDoctrine()
    //             ->getManager()
    //             ->getRepository('SmartUnityAppBundle:question');
    //     $reponseRepository = $this->getDoctrine()
    //             ->getManager()
    //             ->getRepository('SmartUnityAppBundle:reponse');

    //     $question = $questionRepository->findOneBySlug($slug);

    //     $isValidated = $questionRepository->isQuestionValid($question->getId());
    //     $isCertif = $questionRepository->isQuestionCertif($question->getId());

    //     $membre = $question->getMembre();

    //     $smartReponses = $reponseRepository->getNbCertifForUser($membre->getId());
    //     $nb_questions_membre = $questionRepository->getNbQuestionsForUser($membre->getId());


    //     $template = sprintf('SmartUnityQuestionReponseBundle:Display:Reponse.html.twig');
    //     return $this->render($template, array(
    //                 'error' => $error,
    //                 'nbReponses' => $nbReponses,
    //                 'nbPages' => $nbPages,
    //                 'tri' => $tri,
    //                 'page' => $page,
    //                 'slug' => $slug,
    //                 'is_certif' => $isCertif,
    //                 'is_validated' => $isValidated,
    //                 'listeReponses' => $listeReponses,
    //                 'pagination' => $pagination,
    //                 'nbParPage' => $nbParPage,
    //                 'question' => $question,
    //                 'smart_reponses' => (int) $smartReponses,
    //                 'nb_questions_membre' => (int) $nb_questions_membre
    //     ));
    // }

    // public function slugify($str) {
    //     $search = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
    //     $replace = array('s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', 'a', 'a', 'e', 'E');
    //     $str = str_ireplace($search, $replace, strtolower(trim($str)));
    //     $str = preg_replace('/[^\w\d\-\ ]/', '', $str);
    //     $str = str_replace(' ', '-', $str);
    //     return preg_replace('/\-{2,}/', '-', $str);
    // }

    // public function addReponseAction($slug) {
    //     $newReponse = new \SmartUnity\AppBundle\Entity\Reponse();
    //     $formReponse = $this->createFormBuilder($newReponse)
    //             ->add('description', 'textarea')
    //             ->add('save', 'submit')
    //             ->getForm();

    //     if ($this->getRequest()->getMethod() == 'POST') {
    //         $formReponse->bind($this->getRequest());

    //         if ($formReponse->isValid()) {
    //             $user = $this->getUser();
    //             $newReponse->setMembre($user);

    //             $newReponse->setDate(new \DateTime(date("Y-m-d H:i:s"))); //date locale

    //             $newReponse->setDateValidation(NULL);
    //             $newReponse->setDateCertification(NULL);

    //             $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);
    //             $newReponse->SetQuestion($question);
    //             $newReponse->setSignaler(false);


    //             $em = $this->getDoctrine()->getManager();
    //             $em->persist($newReponse);
    //             $em->flush();

    //             return new Response('Votre réponse a bien été ajoutée');
    //         }
    //     }
    //     return $this->render('SmartUnityQuestionReponseBundle:Frame:AddReponse.html.twig', array(
    //                 'formReponse' => $formReponse->createView()));
    // }

    // public function validationReponseAction() {
    //     // return $this->;//pointer vers l'affichage de la question
    //     return new Response('validation QuestionReponses');
    // }

    // public function certificationReponseAction() {
    //     // return $this->;//pointer vers l'affichage de la question
    //     return new Response('certification QuestionReponses');
    // }

    // public function addCommentaireQuestionAction($slug) {
    //     $newCommentaireQuestion = new \SmartUnity\AppBundle\Entity\CommentaireQuestion();
    //     $formCommentaire = $this->createFormBuilder($newCommentaireQuestion)
    //             ->add('description', 'textarea')
    //             ->add('save', 'submit')
    //             ->getForm();

    //     if ($this->getRequest()->getMethod() == 'POST') {
    //         $formCommentaire->bind($this->getRequest());

    //         if ($formCommentaire->isValid()) {
    //             $user = $this->getUser();
    //             $newCommentaireQuestion->setMembre($user);

    //             $newCommentaireQuestion->setDate(new \DateTime(date("Y-m-d H:i:s"))); //date locale
    //             $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);
    //             $newCommentaireQuestion->SetQuestion($question);
    //             $newCommentaireQuestion->setSignaler(false);


    //             $em = $this->getDoctrine()->getManager();
    //             $em->persist($newCommentaireQuestion);
    //             $em->flush();

    //             return new Response('Votre commentaire a bien été ajouté');
    //         }
    //     }
    //     return $this->render('SmartUnityQuestionReponseBundle:Frame:AddCommentaire.html.twig', array(
    //                 'formCommentaire' => $formCommentaire->createView(),
    //                 'type' => 'Question'));
    // }

    // public function addCommentaireReponseAction($idReponse) {

    //     $newCommentaireReponse = new \SmartUnity\AppBundle\Entity\CommentaireReponse();
    //     $formCommentaire = $this->createFormBuilder($newCommentaireReponse)
    //             ->add('description', 'textarea')
    //             ->add('save', 'submit')
    //             ->getForm();

    //     if ($this->getRequest()->getMethod() == 'POST') {
    //         $formCommentaire->bind($this->getRequest());

    //         if ($formCommentaire->isValid()) {
    //             $user = $this->getUser();
    //             $newCommentaireReponse->setMembre($user);

    //             $newCommentaireReponse->setDate(new \DateTime(date("Y-m-d H:i:s"))); //date locale
    //             $reponse = $this->getDoctrine()->getRepository('SmartUnityAppBundle:reponse')->find($idReponse);
    //             $newCommentaireReponse->setReponse($reponse);

    //             $newCommentaireReponse->setSignaler(false);


    //             $em = $this->getDoctrine()->getManager();
    //             $em->persist($newCommentaireReponse);
    //             $em->flush();

    //             return new Response('Votre commentaire a bien été ajouté');
    //         }
    //     }
    //     return $this->render('SmartUnityQuestionReponseBundle:Frame:AddCommentaire.html.twig', array(
    //                 'formCommentaire' => $formCommentaire->createView(),
    //                 'type' => 'Reponse'));
    // }

    // public function addSoutienQuestionAction($slug) {
    //     $user = $this->getUser();
    //     $formSoutien = $this->createFormBuilder()
    //             ->add('soutien', 'integer', array('attr' => array('min' => 0, 'max' => ($user->getCagnotte()))))
    //             ->add('save', 'submit')
    //             ->getForm();

    //     if ($this->getRequest()->getMethod() == 'POST') {
    //         $formSoutien->bind($this->getRequest());

    //         if ($formSoutien->isValid()) {
    //             $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);
    //             $question->setRemuneration($question->getRemuneration() + ($formSoutien->get('soutien')->getData()));
    //             $user->setCagnotte($user->getCagnotte() - ($formSoutien->get('soutien')->getData()));

    //             //ajouté l'utilisateur à la liste de soutien

    //             $em = $this->getDoctrine()->getManager();
    //             $em->flush();

    //             return new Response('Votre soutien a bien été ajouté');
    //         }
    //     }
    //     return $this->render('SmartUnityQuestionReponseBundle:Frame:AddSoutien.html.twig', array(
    //                 'formSoutien' => $formSoutien->createView()));
    // }

    // public function signalerQuestionAction($slug) {

    //     $formSignaler = $this->createFormBuilder()
    //             ->add('Signaler', 'submit')
    //             ->getForm();

    //     if ($this->getRequest()->getMethod() == 'POST') {
    //         $formSignaler->bind($this->getRequest());

    //         if ($formSignaler->isValid()) {
    //             $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);
    //             $question->setSignaler(true);

    //             $em = $this->getDoctrine()->getManager();
    //             $em->persist($question);
    //             $em->flush();

    //             return new Response('Le signalement a ben été envoyé');
    //         }
    //     }
    //     return $this->render('SmartUnityQuestionReponseBundle:Frame:Signaler.html.twig', array(
    //                 'formSignaler' => $formSignaler->createView()));
    // }

    // public function signalerReponseAction($idReponse) {
    //     $formSignaler = $this->createFormBuilder()
    //             ->add('Signaler', 'submit')
    //             ->getForm();

    //     if ($this->getRequest()->getMethod() == 'POST') {
    //         $formSignaler->bind($this->getRequest());

    //         if ($formSignaler->isValid()) {
    //             $reponse = $this->getDoctrine()->getRepository('SmartUnityAppBundle:reponse')->find($idReponse);
    //             $reponse->setSignaler(true);

    //             $em = $this->getDoctrine()->getManager();
    //             $em->persist($reponse);
    //             $em->flush();

    //             return new Response('Le signalement a ben été envoyé');
    //         }
    //     }
    //     return $this->render('SmartUnityQuestionReponseBundle:Frame:Signaler.html.twig', array(
    //                 'formSignaler' => $formSignaler->createView()));
    // }

    // public function signalerCommentaireQuestionAction($idCommentaireQuestion) {
    //     $formSignaler = $this->createFormBuilder()
    //             ->add('Signaler', 'submit')
    //             ->getForm();

    //     if ($this->getRequest()->getMethod() == 'POST') {
    //         $formSignaler->bind($this->getRequest());

    //         if ($formSignaler->isValid()) {
    //             $commentaireQuestion = $this->getDoctrine()->getRepository('SmartUnityAppBundle:commentaireQuestion')->find($idCommentaireQuestion);
    //             $commentaireQuestion->setSignaler(true);

    //             $em = $this->getDoctrine()->getManager();
    //             $em->persist($commentaireQuestion);
    //             $em->flush();

    //             return new Response('Le signalement a ben été envoyé');
    //         }
    //     }
    //     return $this->render('SmartUnityQuestionReponseBundle:Frame:Signaler.html.twig', array(
    //                 'formSignaler' => $formSignaler->createView()));
    // }

    // public function signalerCommentaireReponseAction($idCommentaireReponse) {
    //     $formSignaler = $this->createFormBuilder()
    //             ->add('Signaler', 'submit')
    //             ->getForm();

    //     if ($this->getRequest()->getMethod() == 'POST') {
    //         $formSignaler->bind($this->getRequest());

    //         if ($formSignaler->isValid()) {
    //             $commentaireReponse = $this->getDoctrine()->getRepository('SmartUnityAppBundle:commentaireReponse')->find($idCommentaireReponse);
    //             $commentaireReponse->setSignaler(true);

    //             $em = $this->getDoctrine()->getManager();
    //             $em->persist($commentaireReponse);
    //             $em->flush();

    //             return new Response('Le signalement a ben été envoyé');
    //         }
    //     }
    //     return $this->render('SmartUnityQuestionReponseBundle:Frame:Signaler.html.twig', array(
    //                 'formSignaler' => $formSignaler->createView()));
    // }

}
