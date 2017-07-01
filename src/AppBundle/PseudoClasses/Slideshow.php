<?php

namespace AppBundle\PseudoClasses;


class Slideshow {
	
	
	private $image;
	private $rang;
	
	public function getImage()
	{
		return $this->image;
	}
	
	public function setImage($image)
	{
		$this->image=$image;
	}
	
	public function getRang()
	{
		return $this->rang;
	}
	
	public function setRang($rang)
	{
		$this->rang=$rang;
	}
}


