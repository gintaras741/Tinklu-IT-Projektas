<?php

namespace App\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Processing = 'processing';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::Pending => 'Laukiama',
            self::Processing => 'Vykdoma',
            self::Shipped => 'Išsiųsta',
            self::Delivered => 'Pristatyta',
            self::Cancelled => 'Atšaukta',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Pending => 'yellow',
            self::Processing => 'blue',
            self::Shipped => 'purple',
            self::Delivered => 'green',
            self::Cancelled => 'red',
        };
    }

    public function next(): self
    {
        return match($this) {
            self::Pending => self::Processing,
            self::Processing => self::Shipped,
            self::Shipped => self::Delivered,
            self::Delivered => self::Pending,
            self::Cancelled => self::Cancelled,
        };
    }
}
