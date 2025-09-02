<?php

namespace Tests\Entity\Embeddable;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Entity\Embeddable\Weight;
use App\Entity\Fruit;
use App\Entity\Vegetable;
use App\Enum\ProduceType;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CollectionTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::getContainer()->get('doctrine')->getManager();
        $this->entityManager->beginTransaction();

        parent::setUp();
    }

    protected function tearDown(): void
    {
        if ($this->entityManager !== null && $this->entityManager->isOpen()) {
            $this->entityManager->rollback();
            $this->entityManager->close();
        }

        $this->entityManager = null;

        parent::tearDown();
    }

    public function testFruitCollectionAcceptsOnlyFruits(): void
    {
        $collection = new FruitCollection();

        $collection->add(new Fruit());
        $this->assertCount(1, $collection);;

        $this->expectException(InvalidArgumentException::class);
        $collection->add(new Vegetable());
    }

    public function testVegetableCollectionAcceptsOnlyVegetables(): void
    {
        $collection = new VegetableCollection();

        $collection->add(new Vegetable());
        $this->assertCount(1, $collection);

        $this->expectException(InvalidArgumentException::class);
        $collection->add(new Fruit());
    }

    public function testRemovingElementFromCollection(): void
    {
        $vegetable = new Vegetable();
        $collection = new VegetableCollection();
        $this->assertCount(0, $collection);

        $collection->add($vegetable);
        $this->assertCount(1, $collection);

        $collection->remove($vegetable);
        $this->assertCount(0, $collection);
    }

    public function testListingElementsGivesArrayOfSameCount(): void
    {
        $firstVegetable = new Vegetable();
        $firstVegetable->setProduceType(ProduceType::CARROT);
        $firstVegetable->setWeight((new Weight())->setWeightInGrams(1000));

        $secondVegetable = new Vegetable();
        $secondVegetable->setProduceType(ProduceType::TOMATOES);
        $secondVegetable->setWeight((new Weight())->setWeightInGrams(1000));

        $collection = new VegetableCollection();
        $this->assertCount(0, $collection);

        $collection->add($firstVegetable);
        $collection->add($secondVegetable);
        $this->assertCount(2, $collection);

        $list = $collection->list();

        $this->assertIsArray($list);
        $this->assertCount(2, $list);
    }
}
