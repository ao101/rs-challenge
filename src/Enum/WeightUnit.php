<?php

namespace App\Enum;

use App\Enum\Trait\ToArrayTrait;

enum WeightUnit: string
{
    use ToArrayTrait;

    case KILOGRAM = 'kg';
    case GRAM = 'g';

    public function toGramsFactor(): float
    {
        return match($this) {
            self::KILOGRAM => 1000,
            self::GRAM     => 1,
        };
    }
}