<?php

namespace Admin\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity
 * @ORM\Table(name="menu_category")
 * 
 * @UniqueEntity(
 *     fields={"name"},
 *     errorPath="name",
 *     message="Категория с таким названием уже существует."
 * )
 */
class Category
{
    /**
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
     /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * 
     * @Assert\NotBlank()
     */
    protected $name;
    
    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="category")
     */
    protected $menus;
    
    /**
     * @ORM\Column(type="smallint")
     */
    protected $position;
    
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * 
     * @Assert\NotBlank()
     */
    protected $route;
    
    
    
    public function __toString()
    {
        return $this->getName();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->menus = new \Doctrine\Common\Collections\ArrayCollection();
        $this->position = 0;
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
     * Set name
     *
     * @param string $name
     * @return Category
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
     * Set position
     *
     * @param integer $position
     * @return Category
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set route
     *
     * @param string $route
     * @return Category
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string 
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Add menus
     *
     * @param \Admin\MenuBundle\Entity\Menu $menus
     * @return Category
     */
    public function addMenu(\Admin\MenuBundle\Entity\Menu $menus)
    {
        $this->menus[] = $menus;

        return $this;
    }

    /**
     * Remove menus
     *
     * @param \Admin\MenuBundle\Entity\Menu $menus
     */
    public function removeMenu(\Admin\MenuBundle\Entity\Menu $menus)
    {
        $this->menus->removeElement($menus);
    }

    /**
     * Get menus
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMenus()
    {
        return $this->menus;
    }
}
