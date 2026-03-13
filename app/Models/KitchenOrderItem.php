<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenOrderItem extends Model
{
    protected $fillable = [
        'kitchen_order_id',
        'inventory_item_id',
        'quantity',
        'price',
        'is_completed',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'is_completed' => 'boolean',
    ];

    public function kitchenOrder()
    {
        return $this->belongsTo(KitchenOrder::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
