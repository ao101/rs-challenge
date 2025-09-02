<?php

namespace Tests\App\Service;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use ValueError;

class StorageServiceTest extends KernelTestCase
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

    public function testUnknownNameThrowsException(): void
    {
        $this->expectException(ValueError::class);

        $data = json_encode([
            [
                'id' => 1,
                'name' => '78a9cb0cfc121013ceaadd09822a428657dc6201',
                'type' => 'vegetable',
                'quantity' => 2000,
                'unit' => 'g'
            ]
        ]);

        self::getContainer()->get(StorageService::class)->handle($data);
    }

    public function testUnknownTypeThrowsException(): void
    {
        $this->expectException(ValueError::class);

        $data = json_encode([
            [
                'id' => 1,
                'name' => 'Carrot',
                'type' => '78a9cb0cfc121013ceaadd09822a428657dc6201',
                'quantity' => 2000,
                'unit' => 'g'
            ]
        ]);

        self::getContainer()->get(StorageService::class)->handle($data);
    }

    public function testQuantityIsZeroThrowsException(): void
    {
        $this->expectException(ValidationFailedException::class);

        $data = json_encode([
            [
                'id' => 1,
                'name' => 'Carrot',
                'type' => 'vegetable',
                'quantity' => 0,
                'unit' => 'g'
            ]
        ]);

        self::getContainer()->get(StorageService::class)->handle($data);
    }

    public function testQuantityIsNegativeThrowsException(): void
    {
        $this->expectException(ValidationFailedException::class);

        $data = json_encode([
            [
                'id' => 1,
                'name' => 'Carrot',
                'type' => 'vegetable',
                'quantity' => -1,
                'unit' => 'g'
            ]
        ]);

        self::getContainer()->get(StorageService::class)->handle($data);
    }

    public function testUnknownUnitThrowsException(): void
    {
        $this->expectException(ValidationFailedException::class);

        $data = json_encode([
            [
                'id' => 1,
                'name' => 'Carrot',
                'type' => 'vegetable',
                'quantity' => 2000,
                'unit' => '78a9cb0cfc121013ceaadd09822a428657dc6201'
            ]
        ]);

        self::getContainer()->get(StorageService::class)->handle($data);
    }

    public function testParsingErroneousDataWithSoftFailEnabledAddsElementsToCollections(): void
    {
        $data = json_encode([
            [
                'id' => 1,
                'name' => 'Carrot',
                'type' => 'vegetable',
                'quantity' => 2000,
                'unit' => 'g'
            ],
            [
                'id' => 2,
                'name' => 'Salmon',
                'type' => 'fish',
                'quantity' => 0,
                'unit' => 't'
            ]
        ]);

        self::getContainer()->get(StorageService::class)->handle($data, true);

        $this->assertEquals(0, self::getContainer()->get(FruitCollection::class)->count());
        $this->assertEquals(1, self::getContainer()->get(VegetableCollection::class)->count());
    }

    public function testParsingWithHardFailThrowsException(): void
    {
        $this->expectException(ValidationFailedException::class);

        $data = json_encode([
            (object) [
                'id' => 1,
                'name' => 'Carrot',
                'type' => 'vegetable',
                'quantity' => 2000,
                'unit' => '78a9cb0cfc121013ceaadd09822a428657dc6201'
            ],
            (object) [
                'id' => 2,
                'name' => 'Salmon',
                'type' => 'fish',
                'quantity' => 0,
                'unit' => 't'
            ]
        ]);

        self::getContainer()->get(StorageService::class)->handle($data);
    }

    public function testCreatesTwoSeparateCollections(): void
    {
        $data = json_encode([
            (object) [
                'id' => 1,
                'name' => 'Carrot',
                'type' => 'vegetable',
                'quantity' => 2000,
                'unit' => 'g'
            ],
            (object) [
                'id' => 2,
                'name' => 'Apples',
                'type' => 'fruit',
                'quantity' => 1,
                'unit' => 'kg'
            ]
        ]);

        $this->assertEquals(0, self::getContainer()->get(FruitCollection::class)->count());
        $this->assertEquals(0, self::getContainer()->get(VegetableCollection::class)->count());

        self::getContainer()->get(StorageService::class)->handle($data);

        $this->assertEquals(1, self::getContainer()->get(FruitCollection::class)->count());
        $this->assertEquals(1, self::getContainer()->get(VegetableCollection::class)->count());
    }
}
