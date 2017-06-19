<?php

namespace ClientBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use ClientBundle\Validator\Address as MyAssert;

/**
 * @ORM\Entity(repositoryClass="ClientBundle\Repository\AddressRepository")
 */
 class Address
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string",length=255)
	
	 */
	private $address;
	/**
	 * @ORM\Column(type="decimal", precision=2, nullable=true)
	 *
	 */
	
	private $countryCode;
	/**
	 * @ORM\Column(type="string", length=5)
	 * @MyAssert\ContainsPostalCode(message="postalcode")
	 * @Assert\Length(min=5)
	 * @Assert\NotBlank()
	 *
	 */
	
	private $postalCode;

	/**
	 * @ORM\Column(type="string",length=128)
	
	 */
	
	private $city;

	/**
	 * @ORM\Column(type="string",length=128, nullable=true)
	 *
	 */
	private $phone;
	/**
	 * @ORM\Column(type="string",length=128, nullable=true)
	 *
	 */
	private $email;
	

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
	 * Set countryCode
	 *
	 * @param string $countryCode
	 * @return address
	 */
	public function setCountryCode($countryCode)
	{
		$this->countryCode = $countryCode;
	
		return $this;
	}
	
	/**
	 * Get countryCode
	 *
	 * @return string
	 */
	public function getCountryCode()
	{
		return $this->countryCode;
	}
	
	/**
	 * Set postalCode
	 *
	 * @param string $postalCode
	 * @return address
	 */
	public function setPostalCode($postalCode)
	{
		$this->postalCode = $postalCode;
	
		return $this;
	}
	
	/**
	 * Get postalCode
	 *
	 * @return string
	 */
	public function getPostalCode()
	{
		return $this->postalCode;
	}
	
	/**
	 * Set city
	 *
	 * @param string $city
	 * @return address
	 */
	public function setCity($city)
	{
		$this->city = $city;
	
		return $this;
	}
	
	/**
	 * Get city
	 *
	 * @return string
	 */
	public function getCity()
	{
		return $this->city;
	}
	
	/**
	 * Set address
	 *
	 * @param string $address
	 * @return address
	 */
	public function setAddress($address)
	{
		$this->address = $address;
	
		return $this;
	}
	
	/**
	 * Get address
	 *
	 * @return string
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * Set phone
	 *
	 * @param string $phone
	 * @return address
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
	 * @return address
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
	 * 
	 * @param array $address
	 * @return \AppBundle\Entity\address
	 */
    
    
    public function Hydrate(array $address){
    	/**
    	 * Todo: vérifier la cohérence de information passée
    	 */
//    	foreach($address as $item=>$val){
//    		$this->$item=$val;   	
//    }
	if (isset($address['countryCode'])) $this->countryCode=$address['countryCode'];
	if(isset($address['address'])) $this->address=$address['address'];
	if(isset($address['city'])) $this->city=$address['city'];
	if(isset($address['postalCode'])) $this->postalCode=$address['postalCode'];
	if(isset($address['phone'])) $this->phone=$address['phone'];
	if(isset($address['email'])) $this->email=$address['email'];
   	
    	return $this;
    }
   
    /**
     * 
     * @return string[]|array[]
     */
    
    public function toArray(){
    	
    	$result=array(
    			'countryCode'=> $this->countryCode,
    			'address'=>$this->address,
    			'city'=>$this->city,
    			'postalCode'=>$this->postalCode,
    			'phone'=>$this->phone,
    			'email'=>$this->email,
    			);
    	
    	return $result;			
    }
    
    public function serialize(){
  	
    $val=serialize(array(
    		$this->id,
    		$this->address,
    		$this->city,
    		$this->postalcode,
    		$this->phone,
    		$this->email
    		
    ));
    return $val;
    }
 }
