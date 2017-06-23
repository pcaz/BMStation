<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;




class ModelForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('series', 'entity', array(
				'class' => 'AppBundle\Entity\Series',
				'property' => 'name',
        		'expanded' => false,
				'multiple' => false,
				'required' => false,
				'empty_data'=>null,
				'placeholder'=> 'series_input',
				'label'=> 'series'))
		->add('name', 'text', array('label'=>'name'))
		->add('description', 'textarea', array('label'=>'description', 'required'=>false))
		->add('comment','textarea', array('label'=>'comment', 'required'=>False))
		;
	}

	public function getName()
	{
		return 'model';
	}
}