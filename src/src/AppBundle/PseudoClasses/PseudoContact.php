<?php
namespace AppBundle\PseudoClasses;

use ClientBundle\Entity\Contact;

class PseudoContact{
	private $id;
	private $name;
	private $email;
	private $phone;
	private $subject;
	private $body;
	private $date;
	private $validate;
	private $client;
	private $old;
	private $today;
	private $delay=30;
	
	
	public function __construct(\ClientBundle\Entity\Contact $contact, $em)
	{
		$this->id=$contact->getId();
		$this->name=$contact->getName();
		$this->email=$contact->getEmail();
		$this->phone=$contact->getPhone();
		$this->subject=$contact->getSubject();
		$this->body=$contact->getBody();
		$this->date=$contact->getDate();
		$this->validate=$contact->getValidate();
		$this->today=new \DateTime();
		
		if($em->getRepository("UserBundle:User")->findByEmail($this->email))
			$this->client=true;
		else 
			$this->client=false;
		$diff=$this->today->diff($this->date);
	    if($diff->d > $this->delay) $this->old=true;
	    else $this->old=false;    	
	    
	    return $this;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function getEmail(){
		return $this->email;
	}
	
	public function getPhone(){
		return $this->phone;
	}
	
	public function getSubject(){
		return $this->subject;
	}
	
	public function getBody(){
		return $this->body;
	}
	
	public function getDate(){
		return $this->date;
	}
	
	public function getValidate(){
		return $this->validate;
	}
	
	public function getClient(){
		return $this->client;
	}
	
	public function getOld(){
		return $this->old;
	}
	
	public function getDelay(){
		return $this->delay;
	}
	
	public function setDetlay($delay) {
		$this->delay=$delay;
	}
	
	public function calculOld(){
		$diff=$this->today->diff($this->date);
		if($diff->d > $this->delay) $this->old=true;
		else $this->old=false;
	}
	
}
