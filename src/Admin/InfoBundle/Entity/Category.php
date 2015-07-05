<?php

namespace Admin\InfoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity
 * @ORM\Table(name="info_category")
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
     * @ORM\OneToMany(targetEntity="Page", mappedBy="category")
     */
    protected $pages;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add pages
     *
     * @param \Admin\InfoBundle\Entity\Page $pages
     * @return Category
     */
    public function addPage(\Admin\InfoBundle\Entity\Page $pages)
    {
        $this->pages[] = $pages;

        return $this;
    }

    /**
     * Remove pages
     *
     * @param \Admin\InfoBundle\Entity\Page $pages
     */
    public function removePage(\Admin\InfoBundle\Entity\Page $pages)
    {
        $this->pages->removeElement($pages);
    }

    /**
     * Get pages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPages()
    {
        return $this->pages;
    }
    
    public function __toString()
    {
        return $this->getName();
    }
}
