<?php

use App\Http\Controllers\SongRequestController;
use illuminate\Support\Facades\Route;


Route::resource('song-requests', SongRequestController::class)->except(['show', 'create', 'edit']);
Route::patch('song-requests/{songRequest}/status', [SongRequestController::class, 'updateStatus'])->name('song-requests.updateStatus');
