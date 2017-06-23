<?php
namespace AppBundle\Form\Common;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;





class SeriesModelForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		$builder

		->add ('nameSeries', 'entity', array(
				'class' => 'AppBundle:Series',
//				'property' => 'name',
				'choice_label' => 'name',
				'expanded' => true,
				'multiple' => false,
				'required' => true,
				'empty_data'=>null,
				'placeholder'=> 'series_input',
				'attr'=> array("onclick"=>"selectSeries(this)")
				))
		->add('nameModel', EntityType::class, array(
 		    	'class' => 'AppBundle:Model',
//				'property' => 'name',
				'choice_label' => 'name',
				'expanded' => false,
				'multiple' => true,
				'required' => true,
//				'empty_data'=>null,
			))
		;
	

	  }

	public function getName()
	{
		return 'SeriesModel';
	}
}