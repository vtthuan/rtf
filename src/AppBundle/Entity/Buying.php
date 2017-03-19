<?php

namespace AppBundle\Entity;

use Application\Sonata\NewsBundle\Entity\Post;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="buyings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BuyingRepository")
 */
class Buying extends UpdatableEntity
{
    const RECENT_BUYING_NUMBER = 5;
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="buyings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\NewsBundle\Entity\Post", inversedBy="buyings")
     */
    private $post;
    
    /**
     * @ORM\Column(type="string")
     */
    private $token;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $quantity;


    /**
     * Set token
     *
     * @param string $token
     * @return Buying
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Buying
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set user
     *
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @return Buying
     */
    public function setUser(\Application\Sonata\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Application\Sonata\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set post
     *
     * @param \Application\Sonata\NewsBundle\Entity\Post $post
     * @return Buying
     */
    public function setPost(\Application\Sonata\NewsBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \Application\Sonata\NewsBundle\Entity\Post 
     */
    public function getPost()
    {
        return $this->post;
    }
    
    public static function getEntityName()
    {
      return get_called_class();
    }
}
