<?php

namespace App\Controller;

use App\DTO\ContactDTO;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new ContactDTO();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $data = $form->getData();

                $email = (new TemplatedEmail())
                    ->from($data->email)
                    ->to('contact@demo.fr')
                    ->subject('Demande de contacy')
                    // path of the Twig template to render
                    ->htmlTemplate('mail/contact.html.twig')
                    // pass variables (name => value) to the template
                    ->context([
                        'data' => $data
                    ]);
                $mailer->send($email);
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
