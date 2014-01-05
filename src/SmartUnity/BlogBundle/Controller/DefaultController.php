<?php

namespace SmartUnity\BlogBundle\Controller;

use Mv\BlogBundle\Controller\DefaultController as BaseController;


class DefaultController extends BaseController
{
    public function indexAction($name)
    {
        return $this->render('SmartUnityBlogBundle:Default:index.html.twig', array('name' => $name));
    }

    public function addCommentAction(Post $entity, Request $request){
        // Si le précédent commentaire est trop récent, on vire le client !
        $last_comment = $this->getDoctrine()->getManager()->getRepository('MvBlogBundle:AdminBlog\Comment')->findOneByIpLast( $this->getRequest()->getClientIp() );
        if($last_comment && (date('U') - $last_comment->getCreated()->format('U') < $this->container->getParameter('mv_blog.min_elapsed_time_comment')))
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(); // changer peut-être par quelque chose de plus ortodoxe...
        
        $comment  = new Comment();
        $comment->setPost($entity);
        $form = $this->createForm(new CommentType(), $comment);
        $form->bind($request);
        
        $comment->setToken($this->getRequest()->server->get('UNIQUE_ID') . date('U'));

        /** @var $t \Symfony\Bundle\FrameworkBundle\Translation\Translator */
        $t = $this->get('translator');

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $mailtest='a@a.fr';
            $pseudotest='essai';
            $comment->setEmail($mailtest);
            $comment->setPseudo($pseudotest);
            $em->persist($comment);

            $em->flush();
            
            $message = \Swift_Message::newInstance()
                ->setSubject($t->trans('default.comment.email.subject', array(), 'MvBlogBundle'))
                ->setFrom($this->container->getParameter('mv_blog.robot_email'))
                ->setTo($comment->getEmail())
                ->setBody($this->renderView('MvBlogBundle:Default/Mail:confirm-comment.txt.twig',
                                            array(  'message'       => $comment->getComment(),
                                                    'url_article'   => $this->generateUrl('blog_post_show', $entity->getRoutingParams(),true),
                                                    'url'           => $this->generateUrl('blog_post_comment_confirm', array('email' => $comment->getEmail(), 'token' => $comment->getToken()), true))))
            ;
            $this->get('mailer')->send($message);

            $this->get('session')->getFlashBag()->add('notice', $t->trans('default.comment.saved', array(), 'MvBlogBundle'));
            $this->get('session')->getFlashBag()->add('notice', $t->trans('default.comment.message_notice', array(), 'MvBlogBundle'));
    
            return $this->redirect($this->generateUrl('blog_post_show', $entity->getRoutingParams()));
        }
        
        return array(
            'entity'      => $entity,
            'form'        => $form->createView(),
            'facebook_api_id'   => $this->container->getParameter('mv_blog.facebook_api_id')
        );
    }
}
