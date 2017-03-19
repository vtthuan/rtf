<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="languages")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LanguageRepository")
 */
class Language extends UpdatableEntity
{
    /**
     * @ORM\OneToMany(
     *      targetEntity="Subtitle",
     *      mappedBy="language"
     * )
     */
    private $subtitles;
    
    /**
     *
     * @ORM\Column(type="string")
     * @Assert\Length(min=2, max=2)
     */
    protected $code;

    /**
     * Set code
     *
     * @param string $code
     * @return Language
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }
    
    public function __toString() {
        return $this->code;
    }
    
    public static function getEntityName()
    {
      return get_called_class();
    }
    
}
