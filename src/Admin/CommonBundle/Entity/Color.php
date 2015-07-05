<?php

namespace Admin\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="color")
 * @UniqueEntity(
 *     fields={"hex"},
 *     errorPath="hex",
 *     message="Такой цвет уже существует."
 * )
 */
class Color
{
    /**
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * 
     * @Assert\NotBlank()
     */
    protected $nameru;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $nameeng;    
    
    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * 
     * @Assert\NotBlank()
     */
    protected $hex;    

    

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
     * Set nameru
     *
     * @param string $nameru
     * @return Color
     */
    public function setNameru($nameru)
    {
        $this->nameru = $nameru;

        return $this;
    }

    /**
     * Get nameru
     *
     * @return string 
     */
    public function getNameru()
    {
        return $this->nameru;
    }

    /**
     * Set nameeng
     *
     * @param string $nameeng
     * @return Color
     */
    public function setNameeng($nameeng)
    {
        $this->nameeng = $nameeng;

        return $this;
    }

    /**
     * Get nameeng
     *
     * @return string 
     */
    public function getNameeng()
    {
        return $this->nameeng;
    }

    /**
     * Set hex
     *
     * @param string $hex
     * @return Color
     */
    public function setHex($hex)
    {
        $this->hex = $hex;

        return $this;
    }

    /**
     * Get hex
     *
     * @return string 
     */
    public function getHex()
    {
        return $this->hex;
    }
}
