<?php

namespace App\Models;

use App\Enums\PartType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BicyclePart extends Model
{
    protected $fillable = [
        'type', 
        'name', 
        'amount', 
        'image'
    ];

    protected $casts = [
        'type' => PartType::class,
        'amount' => 'integer'
    ];

    public function components(): HasMany
    {
        return $this->hasMany(BicycleComponent::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(PartItem::class);
    }

    public function bicycles(): BelongsToMany
    {
        return $this->belongsToMany(Bicycle::class, 'bicycle_components', 'bicycle_part_id', 'bicycle_id')
            ->using(BicycleComponent::class)
            ->withPivot('quantity');
    }
}
