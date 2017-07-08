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
		->add('name', TextType::class, array(
				'label'=>'annonce.name',
				'label_attr'=>array(
				'id'=>'label_annonce_name',
		)))
		->add('shortdesc',TextType::class,array(
				'label'=>'annonce.shortdesc',
				'label_attr'=>array(
						'id'=>'label_annonce_shortdesc',
				)))
		->add('longdesc',TextareaType::class, array(
				'label'=>'annonce.longdesc', 
				'required'=>false,
				'label_attr'=>array(
						'id'=>'label_annonce_longdesc',
				)))
		->add( 'visiblecontact',CheckboxType::class, array(
				'mapped'=>false, 
				'label'=>'annonce.visible',
				'label_attr'=>array(
						'id'=>'label_annonce_visiblecontact',
						)))		
		->add ('typecontact',ChoiceType::class, array(
						'choices'  => array(
								'1'=>'phone',
								'2'=>'email',
						),
				'label'=>'annonce.typecontact',
				'label_attr'=>array(
						'id'=>'label_annonce_typecontact')))
        ->add('contact',TextType::class, array(
        		'label'=>'annonce.contact',
        		'label_attr'=>array(
        		'id'=>'label_annonce_contact',)))
				;
       
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