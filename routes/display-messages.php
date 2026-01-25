<?php

use App\Http\Controllers\DisplayMessageRequestController;
use illuminate\Support\Facades\Route;

Route::resource('display-messages', DisplayMessageRequestController::class)->except(['show', 'create', 'edit']);
Route::patch('display-messages/{displayMessage}/status', [DisplayMessageRequestController::class, 'updateStatus'])->name('display-messages.updateStatus');
