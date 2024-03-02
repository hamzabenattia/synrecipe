<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/profile'), IsGranted(User::ROLE_USER)]
class UserController extends AbstractController
{
    #[Route('/edit', name: 'user_edit')]
    public function index(EntityManagerInterface $manager, Request $request,  #[CurrentUser] User $user): Response
    {

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Votre profil a été mis à jour avec succès');
        }

        return $this->render('pages/user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    #[Route('/change-password', name: 'user_change_password', methods: ['GET', 'POST'])]
    public function changePassword(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
        Security $security,
    ): Response {
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $security->logout(validateCsrfToken: false) ?? $this->redirectToRoute('homepage');
        }

        return $this->render('pages/user/change_password.html.twig', [
            'form' => $form,
        ]);
    }
}
