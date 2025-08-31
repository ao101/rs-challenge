<?php

namespace App\Collection;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @template T of object
 * 
 * @extends ArrayCollection<int, T>
 */
abstract class ProduceCollection extends ArrayCollection implements ProduceCollectionInterface
{
    public function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            $this->validateType($element);
        }

        parent::__construct($elements);
    }

    /**
     * @param object $produce
     */
    public function add($produce): void
    {
        $this->validateType($produce);

        if (!$this->contains($produce)) {
            parent::add($produce);
        }
    }

    /**
     * @param object $produce
     */
    public function remove($produce): void
    {
        $this->validateType($produce);
        $this->removeElement($produce);
    }

    public function list(): array
    {
        return $this->toArray();
    }

    public function each(callable $callback): void
    {
        foreach ($this as $key => $value) {
            $callback($value, $key);
        }
    }

    abstract protected function validateType(object $produce): void;
}