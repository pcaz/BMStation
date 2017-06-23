<?php
namespace ClientBundle\Validator\Client;


use ClientBundle\Validator\Client\ContainsClientName;


class ValidateClientName {

	public function validate($validator, $Client){

		$errors= array();
		$i=0;

		$constraint=new ContainsClientName;
		$val=$validator->validate($Client['firstName'],$constraint);
		if($val->count()<>0)
		{
			$errors[$i]=$val;
			$i++;
		}

		$val=$validator->validate($Client['lastName'],$constraint);
		if($val->count() <>0){
			$errors[$i]=$val;
			$i++;
		}
		


		return $errors;
	}
}