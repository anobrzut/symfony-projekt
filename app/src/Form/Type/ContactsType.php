<?php

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
 */
class ContactsType extends AbstractType
{
    /**
     * Konstruktor.
     *
     * @param TagsDataTransformer $tagsDataTransformer Transformator danych dla tagów
     */
    public function __construct(private readonly TagsDataTransformer $tagsDataTransformer)
    {
    }

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
                ]
            )
            ->add(
                'phone',
                IntegerType::class,
                [
                    'label' => 'label.phone',
                    'required' => true,
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Contacts::class]);
    }

    public function getBlockPrefix(): string
    {
        return 'contacts';
    }
}
