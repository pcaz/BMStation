<?php
namespace ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="ClientBundle\Repository\ContactRepository")
 */
class Contact
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(type="string",length=128, nullable=false)
	 *
	 */
	private $name;
	
	/**
	 * @ORM\Column(type="string",length=128, nullable=false)
	 * @Assert\Email()
	 */
	private $email;
	/**
	 * @ORM\Column(type="string",length=16, nullable=true)
	 *
	 */
	private $phone;
	/**
	 * @ORM\Column(type="string",length=128, nullable=false)
	 *
	 */
	private $subject;
	/**
	 * @ORM\Column(type="text", nullable=false)
	 *
	 */
	private $body;
	/**
	 * @ORM\Column(type="datetime", nullable=false)
	 *
	 */
	private $date;
	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 *
	 */
	private $validate;
    /**
     *  getId
     *  
     * @return decimal
     */
	
	
	public function __construct()
	{
		$this->date = new \DateTime();
	}
	
	public function getId() {
		return $this->id;
	}
	/**
	 *  getName
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
    /**
     * setName
     * 
     * @param string $name
     */
	public function setName($name) {
		$this->name = $name;
	}
	/**
	 * getEmail
	 * 
	 * @return string
	 */
	public function getEmail() {
		return $this->email;
	}
	/**
	 * setEmail
	 * 
	 * @param string $email
	 */
	public function setEmail($email) {
		$this->email = $email;
	}
	/**
	 * getSubject
	 * 
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}
	/**
	 * setSubject
	 * 
	 * @param string $subject
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}
	/**
	 * getBody
	 * 
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}
	/**
	 * setBody
	 * 
	 * @param string $body
	 */
	public function setBody($body) {
		$this->body = $body;
	}

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Contact
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set reply
     *
     * @param \DateTime $reply
     *
     * @return Contact
     */
    public function setValidate($reply)
    {
        $this->validate = $reply;

        return $this;
    }

    /**
     * Get reply
     *
     * @return \DateTime
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Contact
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
