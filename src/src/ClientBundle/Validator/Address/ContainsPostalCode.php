<?php
namespace ClientBundle\Validator\Address;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsPostalCode extends Constraint
{
//	public $message = 'postalcode'.' '."{{ string }}".' '.'invalide';
	public $message = 'invalidpostalcode';
}