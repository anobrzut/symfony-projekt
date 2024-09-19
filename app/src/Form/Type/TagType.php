<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Form\Type;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class TagType.
 *
 * This form type is used for creating and editing Tag entities.
 */
class TagType extends AbstractType
{
    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator The translator service
     */
    public function __construct(private readonly TranslatorInterface $translator)
    {
    }

    /**
     * Builds the form for the Tag entity.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options Additional options for the form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => $this->translator->trans('label.title'),
                'required' => true,
                'empty_data' => '',
            ]);
    }

    /**
     * Configures the options for this form type.
     *
     * @param OptionsResolver $resolver The options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
