<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CardExpirationDate extends Constraint
{
    public string $message = 'La date d’expiration doit être dans le futur.';

    public function validatedBy(): string
    {
        return CardExpirationDateValidator::class;
    }
}

