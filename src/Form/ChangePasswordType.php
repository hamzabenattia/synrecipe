<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class,[
                'constraints' => [
                    new UserPassword()
                ],
                'label' => 'Ancien mot de passe',
                'mapped' => false,
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'placeholder' => 'Ancien mot de passe',
                    'class' => 'form-control'
                ]
            ])
            
            ->add('newPassword', RepeatedType::class,[
                'type' => PasswordType::class,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 5,
                        'max' => 4096
                    ])
                ],
                'mapped' => false,

                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'hash_property_path' => 'plainPassword', // 'password' is the property path to the plain password in your User class
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'attr' => [
                        'placeholder' => 'Nouveau mot de passe',
                        'class' => 'form-control'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmer le mot de passe',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'attr' => [
                        'placeholder' => 'Confirmer le mot de passe',
                        'class' => 'form-control'
                    ]
                ]
                
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
