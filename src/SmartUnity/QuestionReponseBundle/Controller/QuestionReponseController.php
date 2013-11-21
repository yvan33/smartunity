<?php

namespace SmartUnity\QuestionReponseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;

class QuestionReponseController extends Controller
{
    public function indexAction()
    {
        // afficher 3 onglets, avec quelques questions... de la page accueil

        //Redirection vers la liste des questions (pour l'instant)
        return $this->redirect( $this->generateUrl('smart_unity_question_reponse_list_of_question') );
    }

    public function displayListOfQuestionAction($type, $page, Request $request)
    {


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
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);


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


        $nbParPage = 5;

        $response = $this->forward('SmartUnityQuestionReponseBundle:Ajax:getQuestions', array(
            'type'  => $type,
            'page' => $page,
            'nbParPage'=>$nbParPage
        ));

        //Suppression de l'en tête HTTP et décodage du JSON
        $cleanJSON = explode('[', $response, 2);
        $listeQuestions = json_decode('[' . $cleanJSON[1]);


        //Le tableau JSON contient une ligne d'entête qui contient les infos à propos de
        //la requête pour vérifier son authenticité... 

        //On récupère des infos utiles pour la pagination..
        $nbPages = $listeQuestions[0]->nbPages;

        if ($page > $nbPages) $page = 1;
        
        //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
        if($listeQuestions[0]->type==$type && $listeQuestions[0]->nbParPage==$nbParPage && $listeQuestions[0]->page==$page)
            unset($listeQuestions[0]);
        else
            throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityQuestionReponseBundle:AjaxController');


        //Génération de la pagination en statique (si pas de JS)
        $pagination = array();
        if($page!=1){
            array_push($pagination, array('<<', '1', '-4'));
            array_push($pagination, array('<', $page - 1, '-4'));
        }
        for ($i=-2; $i<3; $i++){
            if( ($page + $i) >= 1  &&  ($page + $i) <= $nbPages )
                array_push($pagination, array($page + $i, $page + $i, $i));
        }
        if($page < $nbPages){
            array_push($pagination, array('>', $page + 1, '3'));
            array_push($pagination, array('>>', $nbPages, '4'));
        }

        

