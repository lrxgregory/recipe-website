<?php

namespace App\Form;

use App\Entity\Recipe;
use DateTimeImmutable;
use App\Entity\Category;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('category', CategoryAutocompleteField::class, ['label' => 'Catégorie'])
            ->add('content', TextareaType::class, ['label' => 'Détail de la recette'])
            ->add('duration', IntegerType::class, ['label' => 'Durée de la recette en minutes'])
            ->add('thumbnailFile', FileType::class, [
                'label' => 'Image',
                'required' => false,
            ])
            ->add('quantities', CollectionType::class, [
                'entry_type' => QuantityType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'entry_options' => ['label' => false],
                'attr' => [
                    'data-controller' => 'form-collection'
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Envoyer'])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoCompleteSlugAndDate(...));;
    }

    public function autoCompleteSlugAndDate(PostSubmitEvent $event): void
    {
        $data = $event->getData();
        if (!($data instanceof Recipe)) {
            return;
        }

        $slugger = new AsciiSlugger();
        $data->setSlug(strtolower($slugger->slug($data->getTitle())));

        if (!$data->getId()) {
            $data->setCreatedAt(new DateTimeImmutable());
        }

        $data->setUpdatedAt(new DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
