<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\ClassificationBundle\Entity;

use Sonata\ClassificationBundle\Entity\BaseCollection as BaseCollection;
use Application\Sonata\NewsBundle\Entity\Post;

/**
 * This file has been generated by the Sonata EasyExtends bundle.
 *
 * @link https://sonata-project.org/bundles/easy-extends
 *
 * References :
 *   working with object : http://www.doctrine-project.org/projects/orm/2.0/docs/reference/working-with-objects/en
 *
 * @author <yourname> <youremail>
 */
class Collection extends BaseCollection
{
    /**
     * @var int $id
     */
    protected $id;
    
    protected $posts;

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }  
    
    /**
     * {@inheritdoc}
     */
    public function addPosts(Post $post)
    {
        $this->posts[] = $post;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setPosts($posts)
    {
        $this->posts = new \Doctrine\Common\Collections\ArrayCollection;

        foreach ($posts as $post) {
            $this->addPosts($post);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPosts()
    {
        return $this->posts;
    }
    
    public static function getEntityName()
    {
      return get_called_class();
    }
}