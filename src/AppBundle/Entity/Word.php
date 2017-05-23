<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="words", options={"collate"="utf8_bin"}))
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WordRepository")
 */
class Word extends UpdatableEntity
{
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $text;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $lemme;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $type;
    
    /**
     * @ORM\Column(type="string")
     */
    private $gender;
    
    /**
     * @ORM\Column(type="string")
     */
    private $number;
    
    /**
     * @ORM\Column(type="string")
     */
    private $form;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $isLem;
    
    /**
     * @ORM\Column(type="string")
     */
    private $possibleTypes;



    /**
     * Set text
     *
     * @param string $text
     * @return Word
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set lemme
     *
     * @param string $lemme
     * @return Word
     */
    public function setLemme($lemme)
    {
        $this->lemme = $lemme;

        return $this;
    }

    /**
     * Get lemme
     *
     * @return string 
     */
    public function getLemme()
    {
        return $this->lemme;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Word
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set gender
     *
     * @param string $gender
     * @return Word
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return Word
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set form
     *
     * @param string $form
     * @return Word
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form
     *
     * @return string 
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Set isLem
     *
     * @param boolean $isLem
     * @return Word
     */
    public function setIsLem($isLem)
    {
        $this->isLem = $isLem;

        return $this;
    }

    /**
     * Get isLem
     *
     * @return boolean 
     */
    public function getIsLem()
    {
        return $this->isLem;
    }

    /**
     * Set possibleTypes
     *
     * @param string $possibleTypes
     * @return Word
     */
    public function setPossibleTypes($possibleTypes)
    {
        $this->possibleTypes = $possibleTypes;

        return $this;
    }

    /**
     * Get possibleTypes
     *
     * @return string 
     */
    public function getPossibleTypes()
    {
        return $this->possibleTypes;
    }
    
    public static $TypeAbbreviation =  array (
     "ADJ" => "Adjectif",
     "ADV" => "Adverbe",
     "ART" => "Article",
     "AUX" => "Auxiliaire",
     "CON" => "Conjonction",
     "LIA" => "Liaison euphonique",
     "NOM" => "Nom",
     "ONO" => "Onomatopée",    
     "PRE" => "Préposition",
     "PRO" => "Pronom",
     "VER" => "Verbe",
	 "ADJ:ind" => "Adjectif",
	 "PRO:ind" => "Pronom"
    );
    
    /**
     * Get translation of Type in france
     *
     * @return string 
     */
    public function formatType()
    {
        if(empty($this->getType()))
            return '';
        return self::$TypeAbbreviation[$this->getType()];        
    }
    
    public static $GenderAbbreviation = array(
       "m" => "masculin",
       "f" => "féminin"
    );
    
    /**
     * Get translation of Gender in france
     *
     * @return string 
     */
    public function formatGerder()
    {
        if(empty($this->getGender()))
            return '';
        return self::$GenderAbbreviation[$this->getGender()];        
    }
    
    public static $NumberAbbreviation = array(
       "s" => "singulier",
       "p" => "pluriel"
    );
    
    /**
     * Get translation of Number in france
     *
     * @return string 
     */
    public function formatNumber()
    {
        if(empty($this->getNumber()))
            return '';
        return self::$NumberAbbreviation[$this->getNumber()];        
    }
    
    public static $ModeVerbAbbreviation = array(
       "ind" => "indicatif",
       "cnd" => "conditionnel",
       "sub" => "subjonctif",
       "par" => "participe",
       "inf" => "infinitif",
       "imp" => "impératif"        
    );
    
    public static $TempVerbAbbreviation = array(
       "pre" => "présent",
       "fut" => "futur",
       "imp" => "imparfait",
       "pas" => "passé"  
    );
    
    public static function getEntityName()
    {
      return get_called_class();
    }
    
}
