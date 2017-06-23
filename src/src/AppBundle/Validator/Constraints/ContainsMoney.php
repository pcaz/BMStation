<?php
namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsMoney extends Constraint
{
	public $message = 'The string "{{ string }}" is not a legal money type.';

}