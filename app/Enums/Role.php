<?php

namespace App\Enums;

enum Role: string
{
    case User = 'user';
    case Worker = 'worker';
    case Admin = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
