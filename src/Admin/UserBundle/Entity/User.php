<?php

namespace Admin\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $firstname;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastname;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity="Address", mappedBy="user")
     */
    protected $addresss;
    
    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

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
     * Set firstname
     *
     * @param string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }
    
    
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

     

    /**
     * Add addresss
     *
     * @param \Admin\UserBundle\Entity\Address $addresss
     * @return User
     */
    public function addAddresss(\Admin\UserBundle\Entity\Address $addresss)
    {
        $this->addresss[] = $addresss;

        return $this;
    }

    /**
     * Remove addresss
     *
     * @param \Admin\UserBundle\Entity\Address $addresss
     */
    public function removeAddresss(\Admin\UserBundle\Entity\Address $addresss)
    {
        $this->addresss->removeElement($addresss);
    }

    /**
     * Get addresss
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddresss()
    {
        return $this->addresss;
    }
}
