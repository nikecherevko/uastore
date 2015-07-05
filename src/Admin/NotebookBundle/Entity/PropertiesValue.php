<?php

namespace Admin\NotebookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="notebook_properties_value")
 */
class PropertiesValue
{
    /**
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Notebook", inversedBy="propvalues")
     * @ORM\JoinColumn(name="notebook_id", referencedColumnName="id")
     */
    protected $notebook;
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Value", inversedBy="propvaluess")
     * @ORM\JoinColumn(name="value_id", referencedColumnName="id")
     */
    protected $value;

   

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
     * Set notebook
     *
     * @param \Admin\NotebookBundle\Entity\Notebook $notebook
     * @return PropertiesValue
     */
    public function setNotebook(\Admin\NotebookBundle\Entity\Notebook $notebook = null)
    {
        $this->notebook = $notebook;

        return $this;
    }

    /**
     * Get notebook
     *
     * @return \Admin\NotebookBundle\Entity\Notebook 
     */
    public function getNotebook()
    {
        return $this->notebook;
    }

    /**
     * Set value
     *
     * @param \Admin\NotebookBundle\Entity\Value $value
     * @return PropertiesValue
     */
    public function setValue(\Admin\NotebookBundle\Entity\Value $value = null)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return \Admin\NotebookBundle\Entity\Value 
     */
    public function getValue()
    {
        return $this->value;
    }
}
