<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(RecipeRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);

        $recipes = $repository->paginateRecipes($page);

        return $this->render('home/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }
}
