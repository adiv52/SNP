<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

Class RegisterType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('name', TextType::class, array (
            'attr'  =>  [
                'class' =>  'form-control form-control-user',
                'placeholder' => 'Nombre'
            ]
        ))
        ->add('surname', TextType::class, array (
            'attr'  =>  [
                'class' =>  'form-control form-control-user',
                'placeholder' => 'Apellido'
            ]
        ))
        ->add('nick', TextType::class, array (
            'attr'  =>  [
                'class' =>  'form-control form-control-user',
                'placeholder' => 'NickName'
            ]
        ))
        ->add('email', EmailType::class, array (
            'attr'  =>  [
                'class' =>  'form-control form-control-user',
                'placeholder' => 'Email'
            ]
        ))
        ->add('password', PasswordType::class, array (
            'attr'  =>  [
                'class' =>  'form-control form-control-user',
                'placeholder' => 'Contraseña'
            ]
        ))
        ->add('submit', SubmitType::class, array (
            'attr'  =>  [
                'class' =>  'btn btn-primary btn-user btn-block',
                'label' => 'Enviar'
            ]
        ));
    }
}
