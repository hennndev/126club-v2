<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $guarded;

    protected $casts = [
        'price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'threshold' => 'integer',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isLowStock()
    {
        return $this->stock_quantity <= $this->threshold;
    }

    public function getStockStatusAttribute()
    {
        return $this->isLowStock() ? 'low' : 'normal';
    }
}
