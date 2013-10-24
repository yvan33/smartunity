<?php

namespace SmartUnity\UtilisateurBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmartUnityUtilisateurBundle extends Bundle
{
	    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
