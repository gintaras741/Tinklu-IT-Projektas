<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BicycleItem extends Pivot
{
    protected $fillable = ['amount', 'cart_id', 'bicycle_id'];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function bicycle(): BelongsTo
    {
        return $this->belongsTo(Bicycle::class);
    }
}
