<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_method',
        'shipping_address',
        'notes',
        'ordered_at',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
        'total_amount' => 'decimal:2',
        'ordered_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
            if (empty($order->ordered_at)) {
                $order->ordered_at = now();
            }
        });
    }

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partItems(): HasMany
    {
        return $this->hasMany(PartItem::class);
    }

    public function bicycleItems(): HasMany
    {
        return $this->hasMany(BicycleItem::class);
    }

    /**
     * Get all order items (parts and bicycles combined)
     */
    public function getAllItems()
    {
        return $this->partItems()->with('part')
            ->get()
            ->merge($this->bicycleItems()->with('bicycle')->get());
    }

    /**
     * Methods
     */
    public function calculateTotal(): float
    {
        $partsTotal = $this->partItems()->sum('subtotal') ?? 0;
        $bicyclesTotal = $this->bicycleItems()->sum('subtotal') ?? 0;
        
        return round($partsTotal + $bicyclesTotal, 2);
    }

    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . strtoupper(Str::random(8));
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, [OrderStatus::Pending, OrderStatus::Processing]);
    }

    /**
     * Scopes
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeWithStatus($query, OrderStatus $status)
    {
        return $query->where('status', $status);
    }
}
