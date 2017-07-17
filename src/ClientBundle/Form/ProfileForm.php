<?php
namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;



class ProfileForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add ('gender',ChoiceType::class, array(
				'choices'  => array(
						'0'=>'male',
						'1'=>'female',
				),'label'=>'gender'
				))
		->add('firstName', TextType::class, array('label'=>'firstname'))
		->add('lastName',TextType::class,array('label'=>'lastname'))
		->add('deliveryAddress', new AddressForm(),array('label'=>'deliveryaddress',))
		;
				
		}
		
		public function setDefaultOptions(OptionsResolverInterface $resolver)
		{
			$resolver->setDefaults(array(
					'class'         => '\ClientBundle\Entity\Client',
			));
		}
		
			
		
		public function getName()
		{
			return 'profile';
		}

}
