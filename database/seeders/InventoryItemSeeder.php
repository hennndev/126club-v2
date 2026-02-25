<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // Kitchen - Spices
            ['code' => 'BP001', 'accurate_id' => 2001, 'name' => 'Black Pepper', 'category_type' => 'spices', 'price' => 45000, 'stock_quantity' => 15, 'threshold' => 5, 'unit' => 'kg'],
            ['code' => 'SALT001', 'accurate_id' => 2002, 'name' => 'Salt', 'category_type' => 'spices', 'price' => 5000, 'stock_quantity' => 50, 'threshold' => 10, 'unit' => 'kg'],
            ['code' => 'GP001', 'accurate_id' => 2003, 'name' => 'Garlic Powder', 'category_type' => 'spices', 'price' => 35000, 'stock_quantity' => 8, 'threshold' => 5, 'unit' => 'kg'],
            ['code' => 'PAP001', 'accurate_id' => 2004, 'name' => 'Paprika', 'category_type' => 'spices', 'price' => 55000, 'stock_quantity' => 3, 'threshold' => 5, 'unit' => 'kg'],

            // Kitchen - Condiments
            ['code' => 'OO001', 'accurate_id' => 2005, 'name' => 'Olive Oil', 'category_type' => 'condiments', 'price' => 125000, 'stock_quantity' => 20, 'threshold' => 8, 'unit' => 'liter'],
            ['code' => 'SS001', 'accurate_id' => 2006, 'name' => 'Soy Sauce', 'category_type' => 'condiments', 'price' => 25000, 'stock_quantity' => 12, 'threshold' => 6, 'unit' => 'liter'],
            ['code' => 'TK001', 'accurate_id' => 2007, 'name' => 'Tomato Ketchup', 'category_type' => 'condiments', 'price' => 35000, 'stock_quantity' => 18, 'threshold' => 10, 'unit' => 'bottle'],
            ['code' => 'MAY001', 'accurate_id' => 2008, 'name' => 'Mayonnaise', 'category_type' => 'condiments', 'price' => 45000, 'stock_quantity' => 4, 'threshold' => 8, 'unit' => 'bottle'],

            // Kitchen - Dairy
            ['code' => 'HC001', 'accurate_id' => 2009, 'name' => 'Heavy Cream', 'category_type' => 'dairy', 'price' => 65000, 'stock_quantity' => 25, 'threshold' => 10, 'unit' => 'liter'],
            ['code' => 'BUT001', 'accurate_id' => 2010, 'name' => 'Butter', 'category_type' => 'dairy', 'price' => 85000, 'stock_quantity' => 15, 'threshold' => 8, 'unit' => 'kg'],
            ['code' => 'CHED001', 'accurate_id' => 2011, 'name' => 'Cheddar Cheese', 'category_type' => 'dairy', 'price' => 120000, 'stock_quantity' => 6, 'threshold' => 5, 'unit' => 'kg'],

            // Bar - Spirits
            ['code' => 'JWB001', 'accurate_id' => 3001, 'name' => 'Johnnie Walker Black Label', 'category_type' => 'spirits', 'price' => 750000, 'stock_quantity' => 45, 'threshold' => 15, 'unit' => 'bottle'],
            ['code' => 'JD001', 'accurate_id' => 3002, 'name' => 'Jack Daniels', 'category_type' => 'spirits', 'price' => 650000, 'stock_quantity' => 30, 'threshold' => 12, 'unit' => 'bottle'],
            ['code' => 'CR001', 'accurate_id' => 3003, 'name' => 'Chivas Regal', 'category_type' => 'spirits', 'price' => 850000, 'stock_quantity' => 8, 'threshold' => 10, 'unit' => 'bottle'],
            ['code' => 'GG001', 'accurate_id' => 3004, 'name' => 'Grey Goose Vodka', 'category_type' => 'spirits', 'price' => 950000, 'stock_quantity' => 22, 'threshold' => 15, 'unit' => 'bottle'],
            ['code' => 'BR001', 'accurate_id' => 3005, 'name' => 'Bacardi Rum', 'category_type' => 'spirits', 'price' => 450000, 'stock_quantity' => 18, 'threshold' => 10, 'unit' => 'bottle'],
            ['code' => 'BSG001', 'accurate_id' => 3006, 'name' => 'Bombay Sapphire Gin', 'category_type' => 'spirits', 'price' => 550000, 'stock_quantity' => 5, 'threshold' => 8, 'unit' => 'bottle'],

            // Bar - Beverage (for POS - these will be drinks)
            ['code' => 'CC001', 'accurate_id' => 4001, 'name' => 'Coca Cola', 'category_type' => 'drink', 'price' => 8000, 'stock_quantity' => 150, 'threshold' => 50, 'unit' => 'bottle'],
            ['code' => 'SPR001', 'accurate_id' => 4002, 'name' => 'Sprite', 'category_type' => 'drink', 'price' => 8000, 'stock_quantity' => 120, 'threshold' => 50, 'unit' => 'bottle'],
            ['code' => 'TW001', 'accurate_id' => 4003, 'name' => 'Tonic Water', 'category_type' => 'drink', 'price' => 15000, 'stock_quantity' => 35, 'threshold' => 20, 'unit' => 'bottle'],
            ['code' => 'OJ001', 'accurate_id' => 4004, 'name' => 'Fresh Orange Juice', 'category_type' => 'drink', 'price' => 25000, 'stock_quantity' => 25, 'threshold' => 15, 'unit' => 'liter'],
            ['code' => 'CJ001', 'accurate_id' => 4005, 'name' => 'Cranberry Juice', 'category_type' => 'drink', 'price' => 35000, 'stock_quantity' => 12, 'threshold' => 15, 'unit' => 'liter'],

            // Bar - Condiments
            ['code' => 'AB001', 'accurate_id' => 5001, 'name' => 'Angostura Bitters', 'category_type' => 'condiments', 'price' => 125000, 'stock_quantity' => 6, 'threshold' => 5, 'unit' => 'bottle'],
            ['code' => 'GS001', 'accurate_id' => 5002, 'name' => 'Grenadine Syrup', 'category_type' => 'condiments', 'price' => 45000, 'stock_quantity' => 8, 'threshold' => 5, 'unit' => 'bottle'],
            ['code' => 'SSY001', 'accurate_id' => 5003, 'name' => 'Simple Syrup', 'category_type' => 'condiments', 'price' => 25000, 'stock_quantity' => 15, 'threshold' => 8, 'unit' => 'bottle'],
            ['code' => 'LJ001', 'accurate_id' => 5004, 'name' => 'Lime Juice', 'category_type' => 'condiments', 'price' => 30000, 'stock_quantity' => 20, 'threshold' => 10, 'unit' => 'liter'],
        ];

        foreach ($items as $item) {
            InventoryItem::create($item);
        }
    }
}
