<?php

namespace Admin\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="user_address")
 */
class Address
{
    /**
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="addresss")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * 
     * @Assert\NotBlank()
     */
    protected $user;
    
    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     minMessage = "Минимальное количество 2 символа",
     * )
     */
    protected $address;
    
    /**
     * @ORM\Column(type="string", length=50)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     minMessage = "Минимальное количество 2 символа",
     * )
     */
    protected $telephone;
    
    /**
     * @Assert\Email(message = "Email is not valid")
     * @ORM\Column(type="string", length=60)
     */
    protected $email;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $home;
    
    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $apartment;

    /**
     * Get id
     *
     * @return integer 
     */
     

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Address
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Address
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Address
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set home
     *
     * @param string $home
     * @return Address
     */
    public function setHome($home)
    {
        $this->home = $home;

        return $this;
    }

    /**
     * Get home
     *
     * @return string 
     */
    public function getHome()
    {
        return $this->home;
    }

    /**
     * Set apartment
     *
     * @param string $apartment
     * @return Address
     */
    public function setApartment($apartment)
    {
        $this->apartment = $apartment;

        return $this;
    }

    /**
     * Get apartment
     *
     * @return string 
     */
    public function getApartment()
    {
        return $this->apartment;
    }

    /**
     * Set user
     *
     * @param \Admin\UserBundle\Entity\User $user
     * @return Address
     */
    public function setUser(\Admin\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Admin\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
