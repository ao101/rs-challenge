<?php

namespace App\Collection;

use App\Entity\Vegetable;
use InvalidArgumentException;

/**
 * @extends ProduceCollection<Vegetable>
 */
class VegetableCollection extends ProduceCollection
{
    protected function validateType(object $produce): void
    {
        if ($produce instanceof Vegetable) {
            return;
        }
        
        throw new InvalidArgumentException(sprintf('Expected instance of %s, got %s', Vegetable::class, $produce::class));
    }
}
