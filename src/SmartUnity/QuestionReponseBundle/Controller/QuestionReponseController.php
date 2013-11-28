<?php

namespace SmartUnity\QuestionReponseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
            'error'         => $error,
            'page'=>$page,
            'type'=>$type,
            'nbPages'=>$nbPages,
            'listeQuestions'=>$listeQuestions, 
            'countListe' => count($listeQuestions),
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




        //Affichage de LA question avec liste réponses
        //Fonctionne de la même manière que displayListOfQuestionAction()
        $nbParPage = 5;

        $response = $this->forward('SmartUnityQuestionReponseBundle:Ajax:getReponses', array(
            'slug'  => $slug,
            'page' => $page,
            'nbParPage'=>$nbParPage,
            'tri'=>$tri
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
            'error'         => $error,
            'nbReponses'=>$nbReponses,
            'nbPages'=>$nbPages,
            'tri'=>$tri,
            'slug'=>$slug,
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
                            ->add('marque', 'entity', array(
                                'class'=> 'SmartUnityAppBundle:marque',
                                'property'=> 'nom'))
                            ->add('modele', 'entity', array(
                                'class'=> 'SmartUnityAppBundle:modele',
                                'property'=> 'nom'))
                            // ->addEventListener(
                            //     FormEvents::PRE_SET_DATA,
                            //         function(FormEvent $event) {
                            //             $form = $event->getForm();
                            //             // this would be your entity, i.e. SportMeetup
                            //             $data = $event->getData();

                            //             $modeleCat = $data->getMarque()->getAvailablePositions();

                            //             $form->add('position', 'entity', array('choices' => $positions));
                            //         }
                            //     )
                            ->add('os', 'entity', array(
                                'class'=> 'SmartUnityAppBundle:os',
                                'property'=> 'nom'))
                            ->add('typeQuestion', 'entity', array(
                                'class'=> 'SmartUnityAppBundle:typeQuestion',
                                'property'=> 'nom'))
                            ->add('remuneration','integer')
                            ->add('save', 'submit')
                            
                            ->getForm();

        if ($this->getRequest()->getMethod() == 'POST')
        {
            $formQuestion->bind($this->getRequest());

            if ($formQuestion->isValid()) {
                $user = $this->getUser();
                $newQuestion->setMembre($user);
                $newQuestion->setSignaler(false);

                $newQuestion->setDate(new \DateTime(date("Y-m-d H:i:s")));//date locale

                    // $str = $formQuestion->get('sujet')->getData();
                    // $search = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
                    // $replace = array('s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', 'a', 'a', 'e', 'E');
                    // $str = str_ireplace($search, $replace, strtolower(trim($str)));
                    // $str = preg_replace('/[^\w\d\-\ ]/', '', $str);
                    // $str = str_replace(' ', '-', $str);
                    // $slug= preg_replace('/\-{2,}', '-', $str);

                $newQuestion->setSlug($this->slugify($formQuestion->get('sujet')->getData()));

                // $newQuestion->addSoutien($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($newQuestion);
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse',array(
                    "slug"  => $newQuestion->getSlug()
                    )));
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Frame:AddQuestion.html.twig',array(
            'formQuestion'=>$formQuestion->createView()));
    }

    public function slugify($str) {
        $search = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $replace = array('s', 't', 's', 't', 's', 't', 's', 't', 'i', 'a', 'a', 'i', 'a', 'a', 'e', 'E');
        $str = str_ireplace($search, $replace, strtolower(trim($str)));
        $str = preg_replace('/[^\w\d\-\ ]/', '', $str);
        $str = str_replace(' ', '-', $str);
        return preg_replace('/\-{2,}/', '-', $str);
    }
    
    public function addReponseAction($slug)
    {
        $newReponse = new \SmartUnity\AppBundle\Entity\Reponse();
        $formQuestion = $this->createFormBuilder($newReponse)
                            ->add('description','textarea')
                            ->add('save', 'submit')
                            ->getForm();

        if ($this->getRequest()->getMethod() == 'POST')
        {
            $formQuestion->bind($this->getRequest());

            if ($formQuestion->isValid()) {
                $user = $this->getUser();
                $newReponse->setMembre($user);

                $newReponse->setDate(new \DateTime(date("Y-m-d H:i:s")));//date locale

                $newReponse->setDateValidation(NULL);
                $newReponse->setDateCertification(NULL);

                $question = $this->getDoctrine()->getRepository('SmartUnityAppBundle:question')->findOneBySlug($slug);
                $newReponse->SetQuestion($question);
                $newReponse->setSignaler(false);


                $em = $this->getDoctrine()->getManager();
                $em->persist($newReponse);
                $em->flush();

                return $this->redirect($this->generateUrl('smart_unity_question_reponse_display_reponse',array(
                    "slug"  => $slug
                    )));
            }
        }
        return $this->render('SmartUnityQuestionReponseBundle:Frame:AddQuestion.html.twig',array(
            'formQuestion'=>$formQuestion->createView()));
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