<?php
namespace ClientBundle\Validator\Address;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsAddressLine extends Constraint
{
	public $message = 'alphanumericaddress';

}