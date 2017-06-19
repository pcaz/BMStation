<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
;



class EditAdministratorForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('username', 'text', array(
				'label'=>'username', 
				'attr' => array('style' => 'width:300px'),
				'read_only'=>'readonly'))
		->add('email','email', array(
				'label'=>'email',
				'attr' => array('style' => 'width:300px')
		))
		->add('lastLogin', 'datetime', array(
				'widget' => 'single_text',
                'format' => 'dd/MM/yyyy hh:mm',
				'label' => 'lastlogin',
				 'read_only'=>'readonly'
		))
		->add('enabled', 'checkbox', array('label'=>'enabled', 'required'=>false))		
		;
	}

	public function getName()
	{
		return 'adminedit';
	}
}