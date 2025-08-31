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
}