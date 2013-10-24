<?php

namespace SmartUnity\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SmartUnityForumBundle:Default:index.html.twig');
    }
}
