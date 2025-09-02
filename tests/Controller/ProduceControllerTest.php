<?php

namespace Tests\Controller;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Repository\ProduceCollectionRepository;
use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProduceControllerTest extends WebTestCase
{
    protected KernelBrowser $client;
    private $entityManager;

    protected function setUp(): void
    {
        $this->client = static::createClient();

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

    public function testPostRequestAddsToCollections(): void
    {
        $payload = json_encode([
            [
                'id' => 1,
                'name' => 'Carrot',
                'type' => 'vegetable',
                'quantity' => 2000,
                'unit' => 'g'
            ],
            [
                'id' => 2,
                'name' => 'Apples',
                'type' => 'fruit',
                'quantity' => 1000,
                'unit' => 'g'
            ]
        ]);

        $this->client->request(
            'POST', '/produce', [], [], ['CONTENT_TYPE' => 'application/json'], $payload
        );

        $response = $this->client->getResponse();
        self::assertResponseIsSuccessful();

        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        $this->assertCount(2, $data);

        $this->assertArrayHasKey('id', $data[0]);
        $this->assertEquals('fruit', $data[0]['produceCategory']);
        $this->assertEquals('apples', $data[0]['produceType']);
        $this->assertEquals(1000, $data[0]['weight']);

        $this->assertArrayHasKey('id', $data[1]);
        $this->assertEquals('vegetable', $data[1]['produceCategory']);
        $this->assertEquals('carrot', $data[1]['produceType']);
        $this->assertEquals(2000, $data[1]['weight']);
    }

    public function testListHasAllStoredElements(): void
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
                'name' => 'Apples',
                'type' => 'fruit',
                'quantity' => 1000,
                'unit' => 'g'
            ]
        ]);

        $container = self::getContainer();

        $container->get(StorageService::class)->handle($data);

        $fruits = $container->get(FruitCollection::class);
        $vegetables = $container->get(VegetableCollection::class);

        $repo = $container->get(ProduceCollectionRepository::class);
        $repo->store($fruits);
        $repo->store($vegetables);

        $this->client->request('GET', '/produce');

        $response = $this->client->getResponse();
        self::assertResponseIsSuccessful();

        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        $this->assertCount(2, $data);

        $this->assertArrayHasKey('id', $data[0]);
        $this->assertEquals('fruit', $data[0]['produceCategory']);
        $this->assertEquals('apples', $data[0]['produceType']);
        $this->assertEquals(1000, $data[0]['weight']);

        $this->assertArrayHasKey('id', $data[1]);
        $this->assertEquals('vegetable', $data[1]['produceCategory']);
        $this->assertEquals('carrot', $data[1]['produceType']);
        $this->assertEquals(2000, $data[1]['weight']);
    }

    public function testUnitSelectionNormalizesWeight(): void
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
                'name' => 'Apples',
                'type' => 'fruit',
                'quantity' => 1000,
                'unit' => 'g'
            ]
        ]);

        $container = self::getContainer();

        $container->get(StorageService::class)->handle($data);

        $fruits = $container->get(FruitCollection::class);
        $vegetables = $container->get(VegetableCollection::class);

        $repo = $container->get(ProduceCollectionRepository::class);
        $repo->store($fruits);
        $repo->store($vegetables);

        $this->client->request('GET', '/produce?unit=kg');

        $response = $this->client->getResponse();
        self::assertResponseIsSuccessful();

        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        $this->assertCount(2, $data);

        $this->assertArrayHasKey('id', $data[0]);
        $this->assertEquals('fruit', $data[0]['produceCategory']);
        $this->assertEquals('apples', $data[0]['produceType']);
        $this->assertEquals(1, $data[0]['weight']);

        $this->assertArrayHasKey('id', $data[1]);
        $this->assertEquals('vegetable', $data[1]['produceCategory']);
        $this->assertEquals('carrot', $data[1]['produceType']);
        $this->assertEquals(2, $data[1]['weight']);
    }

    public function testSearchingCollectionOfTwoElementsReturnsOneResultElement(): void
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
                'name' => 'Apples',
                'type' => 'fruit',
                'quantity' => 1000,
                'unit' => 'g'
            ]
        ]);

        $container = self::getContainer();

        $container->get(StorageService::class)->handle($data);

        $fruits = $container->get(FruitCollection::class);
        $vegetables = $container->get(VegetableCollection::class);

        $repo = $container->get(ProduceCollectionRepository::class);
        $repo->store($fruits);
        $repo->store($vegetables);

        $this->client->request('GET', '/produce?search=ppl');

        $response = $this->client->getResponse();
        self::assertResponseIsSuccessful();

        $data = json_decode($response->getContent(), true);

        $this->assertIsArray($data);
        $this->assertCount(1, $data);
        $this->assertEquals('apples', $data[0]['produceType']);
    }
}
