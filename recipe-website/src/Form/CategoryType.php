<?php

namespace App\Form;

use DateTimeImmutable;
use App\Entity\Category;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de la catégorie'])
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoCompleteSlugAndDate(...));
    }

    public function autoCompleteSlugAndDate(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        if (!($data instanceof Category)) {
            return;
        }

        $slugger = new AsciiSlugger();
        $data->setSlug(strtolower($slugger->slug($data->getName())));

        if (!$data->getId()) {
            $data->setCreatedAt(new DateTimeImmutable());
        }

        $data->setUpdatedAt(new DateTimeImmutable());
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
