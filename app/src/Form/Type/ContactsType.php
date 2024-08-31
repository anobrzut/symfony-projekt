<?php
/**
 * Contacts type.
 */

namespace App\Form\Type;

use App\Entity\Contacts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ContactsType.
 */
class ContactsType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
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
                'createdAt',
                DateTimeType::class,
                [
                    'label' => 'label.created_at',
                    'required' => true,
                    'widget' => 'single_text',
                ]
            )
            ->add(
                'updatedAt',
                DateTimeType::class,
                [
                    'label' => 'label.updated_at',
                    'required' => true,
                    'widget' => 'single_text',
                ]
            );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Contacts::class]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'contacts';
    }
}
