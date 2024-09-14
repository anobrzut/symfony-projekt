<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Form\Type;

use App\Form\Validator\PasswordsMatch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class ChangePasswordType.
 *
 * This form type is used to handle the user password change functionality.
 * It includes fields for the current password, new password, and confirmation of the new password.
 */
class ChangePasswordType extends AbstractType
{
    /**
     * Builds the form for changing the user's password.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Additional form options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'label.current_password',
                'mapped' => false,
                'required' => true,
                'attr' => ['autocomplete' => 'current-password'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter your current password.',
                    ]),
                ],
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'label.new_password',
                'mapped' => false,
                'required' => true,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please enter a new password.',
                    ]),
                    new Assert\Length([
                        'min' => 6,
                        'minMessage' => 'Your new password should be at least {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('confirmNewPassword', PasswordType::class, [
                'label' => 'label.confirm_new_password',
                'mapped' => false,
                'required' => true,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Please confirm your new password.',
                    ]),
                ],
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
            'constraints' => [
                new PasswordsMatch(),
            ],
        ]);
    }
}
