<?php

use App\Http\Controllers\Waiter\WaiterController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WaiterController::class, 'index'])->name('index');
Route::get('/pos', [WaiterController::class, 'pos'])->name('pos');
Route::get('/active-tables', [WaiterController::class, 'activeTables'])->name('active-tables');
Route::get('/scanner', [WaiterController::class, 'scanner'])->name('scanner');
Route::get('/notifications', [WaiterController::class, 'notifications'])->name('notifications');
Route::get('/settings', [WaiterController::class, 'settings'])->name('settings');
