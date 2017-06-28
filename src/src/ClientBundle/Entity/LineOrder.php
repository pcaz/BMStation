<?php
namespace ClientBundle\Entity;


use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="ClientBundle\Repository\LineOrderRepository")
 */
class LineOrder
{
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="ClientBundle\Entity\Basket",inversedBy="lineOrder")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $basket;
	/**
	 * @ORM\Id
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $product;
	/**
	 * @ORM\Column(type="decimal", nullable=false)
	 *
	 */
	private $number;
	/**
	 * @ORM\Column(type="decimal", precision=7, scale=2, nullable=true)
	 *
	 */
	private $price;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->number=1;
		
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
     * Set number
     *
     * @param string $number
     *
     * @return LineOrder
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return LineOrder
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
     * Set product
     *
     * @param \ClientBundle\Entity\AppBundle:Product $product
     *
     * @return LineOrder
     */
    public function setProduct(\AppBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \ClientBundle\Entity\AppBundle:Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set order
     *
     * @param \AppBundle\Entity\Basket $order
     *
     * @return LineOrder
     */
    public function setOrder(\AppBundle\Entity\Basket $order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \AppBundle\Entity\Basket
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set basket
     *
     * @param \ClientBundle\Entity\Basket $basket
     *
     * @return LineOrder
     */
    public function setBasket(\ClientBundle\Entity\Basket $basket)
    {
        $this->basket = $basket;

        return $this;
    }

    /**
     * Get basket
     *
     * @return \ClientBundle\Entity\Basket
     */
    public function getBasket()
    {
        return $this->basket;
    }
}
