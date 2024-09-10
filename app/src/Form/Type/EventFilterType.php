<?php

namespace App\Form\Type;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EventFilterType.
 */
class EventFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('category', EntityType::class, [
            'class' => Category::class,
            'choice_label' => 'title',
            'placeholder' => 'Choose a category',
            'required' => false,
            'mapped' => false, // Prevent the category from being bound to the form's data class
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'method' => 'GET', // Set method to GET for query parameters
            'csrf_protection' => false, // No need for CSRF protection for a filter form
        ]);
    }
}
