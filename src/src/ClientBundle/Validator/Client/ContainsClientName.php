<?php
namespace ClientBundle\Validator\Client;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsClientName extends Constraint
{
	public $message = 'invalidclientname';

}