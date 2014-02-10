<?php

namespace SmartUnity\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use SmartUnity\AppBundle\Form\Type\ContactType;

class AccueilController extends Controller {

    public function indexAction(Request $request) {

//Le code de la function indexAction Permet de récupérer la session et de préremplir le formulaire de login.    

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


        //////------------ CONTENU LISTE
        //Cf SmartUnityQuestionReponseBundle:QuestionReponse:displayListOfQuestionAction

        $nbParPage = 5;



        //Liste onFire
        /////////////
        $response = $this->forward('SmartUnityQuestionReponseBundle:Ajax:getQuestions', array(
            'type' => 'onFire',
            'page' => 1,
            'nbParPage' => $nbParPage
        ));

        //Suppression de l'en tête HTTP et décodage du JSON
        $cleanJSON = explode('[', $response, 2);
        $listeQuestionsOnFire = json_decode('[' . $cleanJSON[1]);

        //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
        if ($listeQuestionsOnFire[0]->type == 'onFire' && $listeQuestionsOnFire[0]->nbParPage == $nbParPage && $listeQuestionsOnFire[0]->page == 1)
            unset($listeQuestionsOnFire[0]);
        else
            throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityQuestionReponseBundle:AjaxController');




        //Liste Last
        ////////////
        $response = $this->forward('SmartUnityQuestionReponseBundle:Ajax:getQuestions', array(
            'type' => 'last',
            'page' => 1,
            'nbParPage' => $nbParPage
        ));
        //Suppression de l'en tête HTTP et décodage du JSON
        $cleanJSON = explode('[', $response, 2);
        $listeLastQuestions = json_decode('[' . $cleanJSON[1]);
  
        //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
        if ($listeLastQuestions[0]->type == 'last' && $listeLastQuestions[0]->nbParPage == $nbParPage && $listeLastQuestions[0]->page == 1)
            unset($listeLastQuestions[0]);
        else
            throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityQuestionReponseBundle:AjaxController');



        //Liste Solutions
        ////////////
        $response = $this->forward('SmartUnityQuestionReponseBundle:Ajax:getQuestions', array(
            'type' => 'reponses',
            'page' => 1,
            'nbParPage' => $nbParPage
        ));
        p($response);
        //Suppression de l'en tête HTTP et décodage du JSON
        $cleanJSON = explode('[', $response, 2);
        $listeSolvedQuestions = json_decode('[' . $cleanJSON[1]);

        //...Et on la supprime, une fois qu'on a checké que les valeurs correspondaient!
        if ($listeSolvedQuestions[0]->type == 'reponses' && $listeSolvedQuestions[0]->nbParPage == $nbParPage && $listeSolvedQuestions[0]->page == 1)
            unset($listeSolvedQuestions[0]);
        else
            throw new \Exception('Erreur sur l\'appel à la BDD via SmartUnityQuestionReponseBundle:AjaxController');

        
//Création du formulaire pour les filtres de recherche de question
        
        $QuestionRecherche = new \SmartUnity\AppBundle\Entity\Question();
        $formQuestion = $this->createForm('smartunity_filtres_repondre', $QuestionRecherche, array(
            'action' => $this->generateUrl('smart_unity_question_reponse_repondre_questions')));

        return $this->renderLogin(array(
                    'error' => $error,
                    'listeQuestionsOnFire' => $listeQuestionsOnFire,
                    'countOnFire' => count($listeQuestionsOnFire),
                    'listeLastQuestions' => $listeLastQuestions,
                    'countLast' => count($listeLastQuestions),
                    'listeSolvedQuestions' => $listeSolvedQuestions,
                    'countSolved' => count($listeSolvedQuestions),
                    'formQuestion' => $formQuestion->createView(),
        ));
    }

    /**
     * Renders the login template with the given parameters. Overwrite this function in
     * an extended controller to provide additional data for the login template.
     *
     * @param array $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderLogin(array $data) {
        $template = sprintf('SmartUnityAppBundle::Accueil.html.twig');

        return $this->container->get('templating')->renderResponse($template, $data);
    }
    

    public function loginCheckAction() {
        // Call intercepted by the Security Component of Symfony
        return $this->redirect($this->generateUrl('smart_unity_app_homepage'));
    }

    public function logoutAction(Request $request) {
        return $this->redirect($request->headers->get('referer'));
    }


    public function affichageDescriptionAction() {

        $template = 'SmartUnityAppBundle::Description.html.twig';

        return $this->render($template);
        
    }

    public function affichageAproposAction() {

        $template = 'SmartUnityAppBundle::Apropos.html.twig';

        return $this->render($template);
        
    }    

    public function affichageConditionsAction() {

        $template = 'SmartUnityAppBundle::Conditions.html.twig';

        return $this->render($template);
        
    }   

   public function formulaireContactAction(Request $request) {

        $template = 'SmartUnityAppBundle::Contact.html.twig';


        $form = $this->createFormBuilder()
        ->add('email', 'email', array(
            'required' => true))
        ->add('sujet', 'text', array(
            'required' => true))
        ->add('message', 'textarea', array(
            'required' => true))    
        ->getForm();
        $confirmation="";
        if ($request->getMethod() == 'POST') {  
            $form->handleRequest($request);
            $sujet= $form->get('sujet')->getData();
            $sujetMail = "Message formulaire de contact: " . $sujet;
            $expediteurMail = $form->get('email')->getData();
            $destinataireMail="contact@smartunity.fr";
            $contenu = $form->get('message')->getData();
            $message = \Swift_Message::newInstance()
                        ->setContentType('text/html')
                        ->setSubject($sujetMail)
                        ->setFrom($expediteurMail)
                        ->setTo($destinataireMail)
                        ->setBody($contenu);
            $this->get('mailer')->send($message);

            $confirmation="Votre message a bien été envoyé à l'équipe Smart'Unity";

            $sujet= $form->get('sujet')->getData();
            $sujetMail = "Confirmation contact Smart'Unity";
            $expediteurMail = "contact@smartunity.fr";
            $destinataireMail =$form->get('email')->getData();
            $contenu = "Bonjour, <br/> votre message : " . $sujet ." nous a bien été envoyé. <br/> Nous vous remercions pour votre demande. <br/> <br/> L'équipe Smart'Unity";
            $message = \Swift_Message::newInstance()
                        ->setContentType('text/html')
                        ->setSubject($sujetMail)
                        ->setFrom($expediteurMail)
                        ->setTo("contact@smartunity.fr")
                        ->setBody($contenu);
            $this->get('mailer')->send($message);
        $form = $this->createFormBuilder()
        ->add('email', 'email', array(
            'required' => true))
        ->add('sujet', 'text', array(
            'required' => true))
        ->add('message', 'textarea', array(
            'required' => true))    
        ->getForm();

            $this->container->get('templating')->renderResponse($template, array(
            'form_contact'=> $form->createView(),
            'confirmation'=> $confirmation)
        );

        }
        return $this->render($template, array(
            'form_contact'=> $form->createView(),
            'confirmation'=> $confirmation)
        );
        
    }  
    
}
