<?php

namespace App\Entity;

use App\Enum\ProduceCategory;
use App\Enum\ProduceType;
use App\Repository\ProduceRepository;
use App\Entity\Embeddable\Weight;
use Doctrine\ORM\Mapping as ORM;

#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'category', type: 'string')]
#[ORM\DiscriminatorMap([
    ProduceCategory::FRUIT->value => Fruit::class,
    ProduceCategory::VEGETABLE->value => Vegetable::class
])]
#[ORM\Entity(repositoryClass: ProduceRepository::class)]
abstract class Produce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(enumType: ProduceType::class)]
    protected ProduceType $produceType;

    #[ORM\Embedded(class: Weight::class, columnPrefix: false)]
    protected Weight $weight;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduceType(): ProduceType
    {
        return $this->produceType;
    }

    public function setProduceType(ProduceType $produceType): static
    {
        $this->produceType = $produceType;

        return $this;
    }

    public function getWeight(): Weight
    {
        return $this->weight;
    }

    public function setWeight(Weight $weight): static
    {
        $this->weight = $weight;

        return $this;
    }
}
