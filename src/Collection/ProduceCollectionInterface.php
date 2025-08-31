<?php

namespace App\Collection;

interface ProduceCollectionInterface
{
    public function each(callable $callback): void;
}