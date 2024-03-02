<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName',TextType::class,[
                'label' => 'Nom complet',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'placeholder' => 'Nom complet',
                    'class' => 'form-control'
                ]
            ])
            ->add('pseudo',TextType::class,[
                'label' => 'Pseudo (no required)',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'required' => false,
                'attr' => [
                    'placeholder' => 'Pseudo',
                    'class' => 'form-control',

                ]
            ])
            ->add('email', EmailType::class,[
                'label' => 'Adresse email',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'attr' => [
                    'placeholder' => 'Adresse email',
                    'class' => 'form-control'
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
