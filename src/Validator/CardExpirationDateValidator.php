<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use DateTime;

class CardExpirationDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        // Si le champ est vide, la validation échoue
        if (empty($value) || !is_string($value)) {
            $this->context->buildViolation('La date d\'expiration ne peut pas être vide.')
                ->addViolation();
            return;
        }

        // Vérifie le format MM/YY avec une expression régulière stricte
        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $value)) {
            $this->context->buildViolation('Le format doit être MM/YY (ex: 03/26)')
                ->addViolation();
            return;
        }

        // On sépare le mois et l'année
        [$month, $year] = explode('/', $value);

        // Convertit l'année en 20XX
        $fullYear = (int)('20' . $year); // "26" devient "2026"
        $month = (int)$month;

        // Crée un objet DateTime pour la fin du mois
        $expirationDate = DateTime::createFromFormat('Y-m-d', sprintf('%04d-%02d-01', $fullYear, $month));

        // Si la date est invalide (par exemple, 13/99), on montre une erreur
        if ($expirationDate === false) {
            $this->context->buildViolation('La date d\'expiration est invalide.')
                ->addViolation();
            return;
        }

        // On ajuste la date à la fin du mois pour la validation
        $expirationDate->modify('last day of this month')->setTime(23, 59, 59);

        // Compare la date d'expiration avec la date actuelle
        $now = new DateTime();
        if ($expirationDate < $now) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
