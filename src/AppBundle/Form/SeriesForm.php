<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SeriesForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
		->add('name', 'text', array(
				'label'=>'name'))
		->add('comment','textarea',array(
				'label'=>'comment'
		));
	}
		public function getName()
		{
			return 'series';
		}
}