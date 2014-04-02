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
   
    ///Au demandeur quand il recoit une réponse
    public function sendReponseMessage(\SmartUnity\AppBundle\Entity\membre $demandeur, $repondant, $urlQuestion) {
        $subject = "Vous avez une nouvelle réponse!";
        $template = 'SmartUnityAppBundle:Mails:nouvelleReponse.html.twig';
        $to = $demandeur->getEmail();
        $body = $this->templating->render($template, array(
            'demandeur' => $demandeur,
            'repondant' => $repondant,
            'url' => $urlQuestion,
                ));
        
        $this->sendMessage($to, $subject, $body);
    }
    
    
    
}