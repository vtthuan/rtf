<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Table(name="contact_messages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactMessageRepository")
 */
class ContactMessage extends Message {

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Message #' . $this->getId();
    }
    
    public static function getEntityName()
    {
      return get_called_class();
    }
    
}
