<?php

namespace App\Service\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\GroupSequence;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    public function __construct(
        private ValidatorInterface $validator
    ) {}

    public function validate(mixed $value, Constraint|array|null $constraints = null, string|GroupSequence|array|null $groups = null): void
    {
        $errors = $this->validator->validate($value, $constraints, $groups);

        if ($errors->count() > 0) {
            throw new ValidationFailedException($value, $errors);
        }
    }
}