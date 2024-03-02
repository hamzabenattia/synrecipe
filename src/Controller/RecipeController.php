<?php

namespace App\Controller;

use App\Entity\Recipie;
use App\Entity\User;
use App\Form\RecipeType;
use App\Repository\RecipieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class RecipeController extends AbstractController
{
    /**
     * Controller pour afficher la liste des recettes
     *
     * @param RecipieRepository $repo
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */
    #[Route('/recettes', name: 'recettes.index' , methods: ['GET'])]
    public function index(RecipieRepository $repo, PaginatorInterface $paginator, Request $request, #[CurrentUser] User $user): Response
    {

        $recipes = $paginator->paginate(
            $repo->findBy(['user' => $user] ), /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('pages/recipe/index.html.twig', [
            'recipes' =>$recipes
        ]);
    }


    #[Route('/recette/new', name: 'recettes.add', methods: ['GET', 'POST'])]
    public function add(Request $request , EntityManagerInterface $manager, #[CurrentUser] User $user): Response
    {
        $recipe = new Recipie();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $recipe= $form->getData();
            $recipe->setUser($user);
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash(
                'success',
                'La recette a bien été ajoutée.'
            );
            return $this->redirectToRoute('recettes.index');

        }

        return $this->render('pages/recipe/new.html.twig',[
            'form' => $form->createView()
        ]);
    }





    #[Route('/recette/{id}', name: 'recettes.edit', methods: ['GET', 'POST'])]
    public function edit(Recipie $recipe , EntityManagerInterface $manager , Request $request): Response
    {
       
        $form = $this->createForm(RecipeType::class, $recipe);
        $form ->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();
            $manager->persist($recipe);
            $manager->flush();
            $this->addFlash(
                'success',
                'La recette a bien été modifiée.'
            );
            return $this->redirectToRoute('recettes.index');
        }
        return $this->render('pages/recipe/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }






    #[Route('/recette/delete/{id}', name: 'recettes.delete', methods: ['GET'])]
    public function delete(Recipie $recipe, EntityManagerInterface $manager ): Response
    {
        $manager->remove($recipe);
        $manager->flush();
        $this->addFlash(
           'success',
           'La recette a bien été supprimée.'
        );
        return $this->redirectToRoute('recettes.index');
    }

}
