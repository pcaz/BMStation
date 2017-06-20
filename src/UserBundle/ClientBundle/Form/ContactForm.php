<?php

namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class ContactForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text',array('label'=>'name'));
		$builder->add('email', 'email',array('label'=>'email'));
		$builder->add('phone', 'text',array('label'=>'phone', 'required'=>false));
		$builder->add('subject','text',array('label'=>'subject'));
		$builder->add('body', 'textarea',array('label'=>'body'));
		$builder->add('captcha', 'captcha',array('label'=>"Vous n'êtes pas un robot ?")); 
	}

	public function getName()
	{
		return 'contact';
	}
}