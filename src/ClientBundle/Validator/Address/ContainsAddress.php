<?php
namespace ClientBundle\Validator\Address;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsAddress extends Constraint
{
	public $message = 'alphanumericaddress';

}