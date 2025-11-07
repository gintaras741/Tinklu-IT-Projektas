<?php

namespace App\Enums;

enum PartType: string
{
    case Frame = 'frame';
    case Fork = 'fork';
    case SeatPost = 'seat_post';
    case Headset = 'headset';
    case SeatClamp = 'seat_clamp';
    case Tires = 'tires';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
