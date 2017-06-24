<?php
namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class NameBasketForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('name', TextType::class,array(
				'label'=>'name'
				));
		
		
	}
	
	
	public function getName()
	{
		return 'namebasket';
	}
}