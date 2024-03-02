<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recipie;
use App\Entity\User;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RecipeType extends AbstractType
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
       $this->token = $token;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,
                [
                    'attr' => [
                        'class' => 'form-control',
                        'minlength' => 2,
                        'maxlength' => 50
                    ],
                    'label' => 'Nom de la recette',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ]
                ]
            )
            ->add('time',NumberType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Temps de préparation',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('nbPeople' , IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Nombre de personnes',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
              
            ])
            ->add('difficulty',RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 5
                ],
                'label' => 'Difficulté de la recette',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => 2,
                    'maxlength' => 255
                ],
                'label' => 'Description de la recette',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ]
            ])
            ->add('price', MoneyType::class, [
                'attr' =>[
                    'class' => 'form-control',
                ],

                ]
            )
            ->add('isFavorite', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'label' => 'Recette favorite',
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'required' => false,
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'query_builder' => function (IngredientRepository $er) {
                    return $er->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC')
                        ->where('i.user = :user')
                        ->setParameter('user', $this->token->getToken()->getUser())
                        ;
                },
                'attr' => [
                    'class' => 'form-select'
                ],
                'label' => 'Ingrédients',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'choice_label' => 'name',
                'multiple' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipie::class,
        ]);
    }
}
