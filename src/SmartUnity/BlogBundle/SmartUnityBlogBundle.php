<?php

namespace SmartUnity\BlogBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmartUnityBlogBundle extends Bundle
{
	    public function getParent()
    {
        return 'MvBlogBundle';
    }

}
