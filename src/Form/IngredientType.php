<?php

namespace App\Form;

use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class IngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'attr' =>[
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 50
                    
                ],
                'label' => 'Nom de l\'ingrédient',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a name',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'le nom d\'ingredient doit etre a minimum de {{ limit }} characters',
                        'max' => 50,
                    ]),
                ],


            ]
            )
            ->add('price', MoneyType::class,[
                'attr' =>[
                    'class' => 'form-control',
                ],
                'label' => 'Prix de l\'ingrédient',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                   new Positive([
                       'message' => 'le prix doit etre un nombre positif'
                   ]),
                   new NotBlank([
                       'message' => 'le prix ne doit pas etre vide'
                   ]),
                   new LessThan([
                       'value' => 2000,
                       'message' => 'le prix doit etre inferieur a 2000'
                   ])

                ],
            ]     )
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Créer un ingrédient'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ingredient::class,
        ]);
    }
}
