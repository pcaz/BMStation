<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class ContainsMoneyValidator extends ConstraintValidator
{


	public function validate($value, Constraint $constraint)
	{
	


		if(isset($value)){
				//     	     	$this->container->get('Utilities')->remove_accents($value);
			if(!preg_match('/^[0-9]+\.?\,?\d{1,2}$/',$value)){
				$this->context->buildViolation($constraint->message)
				->setParameter('{{ string }}', $value)
				->addViolation();}
				return False;
		}
		return True;
	}


}