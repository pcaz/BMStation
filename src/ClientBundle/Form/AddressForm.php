<?php
namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddressForm extends AbstractType
{

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
				->add('address', TextType::class, array('label'=>'address'))
				->add('city', TextType::class, array('label'=>'city'))
				->add('postalCode',TextType::class, array('label'=>'postalcode'))
                                ->add ('phone', TextType::class, array('label'=>'phone'))    
				->add('email',TextType::class, array('label'=>'email'))
	
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