<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/',  name: 'app_home' , methods: ['GET','POST'])]
    public function index(): Response
    {
        return $this->render('pages/home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
