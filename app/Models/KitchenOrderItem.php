<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KitchenOrderItem extends Model
{
    protected $fillable = [
        'kitchen_order_id',
        'bom_recipe_id',
        'quantity',
        'price',
        'is_completed',
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

    public function recipe()
    {
        return $this->belongsTo(BomRecipe::class, 'bom_recipe_id');
    }
}
