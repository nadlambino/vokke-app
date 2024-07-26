<?php

namespace App\Enums;

trait Arrayable
{
    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
