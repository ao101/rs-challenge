<?php

namespace App\Service;

use App\Collection\FruitCollection;
use App\Collection\VegetableCollection;
use App\Dto\ProduceDto;
use App\Entity\Fruit;
use App\Entity\Vegetable;
use App\Enum\ProduceCategory;
use App\Repository\ProduceCollectionRepository;
use App\Service\Validator\ValidatorService;
use Psr\Log\LoggerInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Serializer\SerializerInterface;

class StorageService
{
    public function __construct(
        private LoggerInterface $logger,
        private SerializerInterface $serializer,
        private ValidatorService $validator,
        private ObjectMapperInterface $objectMapper,
        private FruitCollection $fruits,
        private VegetableCollection $vegetables,
        private ProduceCollectionRepository $produceCollectionRepository,
    ) {}

    public function handle(string $rawData, $softFail = false): void
    {
        $produceDtos = $this->serializer->deserialize($rawData, ProduceDto::class . '[]', 'json');

        foreach ($produceDtos as $produceDto) {

            try {
                $this->validator->validate($produceDto);

            } catch (\Throwable $th) {

                if ($softFail) {
                    $this->logger->info('Validation failed. Skipping {object}. {message}', ['object' => (string) $produceDto, 'message' => $th->getMessage()]);

                    continue;
                }

                throw $th;
            }

            /**
             * @var Fruit&Vegetable
             */
            $produce = $this->objectMapper->map($produceDto);

            match (true) {
                ProduceCategory::FRUIT === $produce->getProduceCategory() => $this->fruits->add($produce),
                ProduceCategory::VEGETABLE === $produce->getProduceCategory() => $this->vegetables->add($produce),
                default => throw new \Exception('This should be unreachable')
            };
        }
    }

    public function storeCollections(): void
    {
        $this->produceCollectionRepository->store($this->fruits);
        $this->produceCollectionRepository->store($this->vegetables);
    }
}
