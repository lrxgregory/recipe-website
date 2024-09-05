<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Security\Voter\RecipeVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\Turbo\TurboBundle;

#[Route('admin/recipe', name: 'admin.recipe.')]
#[IsGranted('ROLE_USER')]
class RecipeController extends AbstractController
{
    #[Route('/', name: 'index')]
    #[IsGranted(RecipeVoter::LIST)]
    public function index(RecipeRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        // Use the paginated recipes directly without reassigning it to something else
        $recipes = $repository->paginateRecipes($page);

        // Render the template with the paginated recipes
        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form
        ]);
    }

    #[Route('/create', name: 'create')]
    #[IsGranted(RecipeVoter::CREATE)]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'La recette a bien été ajoutée');
            return $this->redirectToRoute('admin.recipe.index');
        }
        return $this->render('admin/recipe/create.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
    #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $em): Response
    {
        $message = 'La recette a bien été supprimée';
        $recipeId = $recipe->getId();
        $em->remove($recipe);
        $em->flush();
        if($request->getPreferredFormat() ===  TurboBundle::STREAM_FORMAT) {
            $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
            return $this->render('admin/recipe/delete.html.twig', ['recipeId' => $recipeId, 'message' => $message]);
        }
        $this->addFlash('success',  $message);
        return $this->redirectToRoute('admin.recipe.index');
    }
}
