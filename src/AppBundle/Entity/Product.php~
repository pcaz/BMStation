<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;



/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */

class Product
{
	/**
	 * @ORM\GeneratedValue
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 */

	private $id;
	/**
	 * @ORM\Column(type="string",length=128)
	 * @Assert\NotBlank(message="Name must not be blank")
	 * @Assert\Length(min=3)
	 */

	private $name;
	/**
	 * @ORM\Column(type="string",length=500, nullable=true)
	 * 
	 *
	 */
	
	private $description;
	
	/**
	 * @ORM\Column(type="string",length=500, nullable=true)
	 *
	 */
	
	private $comment;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Category")
	 * @ORM\JoinColumn(name="category", referencedColumnName="id", nullable=false)
	 **/
	 /*@Assert\NotBlank(message="Category must not be blank")
	 * 
	 */
	private $category;
	/**
	 * @ORM\Column(type="decimal", precision=7, scale=2)
	 * 
	 */

	 private $price;
	 
	 /**
	  * @ORM\Column(type="integer")
	  * 
	  */
	 
	 private $disponibility;
	 
	 /**
	  * @ORM\Column(type="integer", nullable=true)
	  *
	  */
	 
	 private $promo;
	
	 /**
	  *@ORM\Column(type="string",length=128, nullable=true)
	  *  
	  *@Assert\Image(
      *     maxSize = "1000000",
      *     mimeTypesMessage = "Votre fichier n'est apparemment pas une photo...",
      *     maxSizeMessage = "Votre fichier est trop gros ({{ size }}). La taille maximum autorisée est : {{ limit }}"
	  *     )
	  */
	 private $image;
	 
	 /**
	 * @ORM\ManyToMany(targetEntity="Model",inversedBy="product")
	 * @ORM\JoinColumn(name="Model", referencedColumnName="id", nullable=true)
	 */
	private $model;
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->model = new ArrayCollection();
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
     * @return product_type
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
     * Set category
     *
     * @param \ProductBundle\Entity\Category $category
     * @return product_type
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \ProductBundle\Entity\Category 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return product_type
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
     * Set description
     *
     * @param string $description
     * @return product_type
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
     * Add model
     *
     * @param \AppBundle\Entity\model $model
     * @return product
     */
    public function addModel(\AppBundle\Entity\Model $model)
    {
        $this->model[] = $model;

        return $this;
    }

    /**
     * Remove model
     *
     * @param \AppBundle\Entity\model $model
     */
    public function removeModel(\AppBundle\Entity\Model $model)
    {
        $this->model->removeElement($model);
    }

    /**
     * Get model
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModel()
    {
        return $this->model;
    }
    
    public function countModel()
    {
    	return $this->model->count();
    }
    /**
     * Set price
     *
     * @param string $price
     * @return product
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
     * Set disponibility
     *
     * @param boolean $disponibility
     * @return product
     */
    public function setDisponibility($disponibility)
    {
        $this->disponibility = $disponibility;

        return $this;
    }

    /**
     * Get disponibility
     *
     * @return boolean 
     */
    public function getDisponibility(){
        return $this->disponibility;
    }
	
	public function Hydrate(array $val){
		
		if(isset($val['name'])) $this->name=$val['name']; else $this->name="";
		if(isset($val['category'])) $this->category= $val['category']; else $this->category =NULL;
		if(isset($val['description'])) $this->description=$val['description']; else $this->description="";
		if(isset($val['comment'])) $this->comment=$val['comment']; else $this->comment="";
		if(isset($val['price'])) $this->price=$this->price=str_replace(',','.',$val['price']); else $this->price="";
		if(isset($val['disponibility'])) $this->disponibility=$val['disponibility']; else $this->disponibility=1;
		if(isset($val['model'])) {
			$models=$val['model']['nameModel'];
			foreach($models as $mods){
				$this->addModel($mods);
			}

		}
		return $this;
		}
		
		public function getselected(){
		
		}
		public function getOption(){
		
	}

    /**
     * Set image
     *
     * @param string $image
     * @return Product
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
    
    public function removeAllModels(){
    	$this->model=new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function toArray(){
    	$result=array(
    			'category'=>$this->category,
    			'name'=>$this->name,
    			'description'=>$this->description,
    			'comment'=>$this->comment,
    			'price'=>$this->price,
    			'disponibility'=>$this->disponibility,
    			'image'=>$this->image,
    			'model'=>$this->model->toArray(),
    			);
    	return $result;
    }

    /**
     * Set promo
     *
     * @param string $promo
     *
     * @return Product
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get promo
     *
     * @return string
     */
    public function getPromo()
    {
        return $this->promo;
    }
}
