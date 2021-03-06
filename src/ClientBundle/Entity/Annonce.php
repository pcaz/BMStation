<?php

namespace ClientBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ClientBundle\Validator\Address as MyAssert;

/**
 * @ORM\Entity(repositoryClass="ClientBundle\Repository\AnnonceRepository")
 */
class Annonce
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 *
	 *@ORM\Column(type="string", length=128, unique=true)
	 *
	 */
	private $identifier;
	
	/**
	 *@ORM\ManyToOne(targetEntity="ClientBundle\Entity\Client")
	 *@ORM\JoinColumn(nullable=false)
	 */
	private $client;
	/**
	 *
	 *@ORM\Column(type="string", length=128, nullable=false)
	 *
	 */
	private $name;
	/**
	*@ORM\Column(type="datetime")
	*
	*/
	private $date;
	/**
	 *@ORM\OneToMany(targetEntity="ClientBundle\Entity\Photo", mappedBy="annonce", cascade={"persist", "remove", "merge"})
	 *@ORM\JoinColumn(nullable=true)
	 */
	private $photos;
	/**
	*
	*@ORM\Column(type="string", length=128, nullable=false)
	*
	*/
	private $shortdesc;
	/**
	 *
	 *@ORM\Column(type="string", length=512, nullable=true)
	 *
	 */
	private $longdesc;
	/**
	 * @ORM\Column(type="decimal", precision=7, scale=2, nullable=false)
	 *
	 */
	private $price;
	/**
	 *
	 *@ORM\Column(type="string", length=32, nullable=true)
	 *
	 */
	private $email;
	/**
	 *
	 *@ORM\Column(type="string", length=16, nullable=true)
	 *
	 */
	private $phone;
      
        
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->photos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set identifier
     *
     * @param string $identifier
     *
     * @return Annonce
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Annonce
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set shortdesc
     *
     * @param string $shortdesc
     *
     * @return Annonce
     */
    public function setShortdesc($shortdesc)
    {
        $this->shortdesc = $shortdesc;

        return $this;
    }

    /**
     * Get shortdesc
     *
     * @return string
     */
    public function getShortdesc()
    {
        return $this->shortdesc;
    }

    /**
     * Set longdesc
     *
     * @param string $longdesc
     *
     * @return Annonce
     */
    public function setLongdesc($longdesc)
    {
        $this->longdesc = $longdesc;

        return $this;
    }

    /**
     * Get longdesc
     *
     * @return string
     */
    public function getLongdesc()
    {
        return $this->longdesc;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Annonce
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
     * Set client
     *
     * @param \ClientBundle\Entity\Client $client
     *
     * @return Annonce
     */
    public function setClient(\ClientBundle\Entity\Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \ClientBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Add photo
     *
     * @param \ClientBundle\Entity\Photo $photo
     *
     * @return Annonce
     */
    public function addPhoto(\ClientBundle\Entity\Photo $photo)
    {
        $this->photos[] = $photo;

        return $this;
    }

    /**
     * Remove photo
     *
     * @param \ClientBundle\Entity\Photo $photo
     */
    public function removePhoto(\ClientBundle\Entity\Photo $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Annonce
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
     * Set email
     *
     * @param string $email
     *
     * @return Annonce
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
     * Set phone
     *
     * @param string $phone
     *
     * @return Annonce
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
}
