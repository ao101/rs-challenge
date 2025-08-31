<?php

namespace App\Mapper\Condition;

use App\Dto\ProduceDto;
use App\Entity\Produce;
use App\Enum\ProduceCategory;
use Symfony\Component\ObjectMapper\ConditionCallableInterface;

/**
 * @implements ConditionCallableInterface<ProduceDto, Produce>
 */
class IsFruitCondition implements ConditionCallableInterface
{
    public function __invoke(mixed $value, object $source, ?object $target): bool
    {
        return ProduceCategory::FRUIT === ProduceCategory::from($source->type);
    }
}