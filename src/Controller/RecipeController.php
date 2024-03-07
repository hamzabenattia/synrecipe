<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Recipie;
use App\Entity\User;
use App\Form\MarkType;
use App\Form\RecipeType;
use App\Repository\MarkRepository;
use App\Repository\RecipieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Flex\Recipe;

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



    #[Route('/recettes/all/', name: 'recettesPublic', methods: ['GET'])]
    public function showall (RecipieRepository $repo , PaginatorInterface $paginator, Request $request): Response
    {

        $recipes = $paginator->paginate(
            $repo->getPublicRecipie(null),
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        

        return $this->render('pages/recipe/showPublic.html.twig',[
            'recipes' => $recipes
        ]);
    }





    #[Route('/recette/view/{id}', name: 'recettes.show', methods: ['GET', 'POST'])]
    #[IsGranted('VIEW', subject: 'recipe' , message: 'Vous n\'avez pas accès à cette recette')]
    public function show (Recipie $recipe , Request $request , EntityManagerInterface $manager , MarkRepository $repo): Response
    {
        $form=$this->createForm(MarkType::class);
        $form->handleRequest($request);


        $existeMark = $repo->findOneBy(['user' => $this->getUser(), 'recipe' => $recipe]);

if($form->isSubmitted() && $form->isValid()){


    if ($existeMark){
        $this->addFlash(
            'danger',
            'Vous avez déjà noté cette recette.'
        );
        return $this->redirectToRoute('recettes.show', ['id' => $recipe->getId()]);
    }

    $mark = $form->getData();
    $mark->setRecipe($recipe);
        $mark->setUser($this->getUser());
    $manager->persist($mark);

    $manager->flush();
    $this->addFlash(
        'success',
        'La recette a bien été notée.'
    );
    return $this->redirectToRoute('recettes.show', ['id' => $recipe->getId()]);
}


        return $this->render('pages/recipe/show.html.twig',[
            'recipe' => $recipe,
            'form' => $form->createView(),
            'existeMark' => $existeMark

        ]);

    }



    #[Route('/recette/{id}', name: 'recettes.edit', methods: ['GET', 'POST'])]
    #[IsGranted('EDIT', subject: 'recipe' , message: 'Vous n\'avez pas accès à cette recette')]
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
    #[IsGranted('Edit', subject: 'recipe' , message: 'Vous n\'avez pas accès à cette recette')]

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
