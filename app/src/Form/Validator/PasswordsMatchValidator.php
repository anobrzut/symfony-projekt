<?php

namespace App\Form\Validator;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PasswordsMatchValidator extends ConstraintValidator
{
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

