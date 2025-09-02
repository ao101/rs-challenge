<?php

namespace Tests\Entity\Embeddable;

use App\Entity\Embeddable\Weight;
use App\Enum\WeightUnit;
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    public function testReturnsSameValueWhenUnitsAreEqual(): void
    {
        $weight = 500.0;
        $unit = WeightUnit::GRAM;

        $result = Weight::toUnit($weight, $unit, $unit);

        $this->assertSame($weight, $result);
    }

    public function testConvertsGramToKilogram(): void
    {
        $weight = 1000.0;
        $sourceUnit = WeightUnit::GRAM;
        $targetUnit = WeightUnit::KILOGRAM;

        $result = Weight::toUnit($weight, $sourceUnit, $targetUnit);

        $this->assertSame(1.0, $result);
    }

    public function testConvertsKilogramToGram(): void
    {
        $weight = 2.0;
        $sourceUnit = WeightUnit::KILOGRAM;
        $targetUnit = WeightUnit::GRAM;

        $result = Weight::toUnit($weight, $sourceUnit, $targetUnit);

        $this->assertSame(2000.0, $result);
    }
}
