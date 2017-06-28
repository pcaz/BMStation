<?php
namespace AppBundle\PseudoClasses;

use ClientBundle\Entity\Contact;

class PseudoContact extends Contact{
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
