<?php
namespace ClientBundle\Validator\Address;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsAddressLineValidator extends ConstraintValidator
{


	public function validate($value, Constraint $constraint)
	{


		if(isset($value) && $value <> ""){
			$value = $this->remove_accents($value);
			//     	     	
			if (!preg_match('/^[a-zA-Z0-9\s.;,()\']+$/', $value)){
				$this->context->buildViolation($constraint->message)
				->addViolation();
				return False;
			}
		return True;
		}
	}



	public function remove_accents($str, $charset='utf-8')
	{
		$str = htmlentities($str, ENT_NOQUOTES, $charset);
	
		$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
//		$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
	
		return $str;
	}
	
}