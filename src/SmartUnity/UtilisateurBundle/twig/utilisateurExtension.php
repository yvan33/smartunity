<?php

namespace SmartUnity\UtilisateurBundle\Twig;

use Symfony\Component\HttpFoundation\Request;

class utilisateurExtension extends \Twig_Extension {
    
        public function getGlobals()
    {
            
        
        $request = Request::createFromGlobals();
        $session = $request->getSession();
            // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
        return array(
            'last_username' => $lastUsername,
        );
    }
    
    public function getName() {
        return 'utilisateur_extension';
    }

//put your code here
}
