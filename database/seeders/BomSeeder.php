<?php

namespace Database\Seeders;

use App\Models\BomRecipe;
use App\Models\BomRecipeItem;
use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class BomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get inventory items
        $oliveOil = InventoryItem::where('name', 'Olive Oil')->first();
        $blackPepper = InventoryItem::where('name', 'Black Pepper')->first();
        $salt = InventoryItem::where('name', 'Salt')->first();
        $cheddar = InventoryItem::where('name', 'Cheddar Cheese')->first();
        $butter = InventoryItem::where('name', 'Butter')->first();

        $vodka = InventoryItem::where('name', 'Grey Goose Vodka')->first();
        $gin = InventoryItem::where('name', 'Bombay Sapphire Gin')->first();
        $rum = InventoryItem::where('name', 'Bacardi Rum')->first();
        $tonicWater = InventoryItem::where('name', 'Tonic Water')->first();
        $orangeJuice = InventoryItem::where('name', 'Fresh Orange Juice')->first();
        $cranberryJuice = InventoryItem::where('name', 'Cranberry Juice')->first();
        $limeJuice = InventoryItem::where('name', 'Lime Juice')->first();
        $simpleSyrup = InventoryItem::where('name', 'Simple Syrup')->first();

        // Create inventory items for BOM products first
        $caesarSalad = InventoryItem::create([
            'code' => 'CS001',
            'accurate_id' => 6001,
            'name' => 'Caesar Salad',
            'category_type' => 'food',
            'price' => 125000,
            'stock_quantity' => 10,
            'threshold' => 5,
            'unit' => 'portion',
        ]);

        $grilledCheese = InventoryItem::create([
            'code' => 'GC001',
            'accurate_id' => 6002,
            'name' => 'Grilled Cheese Sandwich',
            'category_type' => 'food',
            'price' => 85000,
            'stock_quantity' => 15,
            'threshold' => 5,
            'unit' => 'portion',
        ]);

        $cosmopolitan = InventoryItem::create([
            'code' => 'COS001',
            'accurate_id' => 6003,
            'name' => 'Cosmopolitan',
            'category_type' => 'bar',
            'price' => 150000,
            'stock_quantity' => 20,
            'threshold' => 10,
            'unit' => 'glass',
        ]);

        $ginTonic = InventoryItem::create([
            'code' => 'GT001',
            'accurate_id' => 6004,
            'name' => 'Gin & Tonic',
            'category_type' => 'bar',
            'price' => 120000,
            'stock_quantity' => 25,
            'threshold' => 10,
            'unit' => 'glass',
        ]);

        $mojito = InventoryItem::create([
            'code' => 'MOJ001',
            'accurate_id' => 6005,
            'name' => 'Mojito',
            'category_type' => 'bar',
            'price' => 130000,
            'stock_quantity' => 20,
            'threshold' => 10,
            'unit' => 'glass',
        ]);

        // Food Recipes
        if ($oliveOil && $blackPepper && $salt && $cheddar) {
            $recipe1 = BomRecipe::create([
                'accurate_id' => 7001,
                'inventory_item_id' => $caesarSalad->id,
                'quantity' => 1,
                'type' => 'food',
                'description' => 'Classic Caesar salad with cheese and dressing',
                'selling_price' => 125000,
                'total_cost' => 0,
            ]);

            $cost1 = 0;
            // Olive Oil - 0.05 liter
            $itemCost = $oliveOil->price * 0.05;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe1->id,
                'inventory_item_id' => $oliveOil->id,
                'quantity' => 0.05,
                'unit' => $oliveOil->unit,
                'cost' => $itemCost,
            ]);
            $cost1 += $itemCost;

            // Black Pepper - 0.01 kg
            $itemCost = $blackPepper->price * 0.01;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe1->id,
                'inventory_item_id' => $blackPepper->id,
                'quantity' => 0.01,
                'unit' => $blackPepper->unit,
                'cost' => $itemCost,
            ]);
            $cost1 += $itemCost;

            // Salt - 0.005 kg
            $itemCost = $salt->price * 0.005;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe1->id,
                'inventory_item_id' => $salt->id,
                'quantity' => 0.005,
                'unit' => $salt->unit,
                'cost' => $itemCost,
            ]);
            $cost1 += $itemCost;

            // Cheddar - 0.05 kg
            $itemCost = $cheddar->price * 0.05;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe1->id,
                'inventory_item_id' => $cheddar->id,
                'quantity' => 0.05,
                'unit' => $cheddar->unit,
                'cost' => $itemCost,
            ]);
            $cost1 += $itemCost;

            $recipe1->update(['total_cost' => $cost1]);
        }

        if ($butter && $cheddar && $salt && $blackPepper) {
            $recipe2 = BomRecipe::create([
                'accurate_id' => 7002,
                'inventory_item_id' => $grilledCheese->id,
                'quantity' => 1,
                'type' => 'food',
                'description' => 'Classic grilled cheese sandwich',
                'selling_price' => 85000,
                'total_cost' => 0,
            ]);

            $cost2 = 0;
            // Butter - 0.02 kg
            $itemCost = $butter->price * 0.02;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe2->id,
                'inventory_item_id' => $butter->id,
                'quantity' => 0.02,
                'unit' => $butter->unit,
                'cost' => $itemCost,
            ]);
            $cost2 += $itemCost;

            // Cheddar - 0.08 kg
            $itemCost = $cheddar->price * 0.08;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe2->id,
                'inventory_item_id' => $cheddar->id,
                'quantity' => 0.08,
                'unit' => $cheddar->unit,
                'cost' => $itemCost,
            ]);
            $cost2 += $itemCost;

            $recipe2->update(['total_cost' => $cost2]);
        }

        // Beverage Recipes
        if ($vodka && $cranberryJuice && $limeJuice) {
            $recipe3 = BomRecipe::create([
                'accurate_id' => 7003,
                'inventory_item_id' => $cosmopolitan->id,
                'quantity' => 1,
                'type' => 'beverage',
                'description' => 'Classic vodka cocktail with cranberry',
                'selling_price' => 150000,
                'total_cost' => 0,
            ]);

            $cost3 = 0;
            // Vodka - 0.05 bottle (50ml of 1L bottle)
            $itemCost = $vodka->price * 0.05;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe3->id,
                'inventory_item_id' => $vodka->id,
                'quantity' => 0.05,
                'unit' => $vodka->unit,
                'cost' => $itemCost,
            ]);
            $cost3 += $itemCost;

            // Cranberry Juice - 0.03 liter
            $itemCost = $cranberryJuice->price * 0.03;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe3->id,
                'inventory_item_id' => $cranberryJuice->id,
                'quantity' => 0.03,
                'unit' => $cranberryJuice->unit,
                'cost' => $itemCost,
            ]);
            $cost3 += $itemCost;

            // Lime Juice - 0.015 liter
            $itemCost = $limeJuice->price * 0.015;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe3->id,
                'inventory_item_id' => $limeJuice->id,
                'quantity' => 0.015,
                'unit' => $limeJuice->unit,
                'cost' => $itemCost,
            ]);
            $cost3 += $itemCost;

            $recipe3->update(['total_cost' => $cost3]);
        }

        if ($gin && $tonicWater && $limeJuice) {
            $recipe4 = BomRecipe::create([
                'accurate_id' => 7004,
                'inventory_item_id' => $ginTonic->id,
                'quantity' => 1,
                'type' => 'beverage',
                'description' => 'Classic gin and tonic with lime',
                'selling_price' => 120000,
                'total_cost' => 0,
            ]);

            $cost4 = 0;
            // Gin - 0.05 bottle
            $itemCost = $gin->price * 0.05;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe4->id,
                'inventory_item_id' => $gin->id,
                'quantity' => 0.05,
                'unit' => $gin->unit,
                'cost' => $itemCost,
            ]);
            $cost4 += $itemCost;

            // Tonic Water - 0.15 bottle
            $itemCost = $tonicWater->price * 0.15;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe4->id,
                'inventory_item_id' => $tonicWater->id,
                'quantity' => 0.15,
                'unit' => $tonicWater->unit,
                'cost' => $itemCost,
            ]);
            $cost4 += $itemCost;

            // Lime Juice - 0.01 liter
            $itemCost = $limeJuice->price * 0.01;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe4->id,
                'inventory_item_id' => $limeJuice->id,
                'quantity' => 0.01,
                'unit' => $limeJuice->unit,
                'cost' => $itemCost,
            ]);
            $cost4 += $itemCost;

            $recipe4->update(['total_cost' => $cost4]);
        }

        if ($rum && $limeJuice && $simpleSyrup) {
            $recipe5 = BomRecipe::create([
                'accurate_id' => 7005,
                'inventory_item_id' => $mojito->id,
                'quantity' => 1,
                'type' => 'beverage',
                'description' => 'Refreshing rum cocktail with lime and mint',
                'selling_price' => 130000,
                'total_cost' => 0,
            ]);

            $cost5 = 0;
            // Rum - 0.05 bottle
            $itemCost = $rum->price * 0.05;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe5->id,
                'inventory_item_id' => $rum->id,
                'quantity' => 0.05,
                'unit' => $rum->unit,
                'cost' => $itemCost,
            ]);
            $cost5 += $itemCost;

            // Lime Juice - 0.03 liter
            $itemCost = $limeJuice->price * 0.03;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe5->id,
                'inventory_item_id' => $limeJuice->id,
                'quantity' => 0.03,
                'unit' => $limeJuice->unit,
                'cost' => $itemCost,
            ]);
            $cost5 += $itemCost;

            // Simple Syrup - 0.02 bottle
            $itemCost = $simpleSyrup->price * 0.02;
            BomRecipeItem::create([
                'bom_recipe_id' => $recipe5->id,
                'inventory_item_id' => $simpleSyrup->id,
                'quantity' => 0.02,
                'unit' => $simpleSyrup->unit,
                'cost' => $itemCost,
            ]);
            $cost5 += $itemCost;

            $recipe5->update(['total_cost' => $cost5]);
        }
    }
}
