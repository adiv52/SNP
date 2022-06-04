<?php
namespace App\Form;

use App\Entity\Following;
use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\PrivateMessages;
use App\Repository\UsersRepository;

Class messageType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options){

        $user = $options['empty_data'];

        $builder
        ->add('receiver', EntityType::class, array (
            'choice_label'  => function($user){
                return $user->getName()."-". $user -> getNick();
            },
            'class' =>  Users::class,
            'query_builder' => function($er) use($user){
                return $er->createQueryBuilder('u'); //return $er->getFollowingUsers($user);
            },
            'label' => 'Usuario',
            'attr'  =>  [
                'class' =>  'btn btn-primary btn-user btn-block'
            ]
        ))
        
        ->add('message', TextareaType::class, array (
            'label' => 'Mensaje',
            'required' => true,
            'data_class' => null,
            'attr'  =>  [
                'class' =>  'form-control'
            ]
        ))
        ->add('submit', SubmitType::class, array (
            'label' => 'Enviar',
            'attr'  =>  [
                'class' =>  'btn btn-primary btn-user btn-block'
            ]
        ));
    }
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\PrivateMessages',
        ));
    }
}
?>