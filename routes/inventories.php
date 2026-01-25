<?php

use App\Http\Controllers\InventoryController;
use illuminate\Support\Facades\Route;

Route::resource('inventory', InventoryController::class)->except(['show', 'create', 'edit']);
Route::post('inventory/update-threshold', [InventoryController::class, 'updateThreshold'])->name('inventory.updateThreshold');
