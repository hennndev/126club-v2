<?php

use App\Models\Area;
use App\Models\CustomerUser;
use App\Models\InventoryItem;
use App\Models\Order;
use App\Models\Tabel;
use App\Models\TableSession;
use App\Models\User;
use App\Models\UserProfile;
use App\Services\AccurateService;
use Mockery\MockInterface;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\mock;

function makePosInventoryItem(array $attributes = []): InventoryItem
{
    return InventoryItem::create(array_merge([
        'code' => 'POS-ITEM-'.uniqid(),
        'accurate_id' => random_int(100000, 999999),
        'name' => 'POS Item '.uniqid(),
        'category_type' => 'beverage',
        'price' => 25000,
        'stock_quantity' => 20,
        'threshold' => 5,
        'unit' => 'glass',
        'is_active' => true,
    ], $attributes));
}

function makePosArea(): Area
{
    return Area::create([
        'code' => 'POS-AREA-'.uniqid(),
        'name' => 'POS Area '.uniqid(),
        'is_active' => true,
        'sort_order' => 1,
    ]);
}

function makePosTable(Area $area): Tabel
{
    return Tabel::create([
        'area_id' => $area->id,
        'table_number' => 'POS-TBL-'.uniqid(),
        'qr_code' => 'POS-QR-'.uniqid(),
        'capacity' => 4,
        'minimum_charge' => 0,
        'status' => 'occupied',
        'is_active' => true,
    ]);
}

test('booking checkout decrements inventory stock', function () {
    $admin = adminUser();
    $customer = User::factory()->create();
    $area = makePosArea();
    $table = makePosTable($area);
    $inventoryItem = makePosInventoryItem(['stock_quantity' => 10]);

    TableSession::create([
        'table_id' => $table->id,
        'customer_id' => $customer->id,
        'session_code' => 'SESSION-'.uniqid(),
        'checked_in_at' => now(),
        'status' => 'active',
    ]);

    $cartKey = 'item_'.$inventoryItem->id;

    $response = actingAs($admin)
        ->withSession([
            'pos_cart' => [
                $cartKey => [
                    'id' => $cartKey,
                    'name' => $inventoryItem->name,
                    'price' => (float) $inventoryItem->price,
                    'quantity' => 3,
                    'preparation_location' => 'kitchen',
                ],
            ],
        ])
        ->postJson(route('admin.pos.checkout'), [
            'customer_type' => 'booking',
            'customer_user_id' => $customer->id,
            'table_id' => $table->id,
            'discount_percentage' => 0,
        ]);

    $response
        ->assertSuccessful()
        ->assertJsonPath('success', true);

    expect($inventoryItem->fresh()->stock_quantity)->toBe(7);
});

test('walk in checkout decrements inventory stock and syncs accurate documents', function () {
    $admin = adminUser();
    $customer = User::factory()->create();
    $profile = UserProfile::create([
        'user_id' => $customer->id,
        'phone' => '08123456789',
    ]);

    $customerUser = CustomerUser::create([
        'user_id' => $customer->id,
        'user_profile_id' => $profile->id,
        'accurate_id' => null,
        'customer_code' => null,
        'total_visits' => 0,
        'lifetime_spending' => 0,
    ]);

    mock(AccurateService::class, function (MockInterface $mock): void {
        $mock->shouldReceive('saveCustomer')
            ->once()
            ->andReturn([
                'r' => [
                    'id' => 98765,
                    'customerNo' => 'CUST-WALKIN-001',
                ],
            ]);

        $mock->shouldReceive('saveSalesOrder')
            ->once()
            ->andReturn([
                'r' => [
                    'number' => 'SO-WALKIN-001',
                ],
            ]);

        $mock->shouldReceive('saveSalesInvoice')
            ->once()
            ->andReturn([
                'r' => [
                    'number' => 'INV-WALKIN-001',
                ],
            ]);
    });

    $inventoryItem = makePosInventoryItem(['stock_quantity' => 8]);
    $cartKey = 'item_'.$inventoryItem->id;

    $response = actingAs($admin)
        ->withSession([
            'pos_cart' => [
                $cartKey => [
                    'id' => $cartKey,
                    'name' => $inventoryItem->name,
                    'price' => (float) $inventoryItem->price,
                    'quantity' => 2,
                    'preparation_location' => 'kitchen',
                ],
            ],
        ])
        ->postJson(route('admin.pos.checkout'), [
            'customer_type' => 'walk-in',
            'walk_in_customer_id' => $customer->id,
            'discount_percentage' => 0,
        ]);

    $response
        ->assertSuccessful()
        ->assertJsonPath('success', true);

    $order = Order::query()->latest('id')->first();

    expect($inventoryItem->fresh()->stock_quantity)->toBe(6)
        ->and($customerUser->fresh()->customer_code)->toBe('CUST-WALKIN-001')
        ->and($customerUser->fresh()->accurate_id)->toBe(98765)
        ->and($order)->not->toBeNull()
        ->and($order->accurate_so_number)->toBe('SO-WALKIN-001')
        ->and($order->accurate_inv_number)->toBe('INV-WALKIN-001');
});
