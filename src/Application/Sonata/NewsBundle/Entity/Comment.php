<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\NewsBundle\Entity\BaseComment as BaseComment;

/**
 * @ORM\Table(name="news__comment")
 * @ORM\Entity(repositoryClass="Doctrine\ORM\EntityRepository")
 */
class Comment extends BaseComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }
}
