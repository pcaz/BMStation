<?php
namespace AppBundle\Validator\Adress;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Service\Utilities;

class ContainsPostalCodeValidator extends ConstraintValidator
{


	public function validate($value, Constraint $constraint)
	{
		
		if(isset($value)){
			
				$viol=$this->context->buildViolation($constraint->message)
				->addViolation();
				
		return False;
		}
		return TRUE;
		
	
	}
}