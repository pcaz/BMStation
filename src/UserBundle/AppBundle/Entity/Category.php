<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as MyAssert;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */

class Category 
{
	/**
	 * @ORM\GeneratedValue
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 */
	
	private $id;
	/**
	 * @ORM\Column(type="string",length=128)
	 * @Assert\NotBlank()
	 * @Assert\Length(min=3)
	 * @MyAssert\ContainsAlphanumeric(message="alphanumeric")
	 */
	
	private $name;
	
	/**
	* @ORM\Column(type="string",length=500, nullable=true)
	* @MyAssert\ContainsAlphanumeric(message="alphanumeric")
	*
	*/
	
	private $comment;
	
	

     /**
     *@ORM\Column(type="string",length=128, nullable=true) 
	 * @Assert\Image(
	 *     minWidth = 200,
	 *     maxWidth = 800,
	 *     minHeight = 200,
	 *     maxHeight = 800
	 * )
	
	 */
	private $image;
	
	/**
	 * @ORM\Column
	 */
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
     * @return productCategory
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
     * Set comment
     *
     * @param string $comment
     * @return product_category
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

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
     * Set image
     *
     * @param string $image
     * @return product_category
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
    
   }
