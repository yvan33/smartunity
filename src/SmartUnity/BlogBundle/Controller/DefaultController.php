<?php

namespace SmartUnity\BlogBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Mv\BlogBundle\Entity\AdminBlog\Category;
use Mv\BlogBundle\Entity\AdminBlog\Post;
use Mv\BlogBundle\Entity\AdminBlog\Comment;
use Mv\BlogBundle\Form\AdminBlog\CommentType;
use Doctrine\Common\Collections\ArrayCollection;
use Mv\BlogBundle\Entity\AdminBlog\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

use Symfony\Component\HttpFoundation\Response;

use Mv\BlogBundle\Controller\DefaultController as BaseController;


class DefaultController extends BaseController
{
    public function indexAction($name)
    {
        return $this->render('SmartUnityBlogBundle:Default:index.html.twig', array('name' => $name));
    }

    public function showArticleAction(Post $post)
    {
        $routing_params = $post->getRoutingParams();
        unset($routing_params['id']);
        foreach ($routing_params AS $key => $value)
            if($this->get('request')->get($key) != $value)
            return $this->redirect($this->generateUrl('blog_post_show', $post->getRoutingParams()));
        
        $comment = new Comment();
        $comment->setIp($this->getRequest()->getClientIp());
        $form   = $this->createForm(new CommentType(), $comment);
        $user=$this->getUser();
        return $this->render( 'SmartUnityBlogBundle:Default:showArticle.html.twig',array(
            'entity'            => $post,
            'form'              => $form->createView(),
            'facebook_api_id'   => $this->container->getParameter('mv_blog.facebook_api_id'),
            'user'              => $user
        ));
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
        $user = $this->getUser();
        $mail=$user->getEmail();
        $pseudo=$user->getUsername();
        $comment->setEmail($mail);
        $comment->setPseudo($pseudo);

        if ($form->isValid()) {

            $em = $this->getDoctrine()->getManager();

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
        
        return $this->render( 'SmartUnityBlogBundle:Default:showArticle.html.twig',array(
            'entity'      => $entity,
            'form'        => $form->createView(),
            'facebook_api_id'   => $this->container->getParameter('mv_blog.facebook_api_id'),
            'user' => $user
        ));
    }
}
