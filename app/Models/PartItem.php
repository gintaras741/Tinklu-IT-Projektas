<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PartItem extends Pivot
{
    protected $table = 'part_items';
    public $incrementing = false;
    protected $fillable = ['amount', 'cart_id', 'bicycle_part_id', 'order_id', 'price_at_purchase', 'subtotal'];

    protected $casts = [
        'amount' => 'integer',
        'price_at_purchase' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(BicyclePart::class, 'bicycle_part_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Calculate subtotal based on amount and price
     */
    public function calculateSubtotal(): float
    {
        return round($this->amount * $this->price_at_purchase, 2);
    }

    /**
     * Scopes
     */
    public function scopeInCart($query)
    {
        return $query->whereNull('order_id');
    }

    public function scopeInOrder($query)
    {
        return $query->whereNotNull('order_id');
    }
}
