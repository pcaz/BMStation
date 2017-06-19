<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class CategoryForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('name', TextType::class, array('label'=>'name'))
		->add('comment',TextareaType::class, array('label'=>'comment', 'required'=>False))
		->add('image', FileType::class, array('data_class'=>null,'label' => 'image', 'required' => False))
		;
	}

	public function getName()
	{
		return 'category';
	}
}