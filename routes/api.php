<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ✅ UBAH DARI: /xendit-callback MENJADI: /xendit/callback
Route::post('/xendit/callback', [DonationController::class, 'callbackXendit'])
    ->name('callback-xendit');