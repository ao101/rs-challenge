<?php

namespace App\Enum;

enum ProduceType: string
{
    case APPLES   = 'apples';
    case AVOCADO  = 'avocado';
    case BANANAS  = 'bananas';
    case BEANS    = 'beans';
    case BEETROOT = 'beetroot';
    case BERRIES  = 'berries';
    case BROCCOLI = 'broccoli';
    case CABBAGE  = 'cabbage';
    case CARROT   = 'carrot';
    case CELERY   = 'celery';
    case CUCUMBER = 'cucumber';
    case KIWI     = 'kiwi';
    case KUMQUAT  = 'kumquat';
    case LETTUCE  = 'lettuce';
    case MELONS   = 'melons';
    case ONION    = 'onion';
    case ORANGES  = 'oranges';
    case PEARS    = 'pears';
    case PEPPER   = 'pepper';
    case TOMATOES = 'tomatoes';

    public function category(): ProduceCategory
    {
        return match ($this) {
            self::APPLES,
            self::AVOCADO,
            self::BANANAS,
            self::BERRIES,
            self::KIWI,
            self::KUMQUAT,
            self::MELONS,
            self::ORANGES,
            self::PEARS => ProduceCategory::FRUIT,

            self::BEANS,
            self::BEETROOT,
            self::BROCCOLI,
            self::CABBAGE,
            self::CARROT,
            self::CELERY,
            self::CUCUMBER,
            self::LETTUCE,
            self::ONION,
            self::PEPPER,
            self::TOMATOES  => ProduceCategory::VEGETABLE,
        };
    }
}