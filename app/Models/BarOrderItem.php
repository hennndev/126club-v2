<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarOrderItem extends Model
{
    protected $fillable = [
        'bar_order_id',
        'inventory_item_id',
        'quantity',
        'price',
        'is_completed',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_completed' => 'boolean',
    ];

    public function barOrder(): BelongsTo
    {
        return $this->belongsTo(BarOrder::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function getItemNameAttribute(): string
    {
        return $this->inventoryItem?->name ?? 'Item';
    }
}
