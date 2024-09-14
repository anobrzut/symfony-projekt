<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Form\Type;

use App\Entity\Contacts;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ContactsType.
 *
 * This form type is used to handle Contacts entity forms, allowing users to input information such as name, phone, description, and tags.
 */
class ContactsType extends AbstractType
{
    /**
     * Constructor.
     *
     * @param TagsDataTransformer $tagsDataTransformer Data transformer for handling tag inputs
     */
    public function __construct(private readonly TagsDataTransformer $tagsDataTransformer)
    {
    }

    /**
     * Builds the form for the Contacts entity.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Additional form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'label.name',
                    'required' => true,
                    'attr' => ['max_length' => 255],
                    'empty_data' => '',
                ]
            )
            ->add(
                'phone',
                IntegerType::class,
                [
                    'label' => 'label.phone',
                    'required' => true,
                    'empty_data' => '',
                ]
            )
            ->add(
                'description',
                TextType::class,
                [
                    'label' => 'label.description',
                    'required' => false,
                    'attr' => ['max_length' => 255],
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
        $resolver->setDefaults(['data_class' => Contacts::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * @return string The block prefix
     */
    public function getBlockPrefix(): string
    {
        return 'contacts';
    }
}
