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

use Sonata\NewsBundle\Entity\BasePost as BasePost;
use Application\Sonata\ClassificationBundle\Entity\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="news__post")
 * @ORM\Entity(repositoryClass="Application\Sonata\NewsBundle\Entity\PostRepository")
 */
class Post extends BasePost
{
    const NUM_ITEMS = 10;
    
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
    
    /**
     * @ORM\Column(type="integer")
     */
    private $price = 0;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $viewCount = 0;
    /**
     * @ORM\Column(type="integer")
     */
    private $viewInit = 0;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isArticle = false;
        
    /**
     * @ORM\OneToMany(
     *      targetEntity="AppBundle\Entity\Buying",
     *      mappedBy="post")
     * )
     */
    private $buyings;

    /**
     * @ORM\OneToMany(
     *      targetEntity="AppBundle\Entity\Favorite",
     *      mappedBy="post")
     * )
     */
    private $favorites;
    
    /**
     * @ORM\OneToMany(
     *      targetEntity="AppBundle\Entity\Subtitle",
     *      mappedBy="post")
     * )
     */
    private $subtitles;
	
	    /**
     * @ORM\OneToMany(
     *      targetEntity="AppBundle\Entity\ReportLinkMessage",
     *      mappedBy="post")
     * )
     */
    private $reports;
    
    
    public function __construct()
    {
        parent::__construct();
        $this->buyings = new ArrayCollection();
        $this->viewInit = rand(0,1000);
    }
    
        /**
     * Set price
     *
     * @param int $price
     * @return Post
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get title
     *
     * @return int 
     */
    public function getPrice()
    {
        return $this->price;
    }
    
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
    
    /**
     * Get title
     *
     * @return int 
     */
    public function getViewInit()
    {
        return $this->viewInit;
    }
    
    
        /**
     * Set isArticle
     *
     * @param boolean $isArticle
     * @return Post
     */
    public function setIsArticle($isArticle)
    {
        $this->isArticle = $isArticle;

        return $this;
    }

    /**
     * Get title
     *
     * @return boolean 
     */
    public function getIsArticle()
    {
        return $this->isArticle;
    }
    
    public function getAvaiblesLanguages()
    {
        $languages = array();
        foreach($this->subtitles as $subtitle)
        {
            $languages[] = $subtitle->getLanguage()->getCode();
        }
        return $languages;
    }
    
    public function getSubtitleByCode($language)
    {
        foreach($this->subtitles as $subtitle)
        {
            if($subtitle->getLanguage()->getCode() == $language)
            {
                return $subtitle;
            }                
        }
        return null;
    }
    
    public static function getEntityName()
    {
      return get_called_class();
    }
}
