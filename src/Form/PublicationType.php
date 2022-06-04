<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

Class PublicationType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
        ->add('text', TextareaType::class, array (
            'label' => 'Publicacion',
            'required' => true,
            'data_class' => null,
            'attr'  =>  [
                'class' =>  'form-control'
            ]
        ))
        ->add('submit', SubmitType::class, array (
            'label' => 'Publicar',
            'attr'  =>  [
                'class' =>  'btn btn-primary btn-user btn-block'
            ]
        ));
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Publication',
        ));
    }
}