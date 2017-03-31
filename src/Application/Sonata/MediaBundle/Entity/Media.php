<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

use Sonata\MediaBundle\Entity\BaseMedia as BaseMedia;


class Media extends BaseMedia
{
    /**
     * @var int $id
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

    /**
     * @ORM\Column(type="string")
     */
    protected $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $viewCount = 0;

    /**
     * Set price
     *
     * @param int $count
     * @return Post
     */
    public function setViewCount($count)
    {
        $this->viewCount = $count;
        return $this;
    }

    /**
     * Get title
     *
     * @return int
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    public function getDuration()
    {
        return $this->duration;
    }

    public function setDuration($duration)
    {
        return $this->duration = $duration;
        return $this;
    }
    
    public static function getEntityName()
    {
      return get_called_class();
    }
}
