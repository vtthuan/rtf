<?php

namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
           
        );
    }

    public function getName()
    {
        return 'app_extension';
    }
}