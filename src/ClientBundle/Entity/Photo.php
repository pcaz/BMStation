<?php
namespace ClientBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ClientBundle\Validator\Address as MyAssert;

/**
 * @ORM\Entity(repositoryClass="ClientBundle\Repository\PhotoRepository")
 */
class Photo
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;	
	/**
	 *@ORM\Column(type="string",length=128)
	 *
	 *@Assert\Image(
	 *     maxSize = "1000000",
	 *     mimeTypesMessage = "Votre fichier n'est apparemment pas une photo...",
	 *     maxSizeMessage = "Votre fichier est trop gros ({{ size }}). La taille maximum autorisée est : {{ limit }}"
	 *     )
	 */
	private $picture;
	/**
	 *@ORM\Column(type="integer", nullable=true)
	 */
	private $width;
	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $height;
	/**
	 * @ORM\Column(type="string",length=128, nullable=true)
	 *
	 *
	 */	
	private $name;
	
	/**
	 * @ORM\ManyToOne(targetEntity="ClientBundle\Entity\Annonce")
	 */
	private $annonce;
	
	

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
     * Set picture
     *
     * @param string $picture
     *
     * @return Photo
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set width
     *
     * @param integer $width
     *
     * @return Photo
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return Photo
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Photo
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
     * Set annonce
     *
     * @param \ClientBundle\Entity\Annonce $annonce
     *
     * @return Photo
     */
    public function setAnnonce(\ClientBundle\Entity\Annonce $annonce = null)
    {
        $this->annonce = $annonce;

        return $this;
    }

    /**
     * Get annonce
     *
     * @return \ClientBundle\Entity\Annonce
     */
    public function getAnnonce()
    {
        return $this->annonce;
    }
}
