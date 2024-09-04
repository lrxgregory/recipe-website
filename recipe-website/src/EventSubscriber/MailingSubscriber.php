<?php

namespace App\EventSubscriber;

use App\Event\ContactRequestEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailingSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly MailerInterface $mailer){

    }

    public function onContactRequestEvent(ContactRequestEvent $event): void
    {
        $data = $event->data;
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
        $this->mailer->send($email);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContactRequestEvent::class => 'onContactRequestEvent',
        ];
    }
}
