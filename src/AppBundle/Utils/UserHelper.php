<?php

namespace AppBundle\Utils;

use Application\Sonata\UserBundle\Entity\User;

class UserHelper
{
    protected $nativeLanguage;
    public function __construct($language)
    {
        $this->nativeLanguage = $language;
    }
    
    public function findLanguage(User $user = null)
    {
        $language = $this->nativeLanguage;
        if($user && $user->getNativeLanguage())
        {
            $language = $user->getNativeLanguage()->getCode();
        }
        return $language;
    }
}