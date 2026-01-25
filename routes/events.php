<?php

use App\Http\Controllers\EventController;
use illuminate\Support\Facades\Route;

Route::resource('events', EventController::class)->except(['show', 'create', 'edit']);
Route::patch('events/{event}/toggle-status', [EventController::class, 'toggleStatus'])->name('events.toggleStatus');
