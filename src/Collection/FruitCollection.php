<?php

namespace App\Collection;

use App\Entity\Fruit;
use InvalidArgumentException;

/**
 * @extends ProduceCollection<Fruit>
 */
class FruitCollection extends ProduceCollection
{
    protected function validateType(object $produce): void
    {
        if ($produce instanceof Fruit) {
            return;
        }
        
        throw new InvalidArgumentException(sprintf('Expected instance of %s, got %s', Fruit::class, $produce::class));
    }
}
