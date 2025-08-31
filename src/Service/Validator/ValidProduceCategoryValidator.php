<?php

namespace App\Service\Validator;

use App\Enum\ProduceType;
use App\Enum\ProduceCategory;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use App\Dto\ProduceDto;
use App\Service\Validator\ValidProduceCategory;

class ValidProduceCategoryValidator extends ConstraintValidator
{
    /**
     * @param ProduceDto $produceDto
     */
    public function validate(mixed $produceDto, Constraint $constraint): void
    {
        if (!$produceDto instanceof ProduceDto) {
            throw new UnexpectedValueException($produceDto, ProduceDto::class);
        }

        if (!$constraint instanceof ValidProduceCategory) {
            throw new UnexpectedValueException($constraint, ValidProduceCategory::class);
        }

        $expectedCategory = ProduceType::from($produceDto->name)?->category();
        $actualCategory = ProduceCategory::from($produceDto->type);

        if ($expectedCategory === $actualCategory) {
            return;
        }

        $this->context
            ->buildViolation($constraint->message)
            ->setParameter('{{ type }}', ucfirst($produceDto->name))
            ->setParameter('{{ category }}', ucfirst($produceDto->type))
            ->addViolation();
    }
}
