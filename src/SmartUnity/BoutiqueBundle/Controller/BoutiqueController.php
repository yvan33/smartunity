<?php

namespace SmartUnity\BoutiqueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BoutiqueController extends Controller {

    public function indexAction() {
        $giftRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:gift');
        $gifts = $giftRepository->findAll();

        return $this->render('SmartUnityBoutiqueBundle::boutique.html.twig');
//        return $this->render('SmartUnityBoutiqueBundle::boutique2.html.twig', array(
//                    'gifts' => $gifts));
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

        //Gestion cagnotte User
        if ($cagnotte > $giftPrice) {
            $user->setCagnotte($cagnotte - $giftPrice);
        }
        else {
            $this->get('session')->getFlashBag()->add(
            'insufficientPool',
            'Vous n\'avez pas assez de points'
        );
            return $this->redirect($this->generateUrl('smart_unity_boutique_homepage'));
        }
        
        //Décrémenter quantité
        $gift->setQuantity($gift->getQuantity()-1);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        //Envoi du mail`
//        $sujetMail = "Vous avez commandé une cadeau";
//        $contenu = "Bonjour " . $user->getUsername() . ", <br/> Vous avez commandé ce cadeau: \"" . $gift->getName() ."\" . <br/> "
//                . " Nous vous recontacterons . <br/><br/>A bientöt sur smartunity.fr ";
//        $user->getEmail();
//        $message = \Swift_Message::newInstance()
//                ->setContentType('text/html')
//                ->setSubject($sujetMail)
//                ->setFrom(array('ne-pas-repondre@smartunity.fr' => 'Smart\'Unity'))
//                ->setTo($mailMembreQuestion)
//                ->setBody($contenu);
//        $this->get('mailer')->send($message);

        return $this->render('SmartUnityBoutiqueBundle::giftConfirmation.html.twig');
    }

}
