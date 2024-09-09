<?php

namespace App\Form\Type;

use App\Entity\Events;
use App\Entity\Category;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EventsType.
 */
class EventsType extends AbstractType
{
    /**
     * Constructor.
     *
     * @param TagsDataTransformer $tagsDataTransformer Tags data transformer
     */
    public function __construct(private readonly TagsDataTransformer $tagsDataTransformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'label.title',
                    'required' => true,
                    'attr' => ['max_length' => 255],
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'label.description',
                    'required' => false,
                    'attr' => ['rows' => 5, 'max_length' => 255],
                ]
            )
            ->add(
                'date',
                DateTimeType::class,
                [
                    'label' => 'label.date',
                    'required' => true,
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'choice_label' => 'title',
                    'label' => 'label.category',
                    'placeholder' => 'Choose a category',
                    'required' => true,
                ]
            )
            ->add(
                'tags',
                TextType::class,
                [
                    'label' => 'label.tags',
                    'required' => false,
                    'attr' => ['max_length' => 255],
                ]
            );

        $builder->get('tags')->addModelTransformer($this->tagsDataTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Events::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'events';
    }
}
