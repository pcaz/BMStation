<?php
namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         
        $builder
            ->remove('username')
/* a ajouter si besoin            ->add('question',TextType::class, array('label'=>'question'))
            ->add('answer',TextType::class, array('label'=>'answer'))
*/
                ;
    }
     public function getParent()
    {
        return BaseRegistrationFormType::class;
    }
}
