<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista.
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Form\Validator;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Validator to check if the new password and confirm password fields match.
 */
class PasswordsMatchValidator extends ConstraintValidator
{
    /**
     * Validates the password match constraint.
     *
     * @param mixed      $value      The value that should be validated (not used in this case)
     * @param Constraint $constraint The constraint for the validation
     *
     * @throws UnexpectedTypeException if the constraint is not an instance of PasswordsMatch
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof PasswordsMatch) {
            throw new UnexpectedTypeException($constraint, PasswordsMatch::class);
        }

        /** @var FormInterface $form */
        $form = $this->context->getRoot();

        $newPassword = $form->get('newPassword')->getData();
        $confirmNewPassword = $form->get('confirmNewPassword')->getData();

        if ($newPassword !== $confirmNewPassword) {
            $this->context->buildViolation($constraint->message)
                ->atPath('confirmNewPassword')
                ->addViolation();
        }
    }
}
