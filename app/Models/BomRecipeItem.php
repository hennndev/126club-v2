<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomRecipeItem extends Model
{
    protected $fillable = [
        'bom_recipe_id',
        'inventory_item_id',
        'quantity',
        'unit',
        'cost',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    public function bomRecipe()
    {
        return $this->belongsTo(BomRecipe::class);
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
