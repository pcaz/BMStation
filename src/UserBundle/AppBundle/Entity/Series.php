<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as MyAssert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SeriesRepository")
 */
class Series
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
	 */

	private $name;


	/**
	 * @ORM\Column(type="string",length=500, nullable=true)
	 * @MyAssert\ContainsAlphanumeric(message="alphanumeric")
	 *
	 */
	
	private $comment;
	
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
	 * @return model
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
     * @return seriesmodel
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
}
