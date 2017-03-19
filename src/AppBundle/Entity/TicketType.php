<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="ticket_types")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TicketTypeRepository")
 */
class TicketType extends UpdatableEntity
{
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
    */
    private $name;
    
    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
    */
    private $quantity;
    
    /**
     * @ORM\Column(type="decimal")
     * @Assert\NotBlank()
    */
    private $price;
    

    /**
     * Set name
     *
     * @param string $name
     * @return TicketType
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return TicketType
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
     * Set price
     *
     * @param string $price
     * @return TicketType
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string 
     */
    public function getPrice()
    {
        return $this->price;
    }
    
    public static function getEntityName()
    {
      return get_called_class();
    }
}
