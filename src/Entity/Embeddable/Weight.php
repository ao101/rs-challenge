<?php

namespace App\Entity\Embeddable;

use App\Enum\WeightUnit;
use Doctrine\ORM\Mapping\Embeddable;
use Doctrine\ORM\Mapping as ORM;

#[Embeddable]
class Weight
{
    #[ORM\Column]
    private float $weightInGrams;   

    public function getWeightInGrams(): float
    {
        return $this->weightInGrams;
    }

    public function setWeightInGrams(float $weightInGrams): static
    {
        $this->weightInGrams = $weightInGrams;

        return $this;
    }

    public static function toUnit(float $weight, WeightUnit $sourceUnit, WeightUnit $targetUnit): float
    {
        if ($sourceUnit === $targetUnit) {
            return $weight;
        }

        $weightInGrams = $weight * $sourceUnit->toGramsFactor();

        $weightInTargetUnit = $weightInGrams / $targetUnit->toGramsFactor();

        return round($weightInTargetUnit, 2);
    }
}