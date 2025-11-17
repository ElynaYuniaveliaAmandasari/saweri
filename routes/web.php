<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Berikut adalah route utama untuk aplikasi kamu.
| - Halaman donasi: /user/{username}
| - Submit donasi: /donate
| - Hasil sukses: /donate/success
| - Dashboard & Profile: hanya untuk user login
|--------------------------------------------------------------------------
*/

// 💚 Halaman donasi publik (bisa diakses siapa saja)
Route::get('/user/{username}', [DonationController::class, 'index'])
    ->name('donations.index');

// 💸 Proses donasi ke Xendit
Route::post('/donate', [DonationController::class, 'store'])
    ->name('donations.store');

// ✅ Halaman sukses setelah pembayaran
Route::get('/donate/success/{id}', [DonationController::class, 'success'])->name('donations.success');
// ❌ Halaman gagal (opsional, bisa tambahkan nanti)
Route::get('/donate/failed', function () {
    return view('donation-failed');
})->name('donations.failed');

// 🏠 Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// 📊 Dashboard (hanya untuk user login & verified)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 👤 Route untuk profile user (edit/update/delete)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔐 Auth routes (login, register, dll)
require __DIR__.'/auth.php';
