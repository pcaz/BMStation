<?php
namespace ClientBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;





class UserSeriesModelForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		$builder

		->add ('name', 'entity', array(
				'class' => 'AppBundle:Series',
				'property' => 'name',
				'expanded' => true,
				'multiple' => false,
				'required' => true,
				'empty_data'=>null,
				'placeholder'=> 'series_input',
				'attr'=> array("onclick"=>"selectSeries(this)")
				))
		->add('name', EntityType::class, array(
 		    	'class' => 'AppBundle:Model',
				'property' => 'name',
				'choice_label' => 'name',
     			'attr'=> array("onchange"=>"selectModel(this)")
));
	

	  }

	public function getName()
	{
		return 'userSeriesModel';
	}
}