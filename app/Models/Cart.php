<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partItems(): HasMany
    {
        return $this->hasMany(PartItem::class)->inCart();
    }

    public function bicycleItems(): HasMany
    {
        return $this->hasMany(BicycleItem::class)->inCart();
    }

    /**
     * Get total number of items in cart
     */
    public function getItemCount(): int
    {
        return $this->partItems()->sum('amount') + $this->bicycleItems()->sum('amount');
    }

    /**
     * Get cart total price
     */
    public function getTotal(): float
    {
        $partsTotal = 0;
        foreach ($this->partItems()->with('part')->get() as $item) {
            $partsTotal += $item->amount * ($item->part->price ?? 0);
        }

        $bicyclesTotal = 0;
        foreach ($this->bicycleItems()->with('bicycle.components.part')->get() as $item) {
            $bicyclesTotal += $item->amount * $item->bicycle->calculatePrice();
        }

        return round($partsTotal + $bicyclesTotal, 2);
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty(): bool
    {
        return $this->partItems()->count() === 0 && $this->bicycleItems()->count() === 0;
    }
}
