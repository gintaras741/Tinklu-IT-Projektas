<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BicycleComponent extends Pivot
{
    protected $table = 'bicycle_components';
    
    protected $fillable = ['bicycle_id', 'bicycle_part_id', 'quantity'];

    public function bicycle(): BelongsTo
    {
        return $this->belongsTo(Bicycle::class);
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(BicyclePart::class, 'bicycle_part_id');
    }
}