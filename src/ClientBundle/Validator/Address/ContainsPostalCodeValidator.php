<?php
namespace ClientBundle\Validator\Address;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use AppBundle\Service\Utilities;

class ContainsPostalCodeValidator extends ConstraintValidator
{


	public function validate($value, Constraint $constraint)
	{
		
		if(isset($value)){
				if(strlen($value)<>5 ||!preg_match('/[0-9]/', $value)){			
					$this->context->buildViolation($constraint->message)
					->setParameter('{{ string }}', $value)
					->addViolation();
					return FALSE;
				}
				
		return TRUE;
		}
	}
		
	

}