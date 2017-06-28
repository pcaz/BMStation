<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class ContainsAlphanumericValidator extends ConstraintValidator
{
	

	public function validate($value, Constraint $constraint)
	{
		$matches='';
	    
		
	    
	     if(isset($value)){
         		$value = $this->remove_accents($value);     	
//     	     	$this->container->get('Utilities')->remove_accents($value);
		        if (!preg_match('/^[a-zA-Z0-9\s.;,()\']+$/', $value, $matches)) 
			    $this->context->buildViolation($constraint->message)
			    ->setParameter('{{ string }}', $value)
			    ->addViolation();
		        return False;
	      }
	      return True;
	}
	
	public function remove_accents($str, $charset='utf-8')
	{
		$str = htmlentities($str, ENT_NOQUOTES, $charset);
	
		$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
		$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
	
		return $str;
	}
	
		
	
}