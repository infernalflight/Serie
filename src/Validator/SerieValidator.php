<?php

namespace App\Validator;

use App\Entity\Serie;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class SerieValidator
{
    public static function validate(Serie $serie, ExecutionContextInterface $context): void
    {
        if (\in_array($serie->getStatus(), ['ended', 'canceled']) && !$serie->getLastAirDate()) {
            $context->buildViolation('Il me faut une date de fin si la série est abandonnée ou terminée')
                ->atPath('lastAirDate')
                ->addViolation();
        }

        if (!\in_array($serie->getStatus(), ['ended', 'canceled']) && $serie->getLastAirDate()) {
            $context->buildViolation('Pourquoi une date de fin alors que la série continue ??')
                ->atPath('lastAirDate')
                ->addViolation();
        }
    }


}
