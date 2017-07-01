<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 


class AddSlideshowForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('image', FileType::class, array('data_class'=>null,'label' => 'image'))
		->add('rang', IntegerType::class, array('label'=>null))
		;
	}

	public function getName()
	{
		return 'slideshow';
	}
}