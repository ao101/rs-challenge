<?php

namespace App\Repository;

use App\Collection\ProduceCollectionInterface;
use Doctrine\ORM\EntityManagerInterface;

class ProduceCollectionRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function store(ProduceCollectionInterface $collection): void
    {
        $collection->each(
            fn($element) => $this->entityManager->persist($element)
        );

        $this->entityManager->flush();
    }
}