<?php

namespace App\Mapper\Transformer;

use App\Dto\ProduceDto;
use App\Entity\Produce;
use App\Entity\Embeddable\Weight;
use App\Enum\ProduceType;
use App\Enum\WeightUnit;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

class ProduceDtoTransformer implements TransformCallableInterface
{
    /**
     * @param Produce $value
     * @param ProduceDto $source
     */
    public function __invoke(mixed $value, object $source, ?object $target): mixed
    {
        $value->setProduceType(
            $this->mapNameToProduceType($source->name)
        );

        $value->setWeight(
            $this->mapQuantityToWeight($source->quantity, $source->unit)
        );

        return $value;
    }

    private function mapNameToProduceType(string $name): ProduceType
    {
        return ProduceType::from(strtolower($name));
    }

    private function mapQuantityToWeight(float $quantity, string $unit): Weight
    {
        $sourceUnit = WeightUnit::from($unit);

        $weightInGrams = Weight::toUnit($quantity, $sourceUnit, WeightUnit::GRAM);

        return (new Weight())->setWeightInGrams($weightInGrams);
    }
}
