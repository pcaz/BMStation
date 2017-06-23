<?php
namespace ClientBundle\Validator\Address;


use ClientBundle\Validator\Address\ContainsAddress;
use ClientBundle\Validator\Address\ContainsPostalCode;
use ClientBundle\Validator\Address\ContainsCity;


class ValidateAddress {
	
	public function validate($validator, $Address){
		
		$errors= array();
		$i=0;
		$constraint=new ContainsAddress;
		$val=$validator->validate($Address['address'],$constraint);
		if($val->count()<>0)
		{
			$errors[$i]=$val;
			$i++;
		}
		
		$constraint=new ContainsCity;
		$val=$validator->validate($Address['city'],$constraint);
		if($val->count() <>0){
			$errors[$i]=$val;
			$i++;
		}
		$constraint=new ContainsPostalCode;
		$val=$validator->validate($Address['postalCode'],$constraint);
		if($val->count()<>0){
			$errors[$i]=$val;
			$i++;
		}
		
		
		return $errors;
	}
}