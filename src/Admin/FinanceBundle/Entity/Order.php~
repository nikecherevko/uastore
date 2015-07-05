<?php

namespace Admin\FinanceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Column(type="smallint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\ManyToOne(targetEntity="Admin\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @ORM\OneToMany(targetEntity="OrderProduct", mappedBy="order")
     */
    protected $orderproducts;
    
    /**
     * @ORM\ManyToOne(targetEntity="Payment")
     * @ORM\JoinColumn(name="payment_id", referencedColumnName="id")
     * 
     * @Assert\NotBlank()
     */
    protected $payment;
    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * 
     * @Assert\NotBlank()
     */
    protected $price;
    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank()
     */
    protected $customer;
    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank()
     */
    protected $address; 
    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank()
     */
    protected $telephone;
    /**
     * @ORM\Column(type="smallint")
     */
    protected $state;
    /**
    * @ORM\Column(type="datetime")
    */
    protected $created;
    /**
    * constructor
    */
    public function __construct()
    {
      $this->created = new \DateTime();
      $this->state = 0;
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
     * Set price
     *
     * @param string $price
     * @return Order
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

    /**
     * Set customer
     *
     * @param string $customer
     * @return Order
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return string 
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Order
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
     * @return Order
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
     * Set state
     *
     * @param integer $state
     * @return Order
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Order
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set user
     *
     * @param \Admin\UserBundle\Entity\User $user
     * @return Order
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

    /**
     * Add orderproducts
     *
     * @param \Admin\FinanceBundle\Entity\OrderProduct $orderproducts
     * @return Order
     */
    public function addOrderproduct(\Admin\FinanceBundle\Entity\OrderProduct $orderproducts)
    {
        $this->orderproducts[] = $orderproducts;

        return $this;
    }

    /**
     * Remove orderproducts
     *
     * @param \Admin\FinanceBundle\Entity\OrderProduct $orderproducts
     */
    public function removeOrderproduct(\Admin\FinanceBundle\Entity\OrderProduct $orderproducts)
    {
        $this->orderproducts->removeElement($orderproducts);
    }

    /**
     * Get orderproducts
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOrderproducts()
    {
        return $this->orderproducts;
    }

    /**
     * Set payment
     *
     * @param \Admin\FinanceBundle\Entity\Payment $payment
     * @return Order
     */
    public function setPayment(\Admin\FinanceBundle\Entity\Payment $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return \Admin\FinanceBundle\Entity\Payment 
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
