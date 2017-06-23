<?php
namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use UserBundle\Form\Type\RegistrationFormType;



class ClientForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
				->add('firstName', 'text', array('label'=>'firstname'))
				->add('lastName','text',array('label'=>'lastname'))
				->add('deliveryAddress', new AddressForm(),array('label'=>'deliveryaddress'))
				->add('user', new RegistrationFormType('UserBundle\Entity\User'))
		
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
			return 'client';
		}

}