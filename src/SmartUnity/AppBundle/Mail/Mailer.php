<?php

// /SmartUnity/AppBundle/Mail/Mailer.php 

namespace SmartUnity\AppBundle\Mail;

use Symfony\Component\Templating\EngineInterface;

class Mailer {

    protected $mailer;
    protected $templating;
    private $from = "ne-pas-repondre@smartunity.fr";
    private $reply = "contact@smartunity.fr";
    private $name = "Equipe Smart'Unity";

    public function __construct($mailer, EngineInterface $templating) {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    protected function sendMessage($to, $subject, $body) {
        $mail = \Swift_Message::newInstance();
        $mail->setFrom($this->from, $this->name)->setTo($to)->setSubject($subject)->setBody($body)->setReplyTo($this->reply, $this->name)->setContentType('text/html');
        $this->mailer->send($mail);
    }

//////Méthodes publiques d'envoi de mail//////////////////
//
    ///Au demandeur quand il recoit une réponse
    public function newAnswerMessage(\SmartUnity\AppBundle\Entity\membre $demandeur, $repondant, $urlQuestion) {
        $subject = "Vous avez une nouvelle réponse !";
        $template = 'SmartUnityAppBundle:Mails:nouvelleReponse.html.twig';
        $to = $demandeur->getEmail();
        $body = $this->templating->render($template, array(
            'demandeur' => $demandeur,
            'repondant' => $repondant,
            'url' => $urlQuestion,
        ));
        $this->sendMessage($to, $subject, $body);
    }

    //Au répondant quand sa réponse à été validée par le demandeur
    public function validatedAnswerMessage($repondant, $demandeur, $question, $urlQuestion) {

        $subject = "Votre réponse a été validée !";
        $template = 'SmartUnityAppBundle:Mails:validation.html.twig';
        $to = $repondant->getEmail();
        $body = $this->templating->render($template, array(
            'demandeur' => $demandeur,
            'repondant' => $repondant,
            'question' => $question,
            'url' => $urlQuestion,
        ));
        $this->sendMessage($to, $subject, $body);
    }
    
    //Au répondant quand sa réponse à été validée par le demandeur
    public function certifiedAnswerMessage($repondant, $question, $urlQuestion) {

        $subject = "Votre réponse a été certifiée par notre équipe !";
        $template = 'SmartUnityAppBundle:Mails:certification.html.twig';
        $to = $repondant->getEmail();
        $body = $this->templating->render($template, array(
            'repondant' => $repondant,
            'question' => $question,
            'url' => $urlQuestion,
        ));
        $this->sendMessage($to, $subject, $body);
    }
    
    //Demande de parrainage
    public function parrainageMessage($parrain, $mailFilleul, $urlParrainage) {

        $subject = $parrain->getUsername(). "vous invite à rejoindre Smart'Unity !";
        $template = 'SmartUnityAppBundle:Mails:invitation_parrainage.html.twig';
        $to = $mailFilleul;
        $urlCCM = $this->generateUrl('smart_unity_app_descriptionpage', true);
        $body = $this->templating->render($template, array(
            'parrain' => $parrain,
            'urlParrainage' => $urlParrainage,
            'urlCCM' => $urlCCM
        ));
        $this->sendMessage($to, $subject, $body);
    }
    
}   