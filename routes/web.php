<?php

use Illuminate\Support\Facades\Route;

// Controllers User & Public
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProfileController;

// Controllers EO
use App\Http\Controllers\Eo\EoDashboardController;
use App\Http\Controllers\Eo\EoConcertController;
use App\Http\Controllers\Eo\TicketTypeController;

// Controllers Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OrganizersController;
use App\Http\Controllers\Admin\ConcertsController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\TicketsController;
use App\Http\Controllers\Admin\ReportsController;


// ============================
// PUBLIC PAGES
// ============================

Route::get('/', function () {
    $concerts = \App\Models\Concert::orderBy('date', 'desc')->limit(8)->get();
    return view('welcome', compact('concerts'));
});

Route::get('/concerts', [ConcertController::class, 'index'])->name('concerts.index');

Route::get('/concerts/search', [ConcertController::class, 'search'])
    ->name('concerts.search');

Route::get('/concerts/{concert}', [ConcertController::class, 'show'])->name('concerts.show');


// ============================
// USER AREA
// ============================
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user/dashboard', function () {
        return view('dashboard.user');
    })->name('user.dashboard');

    Route::get('/history', function () {
        return view('history');
    })->name('history');

    Route::get('/concerts/{concert}/buy', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/concerts/{concert}/buy', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/{order}/detail', [PurchaseController::class, 'detail'])->name('purchase.detail');
    Route::post('/purchase/{order}/detail', [PurchaseController::class, 'processDetail'])->name('purchase.processDetail');
    // Payment step
    Route::get('/purchase/{order}/payment', [PurchaseController::class, 'payment'])->name('purchase.payment');
    Route::post('/purchase/{order}/pay', [PurchaseController::class, 'pay'])->name('purchase.pay');
    // Payment confirmation
    Route::get('/purchase/{order}/confirmation', [PurchaseController::class, 'confirmation'])->name('purchase.confirmation');
});


// ============================
// EO AREA (Event Organizer)
Route::middleware(['auth', 'role:eo'])->prefix('eo')->name('eo.')->group(function () {

    Route::get('/dashboard', [EoDashboardController::class, 'index'])->name('dashboard');

    Route::resource('concerts', EoConcertController::class);
    Route::resource('concerts.tickets', TicketTypeController::class)
        ->shallow()
        ->except(['show']);

    Route::get(
        '/eo/concerts/{id}/approval',
        [EoConcertController::class, 'approvalPage']
    )
        ->name('eo.concerts.approval');

    Route::post(
        '/eo/concerts/{id}/submit-approval',
        [EoConcertController::class, 'submitApproval']
    )
        ->name('eo.concerts.submitApproval');
});



// ============================
// ADMIN AREA
// ============================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');

    // EO Approval
    Route::get('/organizers/pending', [OrganizersController::class, 'pending'])->name('organizers.pending');
    Route::get('/organizers/{user}', [OrganizersController::class, 'show'])->name('organizers.show');
    Route::post('/organizers/{user}/approve', [OrganizersController::class, 'approve'])->name('organizers.approve');
    Route::post('/organizers/{user}/reject', [OrganizersController::class, 'reject'])->name('organizers.reject');

    // Concert Approval
    Route::get('/concerts/pending', [ConcertsController::class, 'pending'])->name('concerts.pending');
    Route::get('/concerts/{concert}', [ConcertsController::class, 'show'])->name('concerts.show');
    Route::post('/concerts/{concert}/approve', [ConcertsController::class, 'approve'])->name('concerts.approve');
    Route::post('/concerts/{concert}/reject', [ConcertsController::class, 'reject'])->name('concerts.reject');

    // Orders & Payment
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/generate-tickets', [OrdersController::class, 'generateTickets'])->name('orders.generateTickets');

    Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [PaymentsController::class, 'show'])->name('payments.show');
    Route::post('/payments/{payment}/confirm', [PaymentsController::class, 'confirm'])->name('payments.confirm');
    Route::post('/payments/{payment}/refund', [PaymentsController::class, 'refund'])->name('payments.refund');

    // Ticket Monitor
    Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets.index');

    // Report
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
    Route::post('/reports/export', [ReportsController::class, 'export'])->name('reports.export');
});

// Toggle active/inactive user
Route::middleware(['auth', 'admin'])->group(function () {
    Route::patch('admin/users/{user}/toggle', [UsersController::class, 'toggle'])
        ->name('admin.users.toggle');
});

// PROFILE ROUTES
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// AUTH ROUTES
require __DIR__ . '/auth.php';
