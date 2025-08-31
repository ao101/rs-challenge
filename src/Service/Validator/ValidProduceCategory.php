<?php

namespace App\Service\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidProduceCategory extends Constraint
{
    public string $message = '{{ type }} does not belong to {{ category }}.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
