<?php

use App\Http\Controllers\DatabaseSelectionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccurateController;


Route::get('/select-database', [
  DatabaseSelectionController::class,
  'showSelection',
])->name('database.selection');
Route::post('/select-database', [
  DatabaseSelectionController::class,
  'selectDatabase',
])->name('database.select');
Route::post('/accurate/disconnect', [
  AccurateController::class,
  'disconnect',
])->name('accurate.disconnect');
Route::get('/accurate/auth', [
  AccurateController::class,
  'redirectToAccurate',
])->name('accurate.auth');
Route::get('/accurate/callback', [
  AccurateController::class,
  'handleCallback',
])->name('accurate.callback');
