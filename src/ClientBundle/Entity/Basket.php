<?php



namespace ClientBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="ClientBundle\Repository\BasketRepository")
 */
class Basket
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\ManyToOne(targetEntity="ClientBundle\Entity\Client")
     * @ORM\JoinColumn(nullable=false)
    */
	private $client;
	/**
     * @ORM\Column(type="string",length=128, nullable=true)
	 * 
	 */
	private $name;
	/**
	 * @ORM\OneToMany(targetEntity="ClientBundle\Entity\LineOrder", mappedBy="basket")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $lineOrder;
	/**
	 *@ORM\Column(type="datetime");
	 */
	
	private $dateCreated;
	/**
	 * @ORM\Column(type="boolean")
	 *
	 */
	private $active;
	
	/**
	 *@ORM\Column(type="datetime", nullable=true);
	 */
	
	private $dateOrdered;
	/**
	 * @ORM\Column(type="string",length=128, nullable=true)
	 *
	 */
	private $facture;
	
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lineOrder = new \Doctrine\Common\Collections\ArrayCollection();
        $this->active = false;
        $this->dateCreated=new \DateTime();
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
     * Set facture
     *
     * @param string $facture
     *
     * @return Order
     */
    public function setFacture($facture)
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * Get facture
     *
     * @return string
     */
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * Set client
     *
     * @param \ClientBundle\Entity\ClientBundle:Client $client
     *
     * @return Order
     */
    public function setClient(\ClientBundle\Entity\Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \ClientBundle\Entity\ClientBundle:Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Add lineOrder
     *
     * @param \ClientBundle\Entity\ClientBundle:LineOrder $lineOrder
     *
     * @return Order
     */
    public function addLineOrder(\ClientBundle\Entity\LineOrder $lineOrder)
    {
        $this->lineOrder[] = $lineOrder;

        return $this;
    }

    /**
     * Remove lineOrder
     *
     * @param \ClientBundle\Entity\ClientBundle:LineOrder $lineOrder
     */
    public function removeLineOrder(\ClientBundle\Entity\LineOrder $lineOrder)
    {
        $this->lineOrder->removeElement($lineOrder);
    }

    /**
     * Get lineOrder
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLineOrder()
    {
        return $this->lineOrder;
    }

    /**
     * Set datecreated
     *
     * @param \DateTime $datecreated
     *
     * @return Neworder
     */
    public function setDateCreated($datecreated)
    {
        $this->datecreated = $dateCreated;

        return $this;
    }

    /**
     * Get datecreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Neworder
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set dateordered
     *
     * @param \DateTime $dateordered
     *
     * @return Neworder
     */
    public function setDateordered($dateordered)
    {
        $this->dateordered = $dateordered;

        return $this;
    }

    /**
     * Get dateordered
     *
     * @return \DateTime
     */
    public function getDateordered()
    {
        return $this->dateordered;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Basket
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
}
