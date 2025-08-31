<?php

namespace App\Enum\Trait;

trait ToArrayTrait
{
    public static function toArray(): array
    {
        return array_map(fn(self $case) => strtolower($case->value), self::cases());
    }
}