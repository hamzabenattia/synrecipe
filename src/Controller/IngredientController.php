<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Entity\User;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;



#[IsGranted(User::ROLE_USER, message: 'You need to be connected to access this page.')]
class IngredientController extends AbstractController
{
    /**
     * Display All Ingredients
     *
     * @param IngredientRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientRepository $repo  ,PaginatorInterface $paginator, Request $request , #[CurrentUser] User $user): Response
    {

        $ingredients = $paginator->paginate(
            $repo->findBy(['user' => $user] ), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }



    /**
     * Add new Ingredient
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    
    #[Route('/ingredient/new', name: 'app_new' , methods: ['GET', 'POST'])]
    public function new(
        Request $request , 
        EntityManagerInterface $manager
    ): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(
            IngredientType::class , $ingredient
        );

        $form ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
           $ingredient = $form->getData();
           $ingredient -> setUser($this->getUser());
           $manager -> persist($ingredient);
           $manager -> flush();
           $this->addFlash('success', 'Ingredient Created Successfully');
           return $this->redirectToRoute('app_ingredient');
        }
    
        return $this->render('pages/ingredient/new.html.twig', [
            'form' => $form ->createView()
        ]);
    }

    

    /**
     * Delete Ingredient
     *
     * @param Ingredient $ingredient
     * @param EntityManagerInterface $manager
     * @return Response
     */


    #[Route('/ingredient/{id}', name: 'edit_ingredient' , methods: ['GET', 'POST'])]
    #[IsGranted('EDIT', subject: 'ingredient', message: 'Ingredient can only be shown to their authors.')]  


    public function edit(Ingredient $ingredient, EntityManagerInterface $manager, Request $request): Response
    {
            $form = $this->createForm(IngredientType::class,$ingredient);
            $form -> handleRequest($request);

            if ($ingredient->getUser() !== $this->getUser()){
                throw $this->createAccessDeniedException('You are not allowed to edit this ingredient');
            }

            if ($form ->isSubmitted() && $form ->isValid()){
                $manager -> flush();
                $this->addFlash('success', 'Ingredient Updated Successfully');
                return $this->redirectToRoute('app_ingredient');    
            }


        return $this->render('pages/ingredient/edit.html.twig', [
            'form' => $form ->createView()
        ]);
    }


    #[Route('/ingredient/delete/{id}', name: 'delete_ingredient' , methods: ['GET'])]
    public function delete(EntityManagerInterface $manager , Ingredient $ingredient): Response
    {
        if ($ingredient->getUser() !== $this->getUser()){
            throw $this->createAccessDeniedException('You are not allowed to edit this ingredient');
        }
        $manager -> remove($ingredient);
        $manager -> flush();
        $this->addFlash('success', 'Ingredient Deleted Successfully');
        return $this->redirectToRoute('app_ingredient');
    }

}
