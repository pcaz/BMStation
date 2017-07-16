<?php

namespace ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use ClientBundle\Entity\Photo;




class AnnonceForm extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
                ->add('identifier', TextType::class, array(
                    'label'=>'annonce.identifier',
                    'read_only'=>true,
                    'required'=> false,
                    'label_attr'=>array(
                        'id'=>'label_annnonce_identifier',)
                ))        
		->add('name', TextType::class, array(
				'label'=>'annonce.name',
				'label_attr'=>array(
				'id'=>'label_annonce_name',
		)))
		->add('shortdesc',TextType::class,array(
				'label'=>'annonce.shortdesc',
				'label_attr'=>array(
					'id'=>'label_annonce_shortdesc',
				),
                               
                    ))
		->add('longdesc',TextareaType::class, array(
				'label'=>'annonce.longdesc', 
				'required'=>false,
				'label_attr'=>array(
						'id'=>'label_annonce_longdesc',
				)))
                ->add('price', 'text', array(
                    'label'=>'price'))     
		->add ('email', TextType::class, array(
                                'label'=>'annonce.email',
                                'label_attr'=> array(
                                            'id'=>'label_annnonce_email',
						),
				))
                ->add('phone',TextType::class, array(
        		'label'=>'annonce.phone',
                        'required'=>false,
        		'label_attr'=>array(
        		'id'=>'label_annonce_phone',)))
/*        ->add('photo1', 'collection', array(
        		    'type' => new PhotoForm1(),
        		    'allow_add' => true,
        		    'allow_delete' => true,
                    'mapped' => false,
        		))
*/				;
       
 //       $builder->add('photos', CollectionType::class, array(  ));
       
}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'class'         => '\ClientBundle\Entity\Annonce',
		));

	}
	
	
	
	public function getName()
	{
		return 'annonce';
	}
	
}