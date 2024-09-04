<?php

namespace App\Controller\API;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    #[Route("/api/me", methods: ["GET"])]
    #[IsGranted("ROLE_USER")]
    public function me()
    {
        return $this->json($this->getUser());
    }
}
