<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Event\ContactRequestEvent;
use App\Form\ContactType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer, EventDispatcherInterface $dispatcher): Response
    {
        $contact = new ContactDTO();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $dispatcher->dispatch((new ContactRequestEvent($contact)));
                $this->addFlash('success', 'L\'email a bien été envoyé');
            } catch (\Exception $e) {
                dd($e);
                $this->addFlash('danger', 'Impossible d\'envoyer l\'email');
            }
            $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form
        ]);
    }
}
