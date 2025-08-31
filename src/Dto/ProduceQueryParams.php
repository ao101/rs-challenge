<?php

namespace App\Dto;

use App\Enum\ProduceCategory;
use App\Enum\ProduceType;
use App\Enum\WeightUnit;

class ProduceQueryParams
{
    public ?ProduceType $produceType = null;
    public ?ProduceCategory $produceCategory = null;
    public ?int $weight = null;
    public ?WeightUnit $unit = null;
    public ?int $minWeight = null;
    public ?int $maxWeight = null;
    public ?string $search = null;

    public static function fromQuery(array $query): self
    {
        $dto = new self();

        if (!empty($query['name'])) {
            $name = strtolower($query['name']);
            $dto->produceType = ProduceType::tryFrom($name);
        }

        if (!empty($query['type'])) {
            $type = strtolower($query['type']);
            $dto->produceCategory = ProduceCategory::tryFrom($type);
        }

        if (!empty($query['weight'])) {
            $dto->weight = (int) $query['weight'];
        }

        if (!empty($query['unit'])) {
            $unit = strtolower($query['unit']);
            $dto->unit = WeightUnit::tryFrom($unit);
        }

        if (!empty($query['minWeight'])) {
            $dto->minWeight = (int) $query['minWeight'];
        }

        if (!empty($query['maxWeight'])) {
            $dto->maxWeight = (int) $query['maxWeight'];
        }

        if (!empty($query['search'])) {
            $dto->search = $query['search'];
        }

        return $dto;
    }
}
