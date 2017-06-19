<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Form\Common;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProductForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('name','text',array('label'=> 'name'))
		->add('description', 'textarea', array('label'=>'description', 'required'=>false))
		->add('comment', 'textarea', array('label'=>'comment','required'=>false))
		->add('price', 'text', array('label'=>'price'))
		->add('disponibility', 'checkbox', array('label'=>'disponibility','required'=>false))
		->add('model', new  Common\SeriesModelForm(), array('required'=>false))
		->add('image', FileType::class, array('data_class'=>null,'label' => 'image', 'required' => False))
//		->add('category', 'entity',array(
//				'class' => 'AppBundle\Entity\Category',
//				'property' => 'id',))
        				;
	}

/*	public function getName()
	{
		return 'product';
	}
*/
}

