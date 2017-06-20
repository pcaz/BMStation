<?php

namespace ClientBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Adress as MyAssert;
use UserBundle\Entity;

/**
 * @ORM\Entity(repositoryClass="ClientBundle\Repository\ClientRepository")
 */
class Client 
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
	private $firstName;
	
	/**
	 * @ORM\Column(type="string",length=128, nullable=false)
	 *
	 */
	
	private $lastName;
	
	/**
	 * @ORM\OneToOne(targetEntity="Address", cascade={"persist","remove"})
	 * @ORM\JoinColumn(name="deliveryAddress", referencedColumnName="id", nullable=true)
	 */
	
	private $deliveryAddress;
	
	/**
	 *
	 * @ORM\OneToOne(targetEntity="Address", cascade={"persist","remove"})
	 * @ORM\JoinColumn(name="billingAddress", referencedColumnName="id", nullable=true)
	 */
	
	private $billingAddress;
	 
	 /**
	  * @ORM\OneToOne(targetEntity="\UserBundle\Entity\User")
	  * @ORM\JoinColumn(name="user", referencedColumnName="id", nullable=true)
	  */
	private $user;
	 
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
	  * Set firstName
	  *
	  * @param string $firstName
	  * @return User
	  */
	
	 
	 public function setFirstName($firstName)
	 {
	 	$this->firstName = $firstName;
	 
	 	return $this;
	 }
	 
	 /**
	  * Get firstName
	  *
	  * @return string
	  */
	 public function getFirstName()
	 {
	 	return $this->firstName;
	 }
	 
	 /**
	  * Set lastName
	  *
	  * @param string $lastName
	  * @return User
	  */
	 public function setLastName($lastName)
	 {
	 	$this->lastName = $lastName;
	 
	 	return $this;
	 }
	 
	 /**
	  * Get lastName
	  *
	  * @return string
	  */
	 public function getLastName()
	 {
	 	return $this->lastName;
	 }
	 
	 /**
	  * Set setDeliveryAddress
	  *
	  * @param \AppBundle\Entity\Address $address
	  * @return User
	  */
	 public function setDeliveryAddress(\ClientBundle\Entity\Address $address = null)
	 {
	 	$this->deliveryAddress = $address;
	 
	 	return $this;
	 }
	 
	 /**
	  * Get getDeliveryAddress
	  *
	  * @return \ClientBundle\Entity\Address
	  */
	 public function getDeliveryAddress()
	 {
	 	return $this->deliveryAddress;
	 }
	 
	 /**
	  * Get BillingAddress
	  *
	  * @return \ClientBundle\Entity\Address
	  */
	 
	 
	 public function setBillingAddress(\ClientBundle\Entity\Address $address = null)
	 {
	 	$this->billingAddress = $address;
	 
	 	return $this;
	 }
	 
	 /**
	  * getBillingAdress
	  * 
	  * @return \ClientBundle\Entity\Address
	  */
	 
	 public function getBillingAddress()
	 {
	 	return $this->billingAdress;
	 }
	 /**
	  * Set user
	  *
	  * @param \UserBundle\Entity\User $user
	  * @return Client
	  */
	 public function setUser(\UserBundle\Entity\User $user = null)
	 {
	 	$this->user = $user;
	 
	 	return $this;
	 }
	 
	 /**
	  * Get user
	  *
	  * @return \UserBundle\Entity\User
	  */
	 public function getUser()
	 {
	 	return $this->user;
	 }
	/**
	 * 
	 * @param array $user
	 * @return \AppBundle\Entity\Client
	 */
	 
	 public function Hydrate(array $user){
	 	//foreach($user as $item=>$val){
	 	//	$this->$item=$val;
	 	//}
	 	if (isset($user['firstName'])) $this->firstName=$user['firstName'];
	 	if(isset($user['lastName'])) $this->lastName=$user['lastName'];
	 	if(isset($user['deliveryAddress'])) $this->deliveryAddress=$user['deliveryAddress']; 
	 	if(isset($user['billingAddress'])) $this->billingAddress=$user['billingAddress'];
	 	if(isset($user['user'])) $this->user=$user['user'];
	 	
	 	return $this;
	 }
/**
 */	 
	public function toArray(){
	
		$result=array(
				'id'=>$this->id,
				'firstName'=>$this->firstName,
				'lastName'=>$this->lastName,
				'deliveryAddress'=>$this->deliveryAddress->toArray(),
				//'billingAddress'=>$this->billingAddress->Serialize(),
				'user'=>$this->user->toArray(),
				);
		
		return $result;
	}
	

    /**
     * Set phone
     *
     * @param string $phone
     * @return Client
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

    /**
     * Set email
     *
     * @param string $email
     * @return Client
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
     * Set historic
     *
     * @param \ClientBundle\Entity\Historic $historic
     *
     * @return Client
     */
    public function setHistoric(\ClientBundle\Entity\Historic $historic = null)
    {
        $this->historic = $historic;

        return $this;
    }

    /**
     * Get historic
     *
     * @return \ClientBundle\Entity\Historic
     */
    public function getHistoric()
    {
        return $this->historic;
    }
}
