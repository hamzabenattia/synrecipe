<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\Ingredient;
use App\Entity\Mark;
use App\Entity\Recipie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private Generator $faker; 
    private  UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->faker = Factory::create('fr_FR');
        $this->passwordHasher = $passwordHasher;


    }
 
    public function load(ObjectManager $manager): void
    {

        $users = [];
        for ($i=0; $i <10 ; $i++) { 
            $user = new User();
            $user ->setFullName($this->faker->name())
                 ->setPseudo(mt_rand(0, 1) === 1 ? $this->faker->firstName() : null)
                    ->setEmail($this->faker->email())
                    ->setPlainPassword('password');

                    $users[] = $user;
            $manager->persist($user);  
        }

            $ingredients = [];
            for ($i = 0; $i < 30; $i++) {
                $ingredient = new Ingredient();
                $ingredient -> setName($this->faker->word(8));
                $ingredient -> setPrice($this->faker->randomFloat(1, 0, 500));
                $ingredient -> setUser($users[mt_rand(0, count($users) - 1)]);
                $ingredients[] = $ingredient;
                $manager->persist($ingredient);

            }

            $recepies = [];

            for ($i=0; $i < 25; $i++) { 
                $recepie = new Recipie();
                $recepie -> setName($this->faker->word(10))
                         -> setTime($this->faker->numberBetween(1, 1440))
                         -> setNbPeople(mt_rand(0, 1) === 1 ? $this->faker->numberBetween(1, 10) : null)
                         -> setDescription($this->faker->text(20))
                         ->setPrice($this->faker->randomFloat(1, 1, 1000))
                         ->setIsPublic(mt_rand(0, 1) === 0 ? false : true)
                         ->setDifficulty(mt_rand(1, 5))
                         ->setIsFavorite(mt_rand(0, 1) === 0 ? false : true)
                         -> setUser($users[mt_rand(0, count($users) - 1)]);
                         

                for ($j=0; $j < mt_rand(5, 15); $j++) {
                    $recepie -> addIngredient($ingredients[mt_rand(0, count($ingredients) - 1)]);
                }


                $recepies[] = $recepie;
                $manager->persist($recepie);
            }

            foreach ($recepies as $recepie ) {

                for ($i=0; $i < mt_rand(0, 4); $i++) { 

                    $mark = new Mark();
                    $mark -> setMark(mt_rand(1, 5))
                          -> setUser($users[mt_rand(0, count($users) - 1)])
                          -> setRecipe($recepie);

                          $manager->persist($mark);
                }
            }
           
     
            for ($i=0; $i <5 ; $i++) { 
                $contact = new Contact();
                $contact -> setFullName($this->faker->name())
                         -> setEmail($this->faker->email())
                         -> setSubject($this->faker->word(8))
                         -> setMessage($this->faker->text(100));

                $manager->persist($contact);
                
            }

        $manager->flush();
    }
}
