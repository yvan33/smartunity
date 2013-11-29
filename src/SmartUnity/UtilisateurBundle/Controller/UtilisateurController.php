<?php

namespace SmartUnity\UtilisateurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;
use SmartUnity\AppBundle\Entity\avatar;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;

class UtilisateurController extends Controller {

    public function indexAction(Request $request, $formPassword = null, $formInfos = null) {

        $user = $this->container->get('security.context')->getToken()->getUser();

        $form_pref = $this->createForm('smartunity_user_preference', $user);
        $em = $this->getDoctrine()->getEntityManager();

        $membreRepository = $this->getDoctrine()
                ->getManager()
                ->getRepository('SmartUnityAppBundle:membre');

        $userid = $user->getId();

        $smartreponse = $membreRepository->getSmartReponses($userid);
        $remuneration = 0;
        $remuneration = $membreRepository->getRemuneration($userid);

        if (isset($formPassword)) {

            return $this->render('SmartUnityUtilisateurBundle:ChangePassword:changePassword.html.twig', array(
                        'form_pref' => $form_pref->createView(),
                        'smartrep' => $smartreponse,
                        'remuneration' => $remuneration,
                        'form_password' => $formPassword
            ));
        } else if (isset($formInfos)) {
            return $this->render('SmartUnityUtilisateurBundle:Profile:edit.html.twig', array(
                        'form_pref' => $form_pref->createView(),
                        'smartrep' => $smartreponse,
                        'remuneration' => $remuneration,
                        'form_infos' => $formInfos,
            ));
        } else {

            $avatar = $em->getRepository('SmartUnityAppBundle:avatar')->find($userid);
            if (isset($avatar)) {
                $avatar = $avatar->getWebPath();
            }

            return $this->render('SmartUnityUtilisateurBundle:Profile:show.html.twig', array(
                        'form_pref' => $form_pref->createView(),
                        'smartrep' => $smartreponse,
                        'remuneration' => $remuneration,
                        'avatar' => $avatar,
            ));
        }
    }

    public function editInfosAction() {

        $user = $this->container->get('security.context')->getToken()->getUser();
        $formInfos = $this->createForm('smartunity_user_informations', $user);

        return $this->forward(
                        'SmartUnityUtilisateurBundle:Utilisateur:index', array(
                    'formInfos' => $formInfos->createView(),
        ));
    }

    public function setPrefAction() {

        $em = $this->getDoctrine()->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
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
        $user = $this->container->get('security.context')->getToken()->getUser();
        $form = $this->createForm('smartunity_user_informations', $user);
        $form->bind($this->getRequest());

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }


        return $this->redirect($this->generateUrl('smart_unity_utilisateur_homepage'));
    }

    public function uploadAction(Request $request) {

        $user = $this->container->get('security.context')->getToken()->getUser();
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
                ->add('save', 'submit')
                ->getForm();

        $form->handleRequest($request);


        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $em->persist($avatar);
            $em->flush();
            
            $this->resizeAvatarAction($avatar);



            
            
            return $this->redirect($this->generateUrl('smart_unity_utilisateur_homepage'));
        }


        return $this->render('SmartUnityUtilisateurBundle:Profile:avatar.html.twig', array(
                    'form' => $form->createView()
        ));
    }

    public function removeAvatarAction($avatar) {

        $em = $this->getDoctrine()->getManager();

        $em->remove($avatar);
        $em->flush();
    }
    
    public function resizeAvatarAction($avatar) {

                    $imagine = new Imagine();
                    
                    $webPath=realpath(__DIR__ .'/../../../../web/');
                    $newPath=realpath(__DIR__ .'/../../../../web/uploads/avatars');
                    $avatarPath= $webPath.'/'.$avatar->getWebPath();
//                    die($avatarPath);
            $image = $imagine->open($avatarPath);
            die ($new = realpath($avatarPath.'/../'));
            $image->resize(new Box(15, 25))
                    ->rotate(45)
                    ->crop(new Point(0, 0), new Box(45, 45))
                    ->save($newPath.);
    }

}
