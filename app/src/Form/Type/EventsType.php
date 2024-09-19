<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

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
 *
 * This form type is used for handling the creation and editing of Events entities.
 */
class EventsType extends AbstractType
{
    /**
     * Constructor.
     *
     * @param TagsDataTransformer $tagsDataTransformer Data transformer for handling tags input
     */
    public function __construct(private readonly TagsDataTransformer $tagsDataTransformer)
    {
    }

    /**
     * Builds the form for the Events entity.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Additional form options
     */
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
                    'empty_data' => '',
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
                    'empty_data' => (new \DateTime())->format('Y-m-d\TH:i'),
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
                    'empty_data' => '',
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

    /**
     * Configures the options for this form type.
     *
     * @param OptionsResolver $resolver The options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Events::class]);
    }

    /**
     * Returns the prefix of the template block name for this form type.
     *
     * @return string The block prefix
     */
    public function getBlockPrefix(): string
    {
        return 'events';
    }
}
