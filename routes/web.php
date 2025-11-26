<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\eo\EoDashboardController;
use App\Http\Controllers\eo\EoConcertController;
use App\Http\Controllers\TicketTypeController;

// ============================
// HALAMAN USER PUBLIC
// ============================

// Halaman Welcome + Concert terbaru
Route::get('/', function () {
    $concerts = \App\Models\Concert::orderBy('date', 'desc')->limit(8)->get();
    return view('welcome', compact('concerts'));
});

// List & Detail Concerts
Route::get('/concerts', [ConcertController::class, 'index'])->name('concerts.index');
Route::get('/concerts/search', [ConcertController::class, 'search'])->name('concerts.search');
Route::get('/concerts/{concert}', [ConcertController::class, 'show'])->name('concerts.show');


// ============================
// DASHBOARD MULTI ROLE
// ============================

// Admin Area
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');
});

// EO Area (Event Organizer)
Route::middleware(['auth', 'role:eo'])->prefix('eo')->group(function () {
    Route::get('/dashboard', [EoDashboardController::class, 'index'])->name('eo.dashboard');

    Route::resource('concerts', EoConcertController::class);

    Route::resource('concerts.tickets', TicketTypeController::class)
        ->shallow()
        ->except(['show']);
});

// User Area (Pembelian Tiket)
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('dashboard.user');
    })->name('user.dashboard');

    // Riwayat pembelian tiket
    Route::get('/history', function () {
        return view('history');
    })->name('history');

    // Proses pembelian tiket
    Route::get('/concerts/{concert}/buy', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/concerts/{concert}/buy', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/{order}/detail', [PurchaseController::class, 'detail'])->name('purchase.detail');
    Route::post('/purchase/{order}/detail', [PurchaseController::class, 'processDetail'])->name('purchase.processDetail');
});


// ============================
// PROFILE
// ============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// AUTH ROUTES
require __DIR__.'/auth.php';
