<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\InventoryCategory;
use App\Models\InventoryItem;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kitchen = InventoryCategory::where('code', 'KITCHEN')->first();
        $bar = InventoryCategory::where('code', 'BAR')->first();
        $bom = InventoryCategory::where('code', 'BOM')->first();

        $items = [
            // Kitchen - Spices
            ['category_id' => $kitchen->id, 'name' => 'Black Pepper', 'category_type' => 'Spices', 'price' => 45000, 'stock_quantity' => 15, 'threshold' => 5, 'unit' => 'kg'],
            ['category_id' => $kitchen->id, 'name' => 'Salt', 'category_type' => 'Spices', 'price' => 5000, 'stock_quantity' => 50, 'threshold' => 10, 'unit' => 'kg'],
            ['category_id' => $kitchen->id, 'name' => 'Garlic Powder', 'category_type' => 'Spices', 'price' => 35000, 'stock_quantity' => 8, 'threshold' => 5, 'unit' => 'kg'],
            ['category_id' => $kitchen->id, 'name' => 'Paprika', 'category_type' => 'Spices', 'price' => 55000, 'stock_quantity' => 3, 'threshold' => 5, 'unit' => 'kg'],
            
            // Kitchen - Condiments
            ['category_id' => $kitchen->id, 'name' => 'Olive Oil', 'category_type' => 'Condiments', 'price' => 125000, 'stock_quantity' => 20, 'threshold' => 8, 'unit' => 'liter'],
            ['category_id' => $kitchen->id, 'name' => 'Soy Sauce', 'category_type' => 'Condiments', 'price' => 25000, 'stock_quantity' => 12, 'threshold' => 6, 'unit' => 'liter'],
            ['category_id' => $kitchen->id, 'name' => 'Tomato Ketchup', 'category_type' => 'Condiments', 'price' => 35000, 'stock_quantity' => 18, 'threshold' => 10, 'unit' => 'bottle'],
            ['category_id' => $kitchen->id, 'name' => 'Mayonnaise', 'category_type' => 'Condiments', 'price' => 45000, 'stock_quantity' => 4, 'threshold' => 8, 'unit' => 'bottle'],
            
            // Kitchen - Dairy
            ['category_id' => $kitchen->id, 'name' => 'Heavy Cream', 'category_type' => 'Dairy', 'price' => 65000, 'stock_quantity' => 25, 'threshold' => 10, 'unit' => 'liter'],
            ['category_id' => $kitchen->id, 'name' => 'Butter', 'category_type' => 'Dairy', 'price' => 85000, 'stock_quantity' => 15, 'threshold' => 8, 'unit' => 'kg'],
            ['category_id' => $kitchen->id, 'name' => 'Cheddar Cheese', 'category_type' => 'Dairy', 'price' => 120000, 'stock_quantity' => 6, 'threshold' => 5, 'unit' => 'kg'],
            
            // Bar - Spirits
            ['category_id' => $bar->id, 'name' => 'Johnnie Walker Black Label', 'category_type' => 'Spirits', 'price' => 750000, 'stock_quantity' => 45, 'threshold' => 15, 'unit' => 'bottle'],
            ['category_id' => $bar->id, 'name' => 'Jack Daniels', 'category_type' => 'Spirits', 'price' => 650000, 'stock_quantity' => 30, 'threshold' => 12, 'unit' => 'bottle'],
            ['category_id' => $bar->id, 'name' => 'Chivas Regal', 'category_type' => 'Spirits', 'price' => 850000, 'stock_quantity' => 8, 'threshold' => 10, 'unit' => 'bottle'],
            ['category_id' => $bar->id, 'name' => 'Grey Goose Vodka', 'category_type' => 'Spirits', 'price' => 950000, 'stock_quantity' => 22, 'threshold' => 15, 'unit' => 'bottle'],
            ['category_id' => $bar->id, 'name' => 'Bacardi Rum', 'category_type' => 'Spirits', 'price' => 450000, 'stock_quantity' => 18, 'threshold' => 10, 'unit' => 'bottle'],
            ['category_id' => $bar->id, 'name' => 'Bombay Sapphire Gin', 'category_type' => 'Spirits', 'price' => 550000, 'stock_quantity' => 5, 'threshold' => 8, 'unit' => 'bottle'],
            
            // Bar - Beverage
            ['category_id' => $bar->id, 'name' => 'Coca Cola', 'category_type' => 'Beverage', 'price' => 8000, 'stock_quantity' => 150, 'threshold' => 50, 'unit' => 'bottle'],
            ['category_id' => $bar->id, 'name' => 'Sprite', 'category_type' => 'Beverage', 'price' => 8000, 'stock_quantity' => 120, 'threshold' => 50, 'unit' => 'bottle'],
            ['category_id' => $bar->id, 'name' => 'Tonic Water', 'category_type' => 'Beverage', 'price' => 15000, 'stock_quantity' => 35, 'threshold' => 20, 'unit' => 'bottle'],
            ['category_id' => $bar->id, 'name' => 'Fresh Orange Juice', 'category_type' => 'Beverage', 'price' => 25000, 'stock_quantity' => 25, 'threshold' => 15, 'unit' => 'liter'],
            ['category_id' => $bar->id, 'name' => 'Cranberry Juice', 'category_type' => 'Beverage', 'price' => 35000, 'stock_quantity' => 12, 'threshold' => 15, 'unit' => 'liter'],
            
            // Bar - Condiments  
            ['category_id' => $bar->id, 'name' => 'Angostura Bitters', 'category_type' => 'Condiments', 'price' => 125000, 'stock_quantity' => 6, 'threshold' => 5, 'unit' => 'bottle'],
            ['category_id' => $bar->id, 'name' => 'Grenadine Syrup', 'category_type' => 'Condiments', 'price' => 45000, 'stock_quantity' => 8, 'threshold' => 5, 'unit' => 'bottle'],
            ['category_id' => $bar->id, 'name' => 'Simple Syrup', 'category_type' => 'Condiments', 'price' => 25000, 'stock_quantity' => 15, 'threshold' => 8, 'unit' => 'bottle'],
            ['category_id' => $bar->id, 'name' => 'Lime Juice', 'category_type' => 'Condiments', 'price' => 30000, 'stock_quantity' => 20, 'threshold' => 10, 'unit' => 'liter'],
            
            // BOM - Mix
            ['category_id' => $bom->id, 'name' => 'Ice Cubes', 'category_type' => 'Beverage', 'price' => 5000, 'stock_quantity' => 200, 'threshold' => 50, 'unit' => 'kg'],
            ['category_id' => $bom->id, 'name' => 'Straws', 'category_type' => 'Condiments', 'price' => 15000, 'stock_quantity' => 1000, 'threshold' => 200, 'unit' => 'pack'],
            ['category_id' => $bom->id, 'name' => 'Napkins', 'category_type' => 'Condiments', 'price' => 25000, 'stock_quantity' => 500, 'threshold' => 100, 'unit' => 'pack'],
            ['category_id' => $bom->id, 'name' => 'Toothpicks', 'category_type' => 'Condiments', 'price' => 8000, 'stock_quantity' => 50, 'threshold' => 20, 'unit' => 'pack'],
        ];

        foreach ($items as $item) {
            InventoryItem::create($item);
        }
    }
}
