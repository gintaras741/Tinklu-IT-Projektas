<?php

namespace App\Enums;

enum PartType: string
{
    case Frame = 'frame';
    case Wheels = 'wheels';
    case Handlebars = 'handlebars';
    case Saddle = 'saddle';
    case Pedals = 'pedals';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
    
    public function label(): string
    {
        return match($this) {
            self::Frame => 'Rėmas',
            self::Wheels => 'Ratai',
            self::Handlebars => 'Vairas',
            self::Saddle => 'Balneliai',
            self::Pedals => 'Pedalai',
        };
    }
}
