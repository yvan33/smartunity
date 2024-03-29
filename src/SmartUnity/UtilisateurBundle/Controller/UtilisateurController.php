<?php

namespace SmartUnity\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SmartUnity\AppBundle\Entity\avatar;
use SmartUnity\AppBundle\Entity\parrainage;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UtilisateurController extends Controller {

    public function indexAction($formPassword = null, $formInfos = null, $formAvatar = null) {

        $user = $this->getUser();
        $parrainage = new parrainage($user);

        $form_pref = $this->createForm('smartunity_user_preference', $user);
        $form_parrainage = $this->createForm('smartunity_user_parrainage', $parrainage);
        $em = $this->getDoctrine()->getEntityManager();

        $membreRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:membre');

        $userid = $user->getId();
        $smartreponse = $membreRepository->getSmartReponses($userid);
        $parrainmembre = $user->getParrain();
        if (isset($parrainmembre)) {
            $parrain = $membreRepository->find($parrainmembre)->getUsername();
        } else {
            $parrain = "";
        }

        $avancement = 25;
        $avatar = $em->getRepository('SmartUnityAppBundle:avatar')->find($userid);
        if (isset($avatar)) {
            $avatar = $avatar->getWebPath();
            $avancement +=25;
        }
        if ((null !== $user->getNom()) && (null !== $user->getPrenom())) {
            $avancement +=10;
        }
        if ((null !== $user->getAdresse())) {
            $avancement +=10;
        }

        if (("na" !== $user->getSexe())) {
            $avancement +=5;
        }

        if ((null !== $user->getDateNaissance())) {
            $avancement +=5;
        }
        if ((null !== $user->getAppareils())) {
            $avancement +=10;
        }


        if (isset($formPassword)) {

            return $this->render('SmartUnityUtilisateurBundle:ChangePassword:changePassword.html.twig', array(
                        'form_pref' => $form_pref->createView(),
                        'smartrep' => $smartreponse,
                        'form_password' => $formPassword,
                        'form_parrainage' => $form_parrainage->createView(),
                        'parrain' => $parrain
            ));
        } else if (isset($formInfos)) {
            return $this->render('SmartUnityUtilisateurBundle:Profile:edit.html.twig', array(
                        'form_pref' => $form_pref->createView(),
                        'smartrep' => $smartreponse,
                        'form_infos' => $formInfos,
                        'form_parrainage' => $form_parrainage->createView(),
                        'parrain' => $parrain
            ));
        } else if (isset($formAvatar)) {
            return $this->render('SmartUnityUtilisateurBundle:Profile:avatar.html.twig', array(
                        'form_pref' => $form_pref->createView(),
                        'smartrep' => $smartreponse,
                        'form_parrainage' => $form_parrainage->createView(),
                        'parrain' => $parrain,
                        'form_avatar' => $formAvatar,
                        'avatar' => $avatar,
            ));
        } else {

            return $this->render('SmartUnityUtilisateurBundle:Profile:show.html.twig', array(
                        'form_pref' => $form_pref->createView(),
                        'smartrep' => $smartreponse,
                        'avatar' => $avatar,
                        'form_parrainage' => $form_parrainage->createView(),
                        'parrain' => $parrain,
                        'avancement' => $avancement
            ));
        }
    }

    public function editInfosAction(Request $request) {

        $user = $this->getUser();
        $formInfos = $this->createForm('smartunity_user_informations', $user);

        if ('POST' === $request->getMethod()) {
            $formInfos->bind($request);

            if ($formInfos->isValid()) {
                $userManager = $this->container->get('fos_user.user_manager');
                $userManager->updateUser($user);

                $url = $this->generateUrl('smart_unity_utilisateur_homepage');
                $response = new RedirectResponse($url);

                return $response;
            }
        }
        return $this->forward(
                        'SmartUnityUtilisateurBundle:Utilisateur:index', array(
                    'formInfos' => $formInfos->createView(),
        ));
    }

    public function setPrefAction() {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm('smartunity_user_preference', $user);
        $form->bind($this->getRequest());

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('smart_unity_utilisateur_homepage'));
    }

    public function setInfosAction() {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $form = $this->createForm('smartunity_user_informations', $user);
        $form->bind($this->getRequest());

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('smart_unity_utilisateur_homepage'));
    }

    public function uploadavatarAction(Request $request) {

        $user = $this->getUser();
        $userid = $user->getId();

        $em = $this->getDoctrine()->getManager();
        $avatar = $em->getRepository('SmartUnityAppBundle:avatar')->find($userid);

        if (!isset($avatar)) {

            $avatar = new avatar();
        }

        $form = $this->createFormBuilder($avatar)
                ->add('id', 'hidden', array(
                    'data' => $userid,
                ))
                ->add('file')
                ->getForm();

        $form->handleRequest($request);


        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($avatar);
            $em->flush();

            return $this->redirect($this->generateUrl('smart_unity_utilisateur_homepage'));
        }


        return $this->forward(
                        'SmartUnityUtilisateurBundle:Utilisateur:index', array(
                    'formAvatar' => $form->createView(),
        ));
    }

    public function envoiParrainageAction(Request $request) {

        if (!isset($parrainage)) {

            $user = $this->getUser();
            $parrainage = new parrainage($user);
        }

        if ($request->getMethod() == 'POST') {
            
            $form = $this->createForm('smartunity_user_parrainage', $parrainage);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $mailFilleul = $form->get('email')->getData();
                $userid = $parrainage->getId();
                $concat = $mailFilleul . $userid;
                $code = sha1($concat);
                $parrainage->setCode($code);
                $em = $this->getDoctrine()->getManager();
                $em->persist($parrainage);
                $em->flush();

                $urlParrainage = $this->generateUrl('smart_unity_utilisateur_confirmparrainage', array('code' => $code), true);

                //Envoi du mail
                $this->get('smart_unity_app.mailer')->parrainageMessage($user, $mailFilleul, $urlParrainage);
                $this->get('session')->getFlashBag()->add(
                        'parrainageEnvoye', 'Une invitation de parranaige à bien été envoyée à cette adresse : '.$mailFilleul
                );
                
                return $this->redirect($this->generateUrl('smart_unity_utilisateur_homepage'));
            }
            throw new Exception('Le formulaire de parrainage n\'est pas valide');
        }
            throw new Exception('L\'accès à la méthode ne s\'est pas fait par soumission du formulaire.');

    }

    public function confirmParrainageAction($code) {
        $parrainrepository = $this->getDoctrine()->getManager()->getRepository('SmartUnityAppBundle:parrainage');
        $parrains = $parrainrepository->getParrainByCode($code);
        $parrain = $parrains->getMembre();
        return $this->forward('SmartUnityUtilisateurBundle:Registration:register', array('parrain' => $parrain));
    }

    public function removeavatarAction() {
        $user = $this->getUser();
        $userid = $user->getId();
        $em = $this->getDoctrine()->getManager();
        $avatar = $em->getRepository('SmartUnityAppBundle:avatar')->find($userid);
        $em->remove($avatar);
        $em->flush();
        return $this->forward('SmartUnityUtilisateurBundle:Utilisateur:index');
    }

    public function profilAction($id) {

        $em = $this->getDoctrine()->getManager();

        $membre = $em->getRepository('SmartUnityAppBundle:membre')->find($id);

        $avatar = $em->getRepository('SmartUnityAppBundle:avatar')->find($membre->getId());

        if (isset($avatar)) {
            $avatar = $avatar->getWebPath();
        }

        return $this->render('SmartUnityUtilisateurBundle:ProfilPublic:profil.html.twig', array(
                    'membre' => $membre,
                    'membreId' => $id,
                    'avatar' => $avatar,
        ));
    }

}
