<?php
namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;




class AddressForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
				->add('ligneAdresse1', 'text', array('label'=>'Addresse'))
				->add('ligneAdresse2', 'text', array('label'=>'','required'=>false))
				->add('ligneAdresse3', 'text', array('label'=>'','required'=>false))
			    ->add('voie','text', array('label'=>'voie'))
			    ->add('commune', 'text', array('label'=>'commune'))
				->add('codePostal','text', array('label'=>'code postal'))
	
				;
	}

	public function getName()
	{
		return 'address';
	}
}