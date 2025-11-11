<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bicycle extends Model
{
    protected $fillable = ['name', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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

    /**
     * Calculate total price based on components
     */
    public function calculatePrice(): float
    {
        $total = 0;
        foreach ($this->components()->with('part')->get() as $component) {
            $total += ($component->part->price ?? 0) * $component->quantity;
        }
        return round($total, 2);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '€' . number_format($this->calculatePrice(), 2);
    }
}
