<?php

namespace App\Dto;

use App\Entity\Fruit;
use App\Entity\Vegetable;
use App\Enum\ProduceCategory;
use App\Enum\ProduceType;
use App\Enum\WeightUnit;
use App\Mapper\Condition\IsFruitCondition;
use App\Mapper\Condition\IsVegetableCondition;
use App\Mapper\Transformer\ProduceDtoTransformer;
use App\Service\Validator as CustomAssert;
use Symfony\Component\ObjectMapper\Attribute\Map;
use Symfony\Component\Validator\Constraints as Assert;

#[Map(
    target:    Fruit::class,
    if:        IsFruitCondition::class,
    transform: ProduceDtoTransformer::class
)]
#[Map(
    target:    Vegetable::class,
    if:        IsVegetableCondition::class,
    transform: ProduceDtoTransformer::class
)]
#[CustomAssert\ValidProduceCategory()]
class ProduceDto
{
    #[Assert\NotBlank]
    public int $id;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: [ProduceType::class, 'toArray'], message: 'Unsupported name.')]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: [ProduceCategory::class, 'toArray'], message: 'Unsupported type.')]
    public string $type;

    #[Assert\NotBlank]
    #[Assert\Positive(message: 'Quantity should be greater than zero.')]
    public float $quantity;

    #[Assert\NotBlank]
    #[Assert\Choice(callback: [WeightUnit::class, 'toArray'], message: 'Unsupported unit.')]
    public string $unit;

    public function setName(string $name): self
    {
        $this->name = strtolower($name);

        return $this;
    }

    public function setUnit(string $unit): self
    {
        $this->unit = strtolower($unit);

        return $this;
    }

    public function __toString(): string
    {
        return json_encode([
            'class'    => self::class,
            'name'     => $this->name,
            'type'     => $this->type,
            'quantity' => $this->quantity,
            'unit'     => $this->unit,
        ]);
    }
}
