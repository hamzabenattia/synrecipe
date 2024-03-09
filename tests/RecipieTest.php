<?php

namespace App\Tests;

use App\Entity\Mark;
use App\Entity\Recipie;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class RecipieTest extends KernelTestCase
{

    public function getRecipie(): Recipie{
        return (new Recipie())
                    ->setName('Name1')
                    ->setDescription('description1')
                    ->setPrice(55)
                    ;
    }

    public function testSomething(): void
    {
        self::bootKernel();

        $container = static::getContainer();

        $recipie = $this->getRecipie();

        $errors = $container ->get('validator') ->validate($recipie);

        $this->assertCount(0,$errors);
    
    }


    public function testAverageMark(){
        $recipie = $this->getRecipie();

        self::bootKernel();

        $user = static::getContainer()->get('doctrine.orm.entity_manager')->find(User::class,1);

        for ($i=0; $i <5 ; $i++) { 
            $mark = new Mark();
            $mark->setMark(2)
                ->setUser($user)
                ->setRecipe($recipie);


            $recipie->addMark($mark);
        
        }

        $this->assertTrue(2.0 === $recipie->getAverageMark());
    }
}
