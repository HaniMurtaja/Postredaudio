<?php

namespace App\Enums;

trait EnumValues {
    public static function values(): array
    {
        return array_column(static::class::cases(), 'value');
    }
}