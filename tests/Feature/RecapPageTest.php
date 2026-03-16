<?php

use App\Models\BarOrder;
use App\Models\BarOrderItem;
use App\Models\InventoryItem;
use App\Models\KitchenOrder;
use App\Models\KitchenOrderItem;
use App\Models\Order;
use App\Models\OrderItem;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

function makeRecapInventoryItem(array $attributes = []): InventoryItem
{
    return InventoryItem::create(array_merge([
        'code' => 'RCP-ITEM-'.uniqid(),
        'accurate_id' => random_int(100000, 999999),
        'name' => 'Recap Item '.uniqid(),
        'category_type' => 'food',
        'price' => 15000,
        'stock_quantity' => 100,
        'threshold' => 5,
        'unit' => 'unit',
        'is_active' => true,
    ], $attributes));
}

function makeRecapOrder(int $createdById, \Illuminate\Support\Carbon $orderedAt, string $orderNumber): Order
{
    return Order::create([
        'table_session_id' => null,
        'customer_user_id' => null,
        'created_by' => $createdById,
        'order_number' => $orderNumber,
        'status' => 'completed',
        'items_total' => 30000,
        'discount_amount' => 0,
        'total' => 30000,
        'ordered_at' => $orderedAt,
    ]);
}

test('admin can open recap page', function () {
    $admin = adminUser();
    $start = now()->startOfDay()->addHours(8);
    $end = now()->startOfDay()->addHours(23)->addMinutes(59);

    actingAs($admin)
        ->get(route('admin.recap.index', [
            'start_datetime' => $start->format('Y-m-d\TH:i'),
            'end_datetime' => $end->format('Y-m-d\TH:i'),
        ]))
        ->assertSuccessful()
        ->assertViewIs('recap.index')
        ->assertSeeText('Filter Rekapan')
        ->assertSeeText('Mulai')
        ->assertSeeText('Sampai')
        ->assertSeeText('Export Excel (.xlsx)')
        ->assertSeeText('Transaksi Kasir')
        ->assertSeeText('Item Keluar Kitchen')
        ->assertSeeText('Item Keluar Bar')
        ->assertSee(route('admin.recap.export', [
            'start_datetime' => $start->format('Y-m-d\TH:i'),
            'end_datetime' => $end->format('Y-m-d\TH:i'),
        ]))
        ->assertDontSeeText('Timeline Kejadian');
});

