<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModelRepository")
 */

class Model
{
	/**
	 * @ORM\GeneratedValue
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 */

	private $id;
	/**
	 * @ORM\Column(type="string",length=128, nullable=true)
	 */
	 /* @Assert\NotBlank()
	 * @Assert\Length(min=3)
	 */

	private $name;
	/**
	 * @ORM\Column(type="string",length=500, nullable=true)
	 *
	 */

	private $description;

	/**
	 * @ORM\Column(type="string",length=500, nullable=true)
	 *
	 */

	private $comment;

	/**
	 * @ORM\ManyToOne(targetEntity="Series")
	 * @ORM\JoinColumn(name="Series", referencedColumnName="id", nullable=true)
	 * @Assert\NotBlank()
	 */
	
	private $series;
	
	
	/**
	 * @ORM\Column(type="string",length=128, nullable=true)
	 * @Assert\Image(
	 *     minWidth = 200,
	 *     maxWidth = 800,
	 *     minHeight = 200,
	 *     maxHeight = 800
	 *     )
	 */
		
	 private $image;

	 /**
	  * @ORM\Column(type="boolean", nullable=true)
	  *
	  */
	 
	 private $selected;
	 /**
	  * @ORM\ManyToMany(targetEntity="Product",mappedBy="model")
	  * 
	  */
	 private $product;

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
     * @return type
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
     * Set description
     *
     * @param string $description
     * @return type
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return type
     */
    public function setComment($comment)
    {
        $this->commenM = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set series
     *
     * @param \AppBundle\Entity\series $series
     * @return type
     */
    public function setSeries(\AppBundle\Entity\Series $series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Get series
     *
     * @return \AppBundle\Entity\series
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set image
     *
     * @param string $image
     * @return Model
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
    	return $this->image;
    }
   
    
    

    /**
     * Set selected
     *
     * @param boolean $selected
     * @return Model
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Get selected
     *
     * @return boolean 
     */
    public function getSelected()
    {
        return $this->selected;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->product = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add product
     *
     * @param \AppBundle\Entity\Product $product
     * @return Model
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->product[] = $product;

        return $this;
    }

    /**
     * Remove product
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        $this->product->removeElement($product);
    }

    /**
     * Get product
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProduct()
    {
        return $this->product;
    }
}
