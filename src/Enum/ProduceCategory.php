<?php

namespace App\Enum;

use App\Enum\Trait\ToArrayTrait;

enum ProduceCategory: string
{
    use ToArrayTrait;

    case FRUIT = 'fruit';
    case VEGETABLE = 'vegetable';
}