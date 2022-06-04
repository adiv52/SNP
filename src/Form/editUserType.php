<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

Class editUserType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder->add('name', TextType::class, array (
            'label' => 'Nombre',
            'attr'  =>  [
                'class' =>  'form-control form-control-user'
            ]
        ))
        ->add('surname', TextType::class, array (
            'label' => 'Apellidos',
            'attr'  =>  [
                'class' =>  'form-control form-control-user'
            ]
        ))
        ->add('nick', TextType::class, array (
            'label' => 'Nombre de Usuario',
            'attr'  =>  [
                'class' =>  'form-control form-control-user'
            ]
        ))
        ->add('email', EmailType::class, array (
            'label' => 'Correo Electronico',
            'required' => false,
            'attr'  =>  [
                'class' =>  'form-control form-control-user'
            ]
        ))
        ->add('bio', TextareaType::class, array (
            'label' => 'Biografia',
            'required' => false,
            'data_class' => null,
            'attr'  =>  [
                'class' =>  'form-control form-control-user'
            ]
        ))
        ->add('imageFile', VichImageType::class, [
            'mapped'    => false,
            'required' => false,
            'allow_delete' => true,
            'download_label' => '...',
            'download_uri' => true,
            'image_uri' => true,
            'asset_helper' => true,
        ])
        ->add('submit', SubmitType::class, array (
            'label' => 'Guardar',
            'attr'  =>  [
                'class' =>  'btn btn-primary btn-user btn-block'
            ]
        ));
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Users',
        ));
    }
}
