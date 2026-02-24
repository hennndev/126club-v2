<?php

namespace Database\Seeders;

use App\Models\Billing;
use App\Models\BomRecipe;
use App\Models\CustomerUser;
use App\Models\InventoryItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Printer;
use App\Models\Tabel;
use App\Models\TableSession;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PosTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test printers
        $this->seedPrinters();

        // Create test orders with items
        $this->seedTestOrders();

        $this->command->info('POS test data seeded successfully!');
    }

    /**
     * Seed test printers.
     */
    protected function seedPrinters(): void
    {
        $printers = [
            [
                'name' => 'Main Bar Printer',
                'location' => 'bar',
                'connection_type' => 'network',
                'ip' => '192.168.1.100',
                'port' => 9100,
                'timeout' => 30,
                'header' => '126 CLUB',
                'footer' => 'Thank you for visiting!',
                'width' => 32,
                'show_qr_code' => true,
                'is_default' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Kitchen Printer',
                'location' => 'kitchen',
                'connection_type' => 'network',
                'ip' => '192.168.1.101',
                'port' => 9100,
                'timeout' => 30,
                'header' => '126 CLUB - KITCHEN',
                'footer' => 'Order Ticket',
                'width' => 32,
                'show_qr_code' => false,
                'is_default' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Test Printer (File)',
                'location' => 'office',
                'connection_type' => 'file',
                'path' => storage_path('app/test-printer.txt'),
                'timeout' => 5,
                'header' => '126 CLUB - TEST',
                'footer' => 'Test Print',
                'width' => 32,
                'show_qr_code' => false,
                'is_default' => false,
                'is_active' => true,
            ],
        ];

        foreach ($printers as $printer) {
            Printer::create($printer);
        }

        $this->command->info('Test printers created.');
    }

    /**
     * Seed test orders for POS testing.
     */
    protected function seedTestOrders(): void
    {
        // Get existing data or create defaults
        $customer = CustomerUser::first();
        $table = Tabel::first();

        if (! $customer || ! $table) {
            $this->command->warn('No customer or table found. Please run customer and table seeders first.');

            return;
        }

        // Create a test table session
        $session = TableSession::create([
            'table_id' => $table->id,
            'customer_id' => $customer->user_id,
            'session_code' => 'SES-'.strtoupper(Str::random(8)),
            'check_in_qr_code' => strtoupper(Str::random(16)),
            'check_in_qr_expires_at' => now()->addHours(4),
            'checked_in_at' => now(),
            'status' => 'active',
        ]);

        // Create billing for the session
        $billing = Billing::create([
            'table_session_id' => $session->id,
            'minimum_charge' => $table->minimum_charge * 1000000, // Convert to Rupiah
            'orders_total' => 0,
            'subtotal' => $table->minimum_charge * 1000000,
            'tax_percentage' => 10,
            'tax' => ($table->minimum_charge * 1000000) * 0.1,
            'grand_total' => ($table->minimum_charge * 1000000) * 1.1,
            'billing_status' => 'draft',
        ]);

        $session->update(['billing_id' => $billing->id]);

        // Create test orders
        $orders = $this->createTestOrders($session);

        $this->command->info('Test orders created: '.count($orders));
    }

    /**
     * Create test orders with items.
     */
    protected function createTestOrders(TableSession $session): array
    {
        $orders = [];
        $staff = User::first();

        // Get BOM recipes (food/bar items)
        $bomRecipes = BomRecipe::with('inventoryItem')
            ->whereHas('inventoryItem', function ($q) {
                $q->whereIn('category_type', ['food', 'bar']);
            })
            ->limit(5)
            ->get();

        // Get drink items
        $drinkItems = InventoryItem::where('category_type', 'drink')
            ->limit(5)
            ->get();

        // Order 1: Food order
        if ($bomRecipes->isNotEmpty()) {
            $order1 = Order::create([
                'table_session_id' => $session->id,
                'created_by' => $staff?->id ?? 1,
                'order_number' => 'ORD-'.date('Ymd').'-0001',
                'status' => 'pending',
                'items_total' => 0,
                'discount_amount' => 0,
                'total' => 0,
                'ordered_at' => now(),
            ]);

            $itemsTotal1 = 0;
            foreach ($bomRecipes->take(3) as $bom) {
                $quantity = rand(1, 3);
                $price = $bom->selling_price;
                $subtotal = $price * $quantity;
                $itemsTotal1 += $subtotal;

                OrderItem::create([
                    'order_id' => $order1->id,
                    'inventory_item_id' => $bom->inventory_item_id,
                    'item_name' => $bom->inventoryItem->name ?? $bom->name,
                    'item_code' => $bom->inventoryItem->code ?? 'BOM-'.$bom->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'preparation_location' => in_array($bom->inventoryItem->category_type ?? 'bar', ['food']) ? 'kitchen' : 'bar',
                    'status' => 'pending',
                ]);
            }

            $order1->update([
                'items_total' => $itemsTotal1,
                'total' => $itemsTotal1,
            ]);

            $orders[] = $order1;
        }

        // Order 2: Drinks order
        if ($drinkItems->isNotEmpty()) {
            $order2 = Order::create([
                'table_session_id' => $session->id,
                'created_by' => $staff?->id ?? 1,
                'order_number' => 'ORD-'.date('Ymd').'-0002',
                'status' => 'pending',
                'items_total' => 0,
                'discount_amount' => 0,
                'total' => 0,
                'ordered_at' => now()->addMinutes(5),
            ]);

            $itemsTotal2 = 0;
            foreach ($drinkItems->take(3) as $item) {
                $quantity = rand(1, 5);
                $price = $item->unit_price ?? $item->price ?? 50000;
                $subtotal = $price * $quantity;
                $itemsTotal2 += $subtotal;

                OrderItem::create([
                    'order_id' => $order2->id,
                    'inventory_item_id' => $item->id,
                    'item_name' => $item->name,
                    'item_code' => $item->code ?? 'ITEM-'.$item->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'preparation_location' => 'bar',
                    'status' => 'pending',
                ]);
            }

            $order2->update([
                'items_total' => $itemsTotal2,
                'total' => $itemsTotal2,
            ]);

            $orders[] = $order2;
        }

        // Order 3: Mixed order (completed status for receipt testing)
        $order3 = Order::create([
            'table_session_id' => $session->id,
            'created_by' => $staff?->id ?? 1,
            'order_number' => 'ORD-'.date('Ymd').'-0003',
            'status' => 'completed',
            'items_total' => 0,
            'discount_amount' => 0,
            'total' => 0,
            'ordered_at' => now()->addMinutes(10),
            'completed_at' => now()->addMinutes(30),
        ]);

        $itemsTotal3 = 0;

        // Add some BOM items
        if ($bomRecipes->isNotEmpty()) {
            $bom = $bomRecipes->first();
            $quantity = 2;
            $price = $bom->selling_price;
            $subtotal = $price * $quantity;
            $itemsTotal3 += $subtotal;

            OrderItem::create([
                'order_id' => $order3->id,
                'inventory_item_id' => $bom->inventory_item_id,
                'item_name' => $bom->inventoryItem->name ?? $bom->name,
                'item_code' => $bom->inventoryItem->code ?? 'BOM-'.$bom->id,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'preparation_location' => 'bar',
                'status' => 'served',
                'served_at' => now()->addMinutes(25),
            ]);
        }

        // Add some drink items
        if ($drinkItems->isNotEmpty()) {
            foreach ($drinkItems->take(2) as $item) {
                $quantity = rand(1, 3);
                $price = $item->unit_price ?? $item->price ?? 50000;
                $subtotal = $price * $quantity;
                $itemsTotal3 += $subtotal;

                OrderItem::create([
                    'order_id' => $order3->id,
                    'inventory_item_id' => $item->id,
                    'item_name' => $item->name,
                    'item_code' => $item->code ?? 'ITEM-'.$item->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'discount_amount' => 0,
                    'preparation_location' => 'bar',
                    'status' => 'served',
                    'served_at' => now()->addMinutes(25),
                ]);
            }
        }

        $order3->update([
            'items_total' => $itemsTotal3,
            'total' => $itemsTotal3,
        ]);

        $orders[] = $order3;

        // Update billing totals
        $ordersTotal = collect($orders)->sum('total');
        $billing = $session->billing;
        $billing->orders_total = $ordersTotal;
        $billing->subtotal = $billing->minimum_charge + $ordersTotal;
        $billing->tax = $billing->subtotal * ($billing->tax_percentage / 100);
        $billing->grand_total = $billing->subtotal + $billing->tax - $billing->discount_amount;
        $billing->save();

        return $orders;
    }
}
