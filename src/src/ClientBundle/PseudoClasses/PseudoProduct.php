<?php
namespace ClientBundle\PseudoClasses;

use AppBundle\Entity\Product;

class PseudoProduct extends Product{
	
	private $command;
	
	public function __construct(\AppBundle\Entity\Product $product, $em)
	{
		$this->id=$product->getId();
		$this->name=$product->getName();
		$this->descrpition=$product->getDescription();
		$this->comment=$product->getDescription();
		$this->price=$product->getPrice();
		$this->disponibility=$product->getDisponibility();
		$this->image=$product->getImage();
		$this->model=$product->getModel();
		$this->command=0;
		
	}

	public function getCommand()
	{
		return $this->command;
	}
	
	public function setCommand($command)
	{
		$this->command=$command;
	}
}
	