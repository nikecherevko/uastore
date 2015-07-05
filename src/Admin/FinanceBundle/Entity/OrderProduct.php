<?php

namespace Admin\FinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="order_product")
 * @ORM\Entity(repositoryClass="Admin\FinanceBundle\Repository\OrderProductRepository")
 */
class OrderProduct
{
    /**
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="orderproducts")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;
    /**
     * @ORM\ManyToOne(targetEntity="Admin\CategoryBundle\Entity\Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;
    
    /**
     * @ORM\ManyToOne(targetEntity="Admin\NotebookBundle\Entity\Notebook")
     * @ORM\JoinColumn(name="notebook_id", referencedColumnName="id")
     */
    protected $notebook;
    /**
     * @ORM\Column(type="smallint")
     */
    protected $count;


    public function __construct()
    {
        $this->count = 1;
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
     * Set count
     *
     * @param integer $count
     * @return OrderProduct
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Set order
     *
     * @param \Admin\FinanceBundle\Entity\Order $order
     * @return OrderProduct
     */
    public function setOrder(\Admin\FinanceBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \Admin\FinanceBundle\Entity\Order 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set category
     *
     * @param \Admin\CategoryBundle\Entity\Category $category
     * @return OrderProduct
     */
    public function setCategory(\Admin\CategoryBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Admin\CategoryBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set notebook
     *
     * @param \Admin\NotebookBundle\Entity\Notebook $notebook
     * @return OrderProduct
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
}
