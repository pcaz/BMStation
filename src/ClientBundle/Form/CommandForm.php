<?php
namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class CommandForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('name', TextType::class, array('label'=>'name', 'required'=>false, 'readonly'=>'readonly'))
		->add('description',TextareaType::class, array('label'=>'description', 'readonly'=>'readonly', 'required'=>false))
		->add('comment',TextareaType::class, array('label'=>'comment', 'readonly'=>'readonly','required'=>false))
		->add('price',TextareaType::class, array('label'=>'price', 'readonly'=>'readonly','required'=>false))
		->add('disponibility',TextareaType::class, array('label'=>'disponibility', 'readonly'=>'readonly', 'required'=>false))
		->add('image', FileType::class, array('data_class'=>null,'label' => 'image', 'readonly'=>'readonly', 'required' => false))
		->add('command',TextareaType::class, array('label'=>'command', 'required'=>false))
		;
	}
	
	public function getName()
	{
		return 'command';
	}
}