<?php
namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddressForm extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
				->add('address', 'text', array('label'=>'address'))
				->add('city', 'text', array('label'=>'city'))
				->add('postalCode','text', array('label'=>'postalcode'))
				->add('phone','text', array('label'=>'phone','required'=>false))
//				->add('email','text', array('label'=>'email'))
	
				;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class'         => '\ClientBundle\Entity\Address',
		));
	}
	
	
		
	public function getName()
	{
		
		return 'address';

	}
	

}