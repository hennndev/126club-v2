<?php

use App\Models\Printer;
use App\Services\PrinterService;
use Mockery\MockInterface;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\mock;

test('pos test print prefers cashier type printer when no printer id is provided', function () {
    $admin = adminUser();

    $defaultKitchenPrinter = Printer::create([
        'name' => 'Default Kitchen Printer',
        'location' => 'kitchen',
        'printer_type' => 'kitchen',
        'connection_type' => 'log',
        'port' => 9100,
        'timeout' => 30,
        'header' => '126 Club',
        'footer' => 'Thank you',
        'width' => 42,
        'is_default' => true,
        'is_active' => true,
    ]);

    $cashierPrinter = Printer::create([
        'name' => 'Cashier Printer',
        'location' => 'cashier',
        'printer_type' => 'cashier',
        'connection_type' => 'log',
        'port' => 9100,
        'timeout' => 30,
        'header' => '126 Club',
        'footer' => 'Thank you',
        'width' => 42,
        'is_default' => false,
        'is_active' => true,
    ]);

    mock(PrinterService::class, function (MockInterface $mock) use ($cashierPrinter): void {
        $mock->shouldReceive('testPrint')
            ->once()
            ->withArgs(fn (Printer $printer): bool => (int) $printer->id === (int) $cashierPrinter->id)
            ->andReturnTrue();
    });

    actingAs($admin)
        ->postJson(route('admin.pos.test-print'))
        ->assertSuccessful()
        ->assertJsonPath('success', true);

    expect($defaultKitchenPrinter->id)->not->toBe($cashierPrinter->id);
});
