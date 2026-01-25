<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarOrder;
use App\Models\BarOrderItem;
use App\Models\CustomerUser;
use App\Models\Tabel;
use App\Models\BomRecipe;

class BarOrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = CustomerUser::all();
        $tables = Tabel::all();
        $beverageRecipes = BomRecipe::where('type', 'beverage')->get();

        if ($customers->isEmpty() || $tables->isEmpty() || $beverageRecipes->isEmpty()) {
            $this->command->warn('Tidak dapat membuat bar orders. Pastikan ada customer, table, dan beverage recipes.');
            return;
        }

        // Create 10 bar orders with various statuses
        for ($i = 1; $i <= 10; $i++) {
            $customer = $customers->random();
            $table = $tables->random();
            
            // Determine status based on order number
            if ($i <= 5) {
                $status = 'baru';
                $progress = 0;
            } elseif ($i <= 8) {
                $status = 'proses';
                $progress = rand(20, 80);
            } else {
                $status = 'selesai';
                $progress = 100;
            }

            $order = BarOrder::create([
                'order_number' => 'BAR-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'customer_user_id' => $customer->id,
                'table_id' => $table->id,
                'total_amount' => 0, // Will be calculated
                'payment_method' => collect(['cash', 'credit-card', 'debit-card', 'e-wallet'])->random(),
                'status' => $status,
                'progress' => $progress
            ]);

            // Add 2-5 beverage items per order
            $itemCount = rand(2, 5);
            $totalAmount = 0;

            for ($j = 0; $j < $itemCount; $j++) {
                $recipe = $beverageRecipes->random();
                $quantity = rand(1, 3);
                $price = $recipe->selling_price;
                
                // Determine if item is completed based on order status
                $isCompleted = false;
                if ($status === 'selesai') {
                    $isCompleted = true;
                } elseif ($status === 'proses') {
                    // Randomly mark some items as completed
                    $isCompleted = rand(0, 100) < $progress;
                }

                BarOrderItem::create([
                    'bar_order_id' => $order->id,
                    'bom_recipe_id' => $recipe->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'is_completed' => $isCompleted
                ]);

                $totalAmount += $quantity * $price;
            }

            // Update total amount
            $order->update(['total_amount' => $totalAmount]);
        }

        $this->command->info('10 bar orders created successfully!');
    }
}
