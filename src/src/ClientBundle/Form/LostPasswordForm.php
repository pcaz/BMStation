<?php

namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class LostPasswordForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('email', 'email',array(
				'label'=>'email',
				'attr' => array(
						'style' => 'width:300px')
				
				));
	
	}

	public function getName()
	{
		return 'lostpassword';
	}
}