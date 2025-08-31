<?php

namespace App\Criteria;

use App\Dto\ProduceQueryParams;
use App\Entity\Embeddable\Weight;
use App\Enum\WeightUnit;
use Doctrine\Common\Collections\Criteria;

class ProduceCriteria extends Criteria
{
    public const FOR_QUERY_BUILDER = 'for_query_builder';
    public const FOR_COLLECTION = 'for_collection';

    public static function fromQueryParams(ProduceQueryParams $params, string $context = self::FOR_QUERY_BUILDER): self
    {
        $expr = self::expr();
        $criteria = new self();
        $conditions = [];

        if (isset($params->produceType)) {
            $conditions[] = $expr->eq('produceType', $params->produceType);
        }

        if (isset($params->category)) {
            $conditions[] = $expr->eq('produceCategory', $params->produceCategory);
        }

        if (isset($params->weight)) {
            $conditions[] = $expr->eq('weight.weightInGrams', Weight::toUnit($params->weight, $params->unit ?? WeightUnit::GRAM, WeightUnit::GRAM));
        }

        if (isset($params->minWeight)) {
            $conditions[] = $expr->gte('weight.weightInGrams', Weight::toUnit($params->minWeight, $params->unit ?? WeightUnit::GRAM, WeightUnit::GRAM));
        }

        if (isset($params->maxWeight)) {
            $conditions[] = $expr->lte('weight.weightInGrams', Weight::toUnit($params->maxWeight, $params->unit ?? WeightUnit::GRAM, WeightUnit::GRAM));
        }

        if (isset($params->search)) {

            $field = 'produceType';

            if (self::FOR_COLLECTION === $context) {
                $field = "{$field}.value";
            }

            $conditions[] = $expr->contains($field, $params->search);
        }

        if (count($conditions) > 0) {
            $criteria->where($expr->andX(...$conditions));
        }

        return $criteria;
    }
}
