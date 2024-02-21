<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use App\Entity\Recipie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Flex\Recipe;

class AppFixtures extends Fixture
{
    private Generator $faker; 

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');

    }
 
    public function load(ObjectManager $manager): void
    {

            $ingredients = [];
            for ($i = 0; $i < 30; $i++) {
                $ingredient = new Ingredient();
                $ingredient -> setName($this->faker->word(8));
                $ingredient -> setPrice($this->faker->randomFloat(1, 0, 500));
                $ingredients[] = $ingredient;
                $manager->persist($ingredient);
            }

            for ($i=0; $i < 25; $i++) { 
                $recepie = new Recipie();
                $recepie -> setName($this->faker->word(10))
                         -> setTime($this->faker->numberBetween(1, 1440))
                         -> setNbPeople(mt_rand(0, 1) === 1 ? $this->faker->numberBetween(1, 10) : null)
                         -> setDescription($this->faker->text(20))
                         ->setPrice($this->faker->randomFloat(1, 1, 1000))
                         ->setDifficulty(mt_rand(1, 5))
                         ->setIsFavorite(mt_rand(0, 1) === 0 ? false : true) ;

                for ($j=0; $j < mt_rand(5, 15); $j++) {
                    $recepie -> addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
                }
                $manager->persist($recepie);
            }

            for ($i=0; $i <10 ; $i++) { 
                $user = new User();
                $user ->setFullName($this->faker->name())
                     ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
                        ->setEmail($this->faker->email())
                        ->setPassword('password');
                $manager->persist($user);  
            }
     

        $manager->flush();
    }
}
