<?php
/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="fos_user_user")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=7, max=100, minMessage="user.password.short", groups={"Registration","Profile"})
     */
    protected $plainPassword;
    
    /**
     * @ORM\Column(type="integer")
     */
    protected $balance = 0;

    /**
     * @ORM\Column(type="integer")
     */
    protected $orderNumber = 0;
    
    /**
     *
     * @ORM\OnetoMany(targetEntity="AppBundle\Entity\Buying", mappedBy="user")
     */
    protected $buyings;

    /**
     *
     * @ORM\OnetoMany(targetEntity="AppBundle\Entity\Favorite", mappedBy="user")
     */
    protected $favorites;

    /**
     * @return mixed
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

    /**
     * @param mixed $favorites
     */
    public function setFavorites($favorites)
    {
        $this->favorites = $favorites;
    }
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Language")
     * @Assert\NotBlank(message="Please enter your native language.", groups={"Registration", "Profile"})
     */
    protected $nativeLanguage;
    
    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebookId;
    
    /**
     * Get facebookId
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }
    
        /**
     * Set balance
     *
     * @param integer $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }
    
    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;

    /**
     * Get balance
     *
     * @return string 
     */
    public function getBalance()
    {
        return $this->balance;
    }
    
        /**
     * Set balance
     *
     * @param integer $balance
     * @return User
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Add buyings
     *
     * @param \AppBundle\Entity\Buying $buyings
     * @return User
     */
    public function addBuying(\AppBundle\Entity\Buying $buyings)
    {
        $this->buyings[] = $buyings;

        return $this;
    }

    /**
     * Remove buyings
     *
     * @param \AppBundle\Entity\Buying $buyings
     */
    public function removeBuying(\AppBundle\Entity\Buying $buyings)
    {
        $this->buyings->removeElement($buyings);
    }

    /**
     * Get buyings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBuyings()
    {
        return $this->buyings;
    }

    /**
     * Set orderNumber
     *
     * @param integer $orderNumber
     * @return User
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Get orderNumber
     *
     * @return integer 
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }
    
    public function getNativeLanguage()
    {
        return $this->nativeLanguage;
    }
    
     /**
     * Set culture
     *
     * @param Language $culture
     * @return User
     */
    public function setNativeLanguage(\AppBundle\Entity\Language $culture)
    {
        $this->nativeLanguage = $culture;

        return $this;
    }
    
    public static function getEntityName()
    {
      return get_called_class();
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string 
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }
}
