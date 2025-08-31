<?php

namespace App\Entity;

use App\Enum\ProduceCategory;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Vegetable extends Produce
{
    public function getProduceCategory(): ProduceCategory
    {
        return ProduceCategory::VEGETABLE;
    }
}
