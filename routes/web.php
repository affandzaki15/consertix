<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OrganizersController;
use App\Http\Controllers\Admin\ConcertsController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\TicketsController;
use App\Http\Controllers\Admin\ReportsController;

// Halaman Welcome
Route::get('/', function () {
    // Pass recent concerts to the welcome view so the event list can render.
    $concerts = \App\Models\Concert::orderBy('date', 'desc')->limit(8)->get();

    return view('welcome', compact('concerts'));
});

// Halaman Concerts
use App\Http\Controllers\ConcertController;
Route::get('/concerts', [ConcertController::class, 'index'])->name('concerts.index');
Route::get('/concerts/search', [ConcertController::class, 'search'])->name('concerts.search');
// Concert detail
Route::get('/concerts/{concert}', [ConcertController::class, 'show'])->name('concerts.show');

// ============================
// DASHBOARD MULTI-ROLE
// ============================
Route::middleware(['auth'])->group(function () {

    // Route dashboard untuk Admin
    Route::get('/admin/dashboard', function () {
        return view('dashboard.admin');
    })->name('admin.dashboard');

    // Route dashboard untuk EO
    Route::get('/eo/dashboard', function () {
        return view('dashboard.eo');
    })->name('eo.dashboard');

    // Route dashboard untuk User biasa
    Route::get('/user/dashboard', function () {
        return view('dashboard.user');
    })->name('user.dashboard');

    // Riwayat pesanan (history) untuk user yang sudah login
    Route::get('/history', function () {
        return view('history');
    })->name('history');
        Route::get('/concerts/{concert}/buy', [PurchaseController::class, 'show'])
        ->name('purchase.show');

    Route::post('/concerts/{concert}/buy', [PurchaseController::class, 'store'])
        ->name('purchase.store');
    Route::get('/purchase/{order}/detail', [PurchaseController::class, 'detail'])
    ->name('purchase.detail');

Route::post('/purchase/{order}/detail', [PurchaseController::class, 'processDetail'])
    ->name('purchase.processDetail');


});

// ============================
// PROFILE
// ============================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');

    // Organizers (EO approval)
    Route::get('/organizers/pending', [OrganizersController::class, 'pending'])->name('organizers.pending');
    Route::get('/organizers/{user}', [OrganizersController::class, 'show'])->name('organizers.show');
    Route::post('/organizers/{user}/approve', [OrganizersController::class, 'approve'])->name('organizers.approve');
    Route::post('/organizers/{user}/reject', [OrganizersController::class, 'reject'])->name('organizers.reject');

    // Concerts (approve/reject)
    Route::get('/concerts/pending', [ConcertsController::class, 'pending'])->name('concerts.pending');
    Route::get('/concerts/{concert}', [ConcertsController::class, 'show'])->name('concerts.show');
    Route::post('/concerts/{concert}/approve', [ConcertsController::class, 'approve'])->name('concerts.approve');
    Route::post('/concerts/{concert}/reject', [ConcertsController::class, 'reject'])->name('concerts.reject');

    // Orders & Payments
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/generate-tickets', [OrdersController::class, 'generateTickets'])->name('orders.generateTickets');

    Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [PaymentsController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/confirm', [PaymentsController::class, 'confirm'])->name('payments.confirm');
    Route::post('/payments/{payment}/refund', [PaymentsController::class, 'refund'])->name('payments.refund');

    // Tickets
    Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets.index');

    // Reports
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportsController::class, 'export'])->name('reports.export');
});