        $template = sprintf('SmartUnityQuestionReponseBundle:Display:ListeQuestion.html.twig');
        return $this->render($template, array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'page'=>$page,
            'type'=>$type,
            'nbPages'=>$nbPages,
            'listeQuestions'=>$listeQuestions, 
            'nbParPage'=>$nbParPage,
            'pagination'=>$pagination
        ));
        

    }



    public function searchQuestionAction()
    {

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


    
    public function displayReponseAction($slug, $page, $tri, Request $request)
    {   

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
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);



        //Affichage de LA question avec liste réponses
        //Fonctionne de la même manière que displayListOfQuestionAction()
        $nbParPage = 5;

        $response = $this->forward('SmartUnityQuestionReponseBundle:Ajax:getReponses', array(
            'slug'  => $slug,
            'page' => $page,
            'nbParPage'=>$nbParPage,
            'tri'=>$tri
        ));

        
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

        if ($page > $nbPages) $page = 1;
        
        //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
        if($listeReponses[0]->slug==$slug && $listeReponses[0]->nbParPage==$nbParPage && $listeReponses[0]->page==$page && $listeReponses[0]->tri == $tri)
            unset($listeReponses[0]);
        else
            throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityQuestionReponseBundle:AjaxController');


        //Génération de la pagination en statique (si pas de JS)
        $pagination = array();
        if($page!=1){
            array_push($pagination, array('<<', '1', '-4'));
            array_push($pagination, array('<', $page - 1, '-4'));
        }
        for ($i=-2; $i<3; $i++){
            if( ($page + $i) >= 1  &&  ($page + $i) <= $nbPages )
                array_push($pagination, array($page + $i, $page + $i, $i));
        }
        if($page < $nbPages){
            array_push($pagination, array('>', $page + 1, '3'));
            array_push($pagination, array('>>', $nbPages, '4'));
        }



        $questionRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:question');
        $reponseRepository = $this->getDoctrine()
                            ->getManager()
                            ->getRepository('SmartUnityAppBundle:reponse');

        $question = $questionRepository->findOneBySlug($slug);

        $membre = $question->getMembre();

        $smartReponses = $reponseRepository->getNbCertifForUser($membre->getId());
        $nb_questions_membre = $questionRepository->getNbQuestionsForUser($membre->getId());


        $template = sprintf('SmartUnityQuestionReponseBundle:Display:Reponse.html.twig');
        return $this->render($template, array(
            'last_username' => $lastUsername,
            'error'         => $error,
            'nbReponses'=>$nbReponses,
            'nbPages'=>$nbPages,
            'tri'=>$tri,
            'listeReponses'=>$listeReponses,
            'pagination'=>$pagination,
            'nbParPage'=>$nbParPage,
            'question'=>$question,
            'membre_nom'=>$membre->getNom(),
            'membre_reputation'=>$membre->getReputation(),
            'date'=>$question->getDate()->format('d-m-Y à H:i'),
            'nb_soutiens'=>$question->getSoutienMembres()->count(),
            'remuneration'=>$question->getRemuneration(),
            'points_membre'=> (int) $membre->getCagnotte(),
            'smart_reponses'=> (int) $smartReponses,
            'nb_questions_membre'=> (int) $nb_questions_membre
        ));
    }

    public function addQuestionAction()
    {
        $newQuestion = new \SmartUnity\AppBundle\Entity\Question();
        

        $formQuestion = $this->createFormBuilder($newQuestion)
                            ->add('sujet','text')
                            ->add('description','textarea')
                            ->add('modele', 'entity', array(
                                'class'=> 'SmartUnityAppBundle:modele',
                                'property'=> 'name'))
                            ->add('os', 'entity', array(
                                'class'=> 'SmartUnityAppBundle:os',
                                'property'=> 'name'))
                            ->add('remuneration','integer')
                            ->getForm();

        if ($this->getRequest->getMethod() == 'POST')
        {
            $formQuestion->bind($this->getRequest());//à tester sans

            if ($formQuestion->isValid()) {
                $user = $this->container->get('security.context')->getToken()->getUser();
                $newQuestion->setMembre(); //Récupèrer l'utilisateur
                $newQuestion->setDate(date("Y-m-d H:i:s"));//date locale
                $newQuestion->setSlug("");
                $newQuestion->addSoutien($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($newQuestion);
                $em->flush();
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Frame:AddQuestion.html.twig',array(
            'formQuestion'=>$formQuestion));
    }

    function slugify($str) {
        $search = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $replace = array('s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', 'a', 'a', 'e', 'E');
        $str = str_ireplace($search, $replace, strtolower(trim($str)));
        $str = preg_replace('/[^\w\d\-\ ]/', '', $str);
        $str = str_replace(' ', '-', $str);
        return preg_replace('/\-{2,}', '-', $str);
    }
    
    public function addReponseAction()
    {
        //lancé depuis une iFrame fancybox
        // --> créer un dossier iFrame dans les vues du QuestionReponseBundle
    	
        //return $this->render('SmartUnityQuestionReponseBundle:Question:Add.html.twig');
        return new Response('add reponse QuestionReponses');
    }

    public function validationReponseAction()
    {
    	// return $this->;//pointer vers l'affichage de la question
        return new Response('validation QuestionReponses');
    }

    public function certificationReponseAction()
    {
		// return $this->;//pointer vers l'affichage de la question
        return new Response('certification QuestionReponses');
    }

    public function addCommentaireQuestionAction()
    {
        //IDEM add réponse
        return new Response('add com question QuestionReponses');
    }

    public function addCommentaireReponseAction()
    {
        //IDEM add réponse
        return new Response('add com reponse QuestionReponses');
    }
}