test('recap page filters cashier kitchen and bar events by selected datetime range', function () {
    $admin = adminUser();

    $today = now()->startOfDay()->addHours(10);
    $yesterday = now()->subDay()->startOfDay()->addHours(11);
    $rangeStart = now()->startOfDay()->addHours(9);
    $rangeEnd = now()->startOfDay()->addHours(23);

    $todayOrder = makeRecapOrder($admin->id, $today, 'RCP-TODAY-001');
    $yesterdayOrder = makeRecapOrder($admin->id, $yesterday, 'RCP-YEST-001');

    $foodToday = makeRecapInventoryItem([
        'name' => 'Nasi Goreng Recap',
        'category_type' => 'food',
    ]);
    $foodYesterday = makeRecapInventoryItem([
        'name' => 'Mie Goreng Lama',
        'category_type' => 'food',
    ]);
    $drinkToday = makeRecapInventoryItem([
        'name' => 'Es Teh Recap',
        'category_type' => 'beverage',
    ]);
    $drinkYesterday = makeRecapInventoryItem([
        'name' => 'Jus Lama',
        'category_type' => 'beverage',
    ]);

    OrderItem::create([
        'order_id' => $todayOrder->id,
        'inventory_item_id' => $foodToday->id,
        'item_name' => $foodToday->name,
        'item_code' => $foodToday->code,
        'quantity' => 2,
        'price' => 15000,
        'subtotal' => 30000,
        'discount_amount' => 0,
        'preparation_location' => 'kitchen',
        'status' => 'served',
    ]);

    $kitchenOrderToday = KitchenOrder::create([
        'order_id' => $todayOrder->id,
        'order_number' => $todayOrder->order_number,
        'customer_user_id' => null,
        'table_id' => null,
        'total_amount' => 15000,
        'status' => 'selesai',
        'progress' => 100,
    ]);
    $kitchenOrderToday->forceFill(['created_at' => $today, 'updated_at' => $today])->save();

    KitchenOrderItem::create([
        'kitchen_order_id' => $kitchenOrderToday->id,
        'inventory_item_id' => $foodToday->id,
        'quantity' => 1,
        'price' => 15000,
        'is_completed' => true,
    ]);

    $kitchenOrderYesterday = KitchenOrder::create([
        'order_id' => $yesterdayOrder->id,
        'order_number' => $yesterdayOrder->order_number,
        'customer_user_id' => null,
        'table_id' => null,
        'total_amount' => 15000,
        'status' => 'selesai',
        'progress' => 100,
    ]);
    $kitchenOrderYesterday->forceFill(['created_at' => $yesterday, 'updated_at' => $yesterday])->save();

    KitchenOrderItem::create([
        'kitchen_order_id' => $kitchenOrderYesterday->id,
        'inventory_item_id' => $foodYesterday->id,
        'quantity' => 1,
        'price' => 15000,
        'is_completed' => true,
    ]);

    $barOrderToday = BarOrder::create([
        'order_id' => $todayOrder->id,
        'order_number' => $todayOrder->order_number,
        'customer_user_id' => null,
        'table_id' => null,
        'total_amount' => 15000,
        'payment_method' => 'cash',
        'status' => 'selesai',
        'progress' => 100,
    ]);
    $barOrderToday->forceFill(['created_at' => $today, 'updated_at' => $today])->save();

    BarOrderItem::create([
        'bar_order_id' => $barOrderToday->id,
        'inventory_item_id' => $drinkToday->id,
        'quantity' => 1,
        'price' => 15000,
        'is_completed' => true,
    ]);

    $barOrderYesterday = BarOrder::create([
        'order_id' => $yesterdayOrder->id,
        'order_number' => $yesterdayOrder->order_number,
        'customer_user_id' => null,
        'table_id' => null,
        'total_amount' => 15000,
        'payment_method' => 'cash',
        'status' => 'selesai',
        'progress' => 100,
    ]);
    $barOrderYesterday->forceFill(['created_at' => $yesterday, 'updated_at' => $yesterday])->save();

    BarOrderItem::create([
        'bar_order_id' => $barOrderYesterday->id,
        'inventory_item_id' => $drinkYesterday->id,
        'quantity' => 1,
        'price' => 15000,
        'is_completed' => true,
    ]);

    actingAs($admin)
        ->get(route('admin.recap.index', [
            'start_datetime' => $rangeStart->format('Y-m-d\TH:i'),
            'end_datetime' => $rangeEnd->format('Y-m-d\TH:i'),
        ]))
        ->assertSuccessful()
        ->assertSee('RCP-TODAY-001')
        ->assertSee($today->format('d/m/Y H:i'))
        ->assertDontSee('RCP-YEST-001')
        ->assertSee('Nasi Goreng Recap')
        ->assertDontSee('Mie Goreng Lama')
        ->assertSee('Es Teh Recap')
        ->assertDontSee('Jus Lama')
        ->assertSee('Rp 30.000');
});

test('recap export returns native xlsx file', function () {
    $admin = adminUser();
    $start = now()->startOfDay()->addHours(8);
    $end = now()->startOfDay()->addHours(23)->addMinutes(59);

    $order = makeRecapOrder($admin->id, now(), 'RCP-EXPORT-001');
    $item = makeRecapInventoryItem(['name' => 'Export Item']);

    OrderItem::create([
        'order_id' => $order->id,
        'inventory_item_id' => $item->id,
        'item_name' => $item->name,
        'item_code' => $item->code,
        'quantity' => 1,
        'price' => 15000,
        'subtotal' => 15000,
        'discount_amount' => 0,
        'preparation_location' => 'kitchen',
        'status' => 'served',
    ]);

    $response = actingAs($admin)
        ->get(route('admin.recap.export', [
            'start_datetime' => $start->format('Y-m-d\TH:i'),
            'end_datetime' => $end->format('Y-m-d\TH:i'),
        ]));

    $response
        ->assertSuccessful()
        ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
        ->assertHeader('content-disposition', 'attachment; filename=rekapan-'.$start->format('Ymd_Hi').'-'.$end->format('Ymd_Hi').'.xlsx');
});

test('user without recap permission cannot access recap route', function () {
    $user = \App\Models\User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Cashier']);
    $user->assignRole($role);

    actingAs($user)
        ->get(route('admin.recap.index'))
        ->assertForbidden();
});

test('user with recap permission can access recap route', function () {
    $user = \App\Models\User::factory()->create();
    $role = Role::firstOrCreate(['name' => 'Cashier']);
    $permission = Permission::firstOrCreate(['name' => 'admin.recap.*', 'guard_name' => 'web']);
    $role->givePermissionTo($permission);
    $user->assignRole($role);

    actingAs($user)
        ->get(route('admin.recap.index'))
        ->assertSuccessful();
});
