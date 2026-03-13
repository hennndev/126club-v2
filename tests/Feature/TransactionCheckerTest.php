<?php

use App\Models\Area;
use App\Models\InventoryItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Tabel;
use App\Models\TableSession;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('transaction checker page shows order cards for authenticated admin users', function () {
    $user = adminUser();
    $customer = User::factory()->create();

    $area = Area::create([
        'code' => 'VIP',
        'name' => 'VIP Room',
        'capacity' => 10,
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $table = Tabel::create([
        'area_id' => $area->id,
        'table_number' => 'A1',
        'qr_code' => 'QR-A1',
        'capacity' => 4,
        'status' => 'available',
        'is_active' => true,
    ]);

    $session = TableSession::create([
        'table_id' => $table->id,
        'customer_id' => $customer->id,
        'session_code' => 'SESSION-001',
        'status' => 'active',
    ]);

    $inventoryItem = InventoryItem::create([
        'code' => 'BEV-001',
        'accurate_id' => 1001,
        'name' => 'Es Teh Manis',
        'category_type' => 'beverage',
        'price' => 12500,
        'stock_quantity' => 20,
        'threshold' => 5,
        'unit' => 'glass',
        'is_active' => true,
        'item_produced' => false,
        'material_produced' => false,
    ]);

    $order = Order::create([
        'table_session_id' => $session->id,
        'created_by' => $user->id,
        'order_number' => 'ORD-TRX-001',
        'status' => 'pending',
        'items_total' => 25000,
        'discount_amount' => 0,
        'total' => 25000,
        'ordered_at' => now(),
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'inventory_item_id' => $inventoryItem->id,
        'item_name' => 'Es Teh Manis',
        'item_code' => 'BEV-001',
        'quantity' => 2,
        'price' => 12500,
        'subtotal' => 25000,
        'discount_amount' => 0,
        'preparation_location' => 'bar',
        'status' => 'pending',
    ]);

    $displayId = '#TRX-TODAY-'.$order->id;

    actingAs($user)
        ->withSession(['accurate_database' => 'test'])
        ->get(route('admin.transaction-checker.index'))
        ->assertOk()
        ->assertViewIs('transaction-checker.index')
        ->assertSee('Transaction Checker')
        ->assertSee($displayId)
        ->assertSee('Es Teh Manis')
        ->assertSee('Check All');
});
