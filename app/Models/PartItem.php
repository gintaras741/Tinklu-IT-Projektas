<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PartItem extends Pivot
{
    protected $fillable = ['amount', 'cart_id', 'bicycle_part_id'];

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(BicyclePart::class);
    }
}
