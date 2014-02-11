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

        $nbParPage = 1;


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

            //On récupère les solutions
            $response = $this->forward('SmartUnityUtilisateurBundle:AjaxMembre:getQuestions', array(
                'type' => 'reponses',
                'page' => $page,
                'nbParPage' => $nbParPage,
                'membreId' => $id,
                'route' => $route,
            ));
            $pageSol=$page;
            //Suppression de l'en tête HTTP et décodage du JSON
            $cleanJSON = explode('[', $response, 2);
            $listeQuestions = json_decode('[' . $cleanJSON[1]);

            //Le tableau JSON contient une ligne d'entête qui contient les infos à propos de
            //la requête pour vérifier son authenticité... 
            //On récupère des infos utiles pour la pagination..
            $nbPagesSol = $listeQuestions[0]->nbPages;

            if ($pageSol > $nbPagesSol)
                $pageSol = 1;

            //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
            if ($listeQuestions[0]->type == 'reponses' && $listeQuestions[0]->nbParPage == $nbParPage && $listeQuestions[0]->page == $pageSol)
                {unset($listeQuestions[0]);}
            else
                {throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityUtilisateurBundle:AjaxMembreController solutions');}
            //On récupère les dernières questions
            $response = $this->forward('SmartUnityUtilisateurBundle:AjaxMembre:getQuestions', array(
                'type' => 'last',
                'page' => $page,
                'nbParPage' => $nbParPage,
                'membreId' => $id,
                'route' => $route,
            ));
            $pageslast=$page;
            //Suppression de l'en tête HTTP et décodage du JSON
            $cleanJSON = explode('[', $response, 2);
            $listelastQuestions = json_decode('[' . $cleanJSON[1]);

            //Le tableau JSON contient une ligne d'entête qui contient les infos à propos de
            //la requête pour vérifier son authenticité... 
            //On récupère des infos utiles pour la pagination..
            $nbPagesLast = $listelastQuestions[0]->nbPages;

            if ($pageslast > $nbPagesLast)
                {$pageslast = 1;}

            //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
            if ($listelastQuestions[0]->type == 'last' && $listelastQuestions[0]->nbParPage == $nbParPage && $listelastQuestions[0]->page == $pageslast)
                {unset($listelastQuestions[0]);}
            else
                {throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityUtilisateurBundle:AjaxMembreController Last');}

            //On récupère les questions onFire
            $response = $this->forward('SmartUnityUtilisateurBundle:AjaxMembre:getQuestions', array(
                'type' => 'onFire',
                'page' => $page,
                'nbParPage' => $nbParPage,
                'membreId' => $id,
                'route' => $route,
            ));


            //Suppression de l'en tête HTTP et décodage du JSON
            $cleanJSON = explode('[', $response, 2);
            $listeQuestionsOnFire = json_decode('[' . $cleanJSON[1]);

            //Le tableau JSON contient une ligne d'entête qui contient les infos à propos de
            //la requête pour vérifier son authenticité... 
            //On récupère des infos utiles pour la pagination..
            $nbPagesOnFire = $listeQuestionsOnFire[0]->nbPages;
            $pagesOnFire=$page;

            if ($pagesOnFire > $nbPagesOnFire)
                {$pagesOnFire = 1;}

            //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
            if ($listeQuestionsOnFire[0]->type == 'onFire' && $listeQuestionsOnFire[0]->nbParPage == $nbParPage && $listeQuestionsOnFire[0]->page == $pagesOnFire)
                {unset($listeQuestionsOnFire[0]);}
            else
                {throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityUtilisateurBundle:AjaxMembreController On fire');}


            //Génération de la pagination en statique (si pas de JS)
            $paginationOnFire = array();
            if ($pagesOnFire != 1) {
                array_push($paginationOnFire, array('<<', '1', '-4'));
                array_push($paginationOnFire, array('<', $pagesOnFire - 1, '-4'));
            }
            for ($i = -2; $i < 3; $i++) {
                if (($pagesOnFire + $i) >= 1 && ($pagesOnFire + $i) <= $nbPagesOnFire)
                    array_push($paginationOnFire, array($pagesOnFire + $i, $pagesOnFire + $i, $i));
            }
            if ($pagesOnFire < $nbPagesOnFire) {
                array_push($paginationOnFire, array('>', $pagesOnFire + 1, '3'));
                array_push($paginationOnFire, array('>>', $nbPagesOnFire, '4'));
            }

            $paginationlast = array();
            if ($pageslast != 1) {
                array_push($paginationlast, array('<<', '1', '-4'));
                array_push($paginationlast, array('<', $pageslast - 1, '-4'));
            }
            for ($i = -2; $i < 3; $i++) {
                if (($pageslast + $i) >= 1 && ($pageslast + $i) <= $nbPagesLast)
                    array_push($paginationlast, array($pageslast + $i, $pageslast + $i, $i));
            }
            if ($pageslast < $nbPagesLast) {
                array_push($paginationlast, array('>', $pageslast + 1, '3'));
                array_push($paginationlast, array('>>', $nbPagesLast, '4'));
            }

            $paginationSol = array();
            if ($pageSol != 1) {
                array_push($paginationSol, array('<<', '1', '-4'));
                array_push($paginationSol, array('<', $pageSol - 1, '-4'));
            }
            for ($i = -2; $i < 3; $i++) {
                if (($pageSol + $i) >= 1 && ($pageSol + $i) <= $nbPagesSol)
                    array_push($paginationSol, array($pageSol + $i, $pageSol + $i, $i));
            }
            if ($pageSol < $nbPagesSol) {
                array_push($paginationSol, array('>', $pageSol + 1, '3'));
                array_push($paginationSol, array('>>', $nbPagesSol, '4'));
            }

            $em = $this->getDoctrine()->getManager();
            $username = $em->getRepository('SmartUnityAppBundle:membre')->find($id)->getUsername();

            $template = sprintf('SmartUnityUtilisateurBundle:ProfilPublic:QuestionsMembre.html.twig');
            return $this->render($template, array(
                'error' => $error,
                'page' => $page,
                'type' => $type,
                'nbPagesSol' => $nbPagesSol,
                'nbPagesLast' => $nbPagesLast,
                'nbPagesOnFire' => $nbPagesOnFire,
                'listeQuestions' => $listeQuestions,
                'listeQuestionsOnFire'=>$listeQuestionsOnFire,
                'listelastQuestions'=>$listelastQuestions,
                'countListe' => count($listeQuestions),
                'countQuestionsOnFire'=>count($listeQuestionsOnFire),
                'countlastQuestions'=>count($listelastQuestions),
                'nbParPage' => $nbParPage,
                'paginationSol' => $paginationSol,
                'paginationOnFire='=>$paginationOnFire,
                'paginationlast'=>$paginationlast,
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
            //Suppression de l'en tête HTTP et décodage du JSON
            $cleanJSON = explode('[', $response, 2);

            $listeQuestions = json_decode('[' . $cleanJSON[1]);

            //Le tableau JSON contient une ligne d'entête qui contient les infos à propos de
            //la requête pour vérifier son authenticité... 
            //On récupère des infos utiles pour la pagination..
            $nbPages = $listeQuestions[0]->nbPages;

            if ($page > $nbPages)
                {$page = 1;}

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


}
