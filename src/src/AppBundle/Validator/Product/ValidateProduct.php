<?php
namespace AppBundle\Validator\Product;


use AppBundle\Validator\Constraints\ContainsMoney;



class ValidateProduct {
	
	public function validate($validator, $Product, &$FlashBag){
		
		$errors=0;
		
		$constraint=new ContainsMoney;
		$error=$validator->validate($Product['price'],$constraint);
		if (count($error)>0){
			$FlashBag->add('error','Le format du prix est invalide.');
			$errors++;
		}
		
		return ($errors==0);
	}
}