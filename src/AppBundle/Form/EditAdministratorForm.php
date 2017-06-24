<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;



class EditAdministratorForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('username', 'text', array(
				'label'=>'username', 
				'attr' => array(
					'style' => 'width:300px'),
					'read_only'=>'readonly',
					'required'=>false,
		 ))
		->add('email','email', array(
				'label'=>'email',
				'required'=> false,
				'attr' => array(
						'style' => 'width:300px',
					
				)
		))
		->add('password', PasswordType::class, array(
				'label'=>'pasword',
				'required'=>false,
		))
		->add('lastLogin', 'datetime', array(
				'widget' => 'single_text',
                'format' => 'dd/MM/yyyy hh:mm',
				'label' => 'lastlogin',
				 'read_only'=>'readonly',
                 'required'=> false,				
		))
		->add('enabled', 'checkbox', array('label'=>'enabled', 'required'=>false))		
		;
	}

	public function getName()
	{
		return 'adminedit';
	}
}