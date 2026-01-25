<?php

use App\Http\Controllers\TableReservationController;
use Illuminate\Support\Facades\Route;

Route::resource('bookings', TableReservationController::class)->except(['show', 'create', 'edit']);
Route::patch('bookings/{booking}/status', [TableReservationController::class, 'updateStatus'])->name('bookings.updateStatus');
