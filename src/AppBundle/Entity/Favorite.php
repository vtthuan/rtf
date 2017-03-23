<?php

namespace AppBundle\Entity;

use Application\Sonata\NewsBundle\Entity\Post;
use Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="favorites")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FavoriteRepository")
 */
class Favorite extends UpdatableEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="favorites")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Application\Sonata\NewsBundle\Entity\Post", inversedBy="favorites")
     */
    private $post;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $start;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $end;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    private $phrase;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * @param mixed $post
     */
    public function setPost($post)
    {
        $this->post = $post;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getPhrase()
    {
        return $this->phrase;
    }

    /**
     * @param mixed $phrase
     */
    public function setPhrase($phrase)
    {
        $this->phrase = $phrase;
    }


    public static function getEntityName()
    {
        return get_called_class();
    }
}
