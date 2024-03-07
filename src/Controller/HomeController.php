<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\RecipieRepository;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/',  name: 'app_home' , methods: ['GET','POST'])]
    public function index(RecipieRepository $repo): Response
    {

        return $this->render('pages/home/index.html.twig', [
            'controller_name' => 'HomeController',
            'recipes' =>  $repo->getPublicRecipie(10),
        ]);
    }
}
