<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ModelSearchForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('keyword', 'text', array('label' => 'keyword'
				,'required'=>False
		));
	}

	public function getName()
	{
		return 'modelsearch';
	}
}