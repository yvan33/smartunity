<?php

namespace SmartUnity\BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class BoutiqueController extends Controller {

    public function indexAction() {
        $giftRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:gift');
        $gifts = $giftRepository->findAll();

        if (empty($gifts)) {
            return $this->render('SmartUnityBoutiqueBundle::boutique_no_gifts.html.twig');
        }

        return $this->render('SmartUnityBoutiqueBundle::boutique.html.twig', array(
                    'gifts' => $gifts));
    }

    public function confirmGiftAction($id) {

        //Get gift and User
        $giftRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:gift');

        $gift = $giftRepository->findOneById($id);
        $user = $this->getUser();
        $cagnotte = $user->getCagnotte();
        $giftPrice = $gift->getPrice();

        //Check quantity
        if ($gift->getQuantity() > 0) {
            //Check pool and handle it
            if ($cagnotte > $giftPrice) {
                $user->setCagnotte($cagnotte - $giftPrice);
            } else {
                $this->get('session')->getFlashBag()->add(
                        'insufficientPool', 'Vous n\'avez pas assez de points'
                );
                return $this->redirect($this->generateUrl('smart_unity_boutique_homepage'));
            }

            $gift->setQuantity($gift->getQuantity() - 1);
        } else {
            $this->get('session')->getFlashBag()->add(
                    'insufficientQuantity', 'Nous sommes désolé, mais ce cadeau n\'est plus disponible'
            );
            return $this->redirect($this->generateUrl('smart_unity_boutique_homepage'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->persist($gift);
        $em->flush();

        //Send mail to the user
        $sujetMail = "Vous avez commandé un cadeau!";
        $contenu = "Bonjour " . $user->getUsername() . ", <br/> Vous avez commandé ce cadeau : \"" . $gift->getName() . "\". <br/> "
                . " Nous allons traiter votre commande très rapidement et revenons vers vous! <br/><br/>A bientôt sur smartunity.fr, <br/><br/>L'équipe Smart'Unity";
        $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject($sujetMail)
                ->setFrom(array('ne-pas-repondre@smartunity.fr' => 'Smart\'Unity'))
                ->setTo($user->getEmail())
                ->setBody($contenu);
        $this->get('mailer')->send($message);
        
        //Send mail to Smart'Unity
        $sujetMail = $user->getUsername(). " vient de commander un cadeau";
        $contenu = $user->getUsername() . " a commandé ce cadeau : \"" . $gift->getName() . "\". <br/> "
                . " Il en reste : ".$gift->getQuantity();
        $message = \Swift_Message::newInstance()
                ->setContentType('text/html')
                ->setSubject($sujetMail)
                ->setFrom(array('ne-pas-repondre@smartunity.fr' => 'Smart\'Unity'))
                ->setTo('contact@smartunity.fr')
                ->setBody($contenu);
        $this->get('mailer')->send($message);

        return $this->render('SmartUnityBoutiqueBundle::giftConfirmation.html.twig', array(
                    'gift' => $gift));
    }

}
