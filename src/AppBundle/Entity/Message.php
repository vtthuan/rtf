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
 * Description of Message
 *
 * @author asus khadija
 */
/** @ORM\MappedSuperclass */
class Message extends UpdatableEntity {
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $name;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    protected $email;
    
        /**
     * Column name "is_read" because "read" is reserved mysql keyword
     *
     * @var bool
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    protected $read = false;
    
    /**
     *{@inheritdoc}
     */
    public function setRead($boolean)
    {
        $this->read = (bool) $boolean;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isRead()
    {
        return (bool) $this->read;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = (string) $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->email;
    }
    
        /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $body;

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->body = (string) $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }


}
