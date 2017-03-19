<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="report_link_messages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReportLinkMessageRepository")
 */
class ReportLinkMessage extends Message {

    /**
     * @ORM\OneToMany(targetEntity="Application\Sonata\NewsBundle\Entity\Post", mappedBy="reports")
     */
    protected $post;

    function __construct() {
        parent::__construct();
        $this->setBody("Le video est mort!!");
    }
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
