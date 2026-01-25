<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KitchenOrder;
use App\Models\KitchenOrderItem;
use App\Models\CustomerUser;
use App\Models\Tabel;
use App\Models\BomRecipe;
use Carbon\Carbon;

class KitchenOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = CustomerUser::with('profile')->take(5)->get();
        $tables = Tabel::with('area')->take(5)->get();
        $foodRecipes = BomRecipe::where('type', 'food')->get();

        if ($customers->isEmpty() || $tables->isEmpty() || $foodRecipes->isEmpty()) {
            $this->command->warn('Need customers, tables, and food recipes to seed kitchen orders');
            return;
        }

        $orders = [
            // Order 1 - SELESAI (100%)
            [
                'order_number' => '#TRX-TODAY-1',
                'customer' => $customers[0],
                'table' => $tables[0],
                'payment_method' => 'credit-card',
                'status' => 'selesai',
                'progress' => 100,
                'time' => Carbon::today()->setTime(20, 42),
                'items' => [
                    ['recipe' => $foodRecipes[0], 'quantity' => 2, 'completed' => true],
                ],
            ],
            // Order 2 - SELESAI (100%)
            [
                'order_number' => '#TRX-TODAY-2',
                'customer' => $customers[1],
                'table' => $tables[1],
                'payment_method' => 'credit-card',
                'status' => 'selesai',
                'progress' => 100,
                'time' => Carbon::today()->setTime(20, 42),
                'items' => [
                    ['recipe' => $foodRecipes[0], 'quantity' => 1, 'completed' => true],
                ],
            ],
            // Order 3 - SELESAI (100%)
            [
                'order_number' => '#TRX-TODAY-3',
                'customer' => $customers[2],
                'table' => $tables[2],
                'payment_method' => 'debit-card',
                'status' => 'selesai',
                'progress' => 100,
                'time' => Carbon::today()->setTime(20, 42),
                'items' => [
                    ['recipe' => $foodRecipes[0], 'quantity' => 1, 'completed' => true],
                ],
            ],
            // Order 4 - BARU (0%)
            [
                'order_number' => '#TRX-TODAY-4',
                'customer' => $customers[3],
                'table' => $tables[3],
                'payment_method' => 'credit-card',
                'status' => 'baru',
                'progress' => 0,
                'time' => Carbon::today()->setTime(20, 42),
                'items' => [
                    ['recipe' => $foodRecipes[0], 'quantity' => 1, 'completed' => false],
                ],
            ],
            // Order 5 - BARU (0%)
            [
                'order_number' => '#TRX-TODAY-5',
                'customer' => $customers[4],
                'table' => $tables[4],
                'payment_method' => 'cash',
                'status' => 'baru',
                'progress' => 0,
                'time' => Carbon::today()->setTime(20, 42),
                'items' => [
                    ['recipe' => $foodRecipes[0], 'quantity' => 1, 'completed' => false],
                ],
            ],
        ];

        foreach ($orders as $orderData) {
            $totalAmount = 0;
            foreach ($orderData['items'] as $item) {
                $totalAmount += $item['recipe']->selling_price * $item['quantity'];
            }

            $order = KitchenOrder::create([
                'order_number' => $orderData['order_number'],
                'customer_user_id' => $orderData['customer']->id,
                'table_id' => $orderData['table']->id,
                'total_amount' => $totalAmount,
                'payment_method' => $orderData['payment_method'],
                'status' => $orderData['status'],
                'progress' => $orderData['progress'],
                'created_at' => $orderData['time'],
                'updated_at' => $orderData['time'],
            ]);

            foreach ($orderData['items'] as $item) {
                KitchenOrderItem::create([
                    'kitchen_order_id' => $order->id,
                    'bom_recipe_id' => $item['recipe']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['recipe']->selling_price,
                    'is_completed' => $item['completed'],
                ]);
            }
        }

        $this->command->info('Kitchen orders seeded successfully!');
    }
}

