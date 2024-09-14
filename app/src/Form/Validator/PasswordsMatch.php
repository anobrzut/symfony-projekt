<?php
/**
 * Projekt Symfony - Zarzadzanie Informacja Osobista
 *
 * (c) Anna Obrzut 2024 <ania.obrzut@student.uj.edu.pl>
 */

namespace App\Form\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordsMatch extends Constraint
{
    public string $message = 'The new password and confirmation do not match.';
}
