<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;



class AdministratorForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('username', 'text', array('label'=>'username'))
		->add('email', 'email', array('label'=>'email'))
		->add('password', 'password', array('label'=>'password'))
		;
	}

	public function getName()
	{
		return 'administrator';
	}
}