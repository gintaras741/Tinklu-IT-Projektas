<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bicycle extends Model
{
    protected $fillable =['name'];

    public function components(): HasMany
    {
        return $this->hasMany(BicycleComponent::class);
    }

    public function parts(): BelongsToMany
    {
        return $this->belongsToMany(BicyclePart::class, 'bicycle_components', 'bicycle_id', 'bicycle_part_id')
            ->using(BicycleComponent::class)
            ->withPivot('quantity');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(BicycleItem::class);
    }
}
