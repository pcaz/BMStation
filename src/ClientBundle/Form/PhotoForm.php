<?php
namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhotoForm extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('picture', FileType::class, array('data_class'=>null,'label' => 'picture', 'required' => False))
		
		;
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class'         => '\ClientBundle\Entity\Photo',
		));
	}
	
	
	
	public function getName()
	{
		
		return 'photo';
		
	}
	
	
}