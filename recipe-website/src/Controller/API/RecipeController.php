<?php

namespace App\Controller\API;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecipeController extends AbstractController
{
    #[Route('/api/recipes', methods: 'GET')]
    public function index(RecipeRepository $repository, SerializerInterface $serializer): Response
    {
        $recipes = $repository->findAll();

        // Serialize the recipes to JSON
        $jsonData = $serializer->serialize($recipes, 'json', ['groups' => ['recipe.index']]);

        // Return a new Response object with the serialized JSON data
        return new Response($jsonData, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/api/recipes/{id}', requirements: ['id' => Requirement::DIGITS], methods: 'GET')]
    public function show(Recipe $recipe, SerializerInterface $serializer): Response
    {
        // Serialize the recipes to JSON
        $jsonData = $serializer->serialize($recipe, 'json', ['groups' => ['recipe.show']]);

        // Return a new Response object with the serialized JSON data
        return new Response($jsonData, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route("/api/recipes", methods: ["POST"])]
    public function create(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['recipe.create']
            ]
        )]
        Recipe $recipe,
        EntityManagerInterface $em
    ) {
        $slugger = new AsciiSlugger();
        $recipe->setSlug(strtolower($slugger->slug($recipe->getTitle())));
        $recipe->setCreatedAt(new \DateTimeImmutable());
        $recipe->setUpdatedAt(new \DateTimeImmutable());

        $em->persist($recipe);
        $em->flush();
        return $this->json($recipe, 200, [], [
            'groups' => ['recipe.index', 'recipe.show']
        ]);
    }
}
