<?php
namespace ClientBundle\Validator\Address;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsCity extends Constraint
{
	public $message = 'invalidcityname';

}