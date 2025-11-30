<?php

use Illuminate\Support\Facades\Route;

// Controllers User & Public
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PagesController;

// Controllers EO
use App\Http\Controllers\Eo\EoDashboardController;
use App\Http\Controllers\Eo\EoConcertController;
use App\Http\Controllers\Eo\TicketTypeController;
use App\Http\Controllers\Eo\EoProfileController;

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

Route::get('/about', [PagesController::class, 'about'])->name('about');


// ============================
// USER AREA
// ============================
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user/dashboard', function () {
        return view('dashboard.user');
    })->name('user.dashboard');

    // History & Tickets routes
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/{order}', [HistoryController::class, 'show'])->name('history.show');
    Route::get('/history/{order}/print', [HistoryController::class, 'print'])->name('history.print');
    Route::get('/history/{order}/download', [HistoryController::class, 'downloadTicket'])->name('history.download');

    // Cart routes
    Route::get('/cart', [PurchaseController::class, 'cart'])->name('cart.show');
    Route::get('/cart/checkout/{concert}/{ticket_type_id?}', [PurchaseController::class, 'checkoutFromCart'])->name('cart.checkout');
    Route::get('/cart/count', [PurchaseController::class, 'cartCount'])->name('cart.count');
    Route::post('/cart/add', [PurchaseController::class, 'cartAdd'])->name('cart.add');
    Route::post('/cart/remove/{ticket_type_id}', [PurchaseController::class, 'cartRemove'])->name('cart.remove');
    Route::post('/cart/clear', [PurchaseController::class, 'cartClear'])->name('cart.clear');

    Route::get('/concerts/{concert}/buy', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/concerts/{concert}/buy', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::post('/purchase/clear-current', [PurchaseController::class, 'clearCurrent'])->name('purchase.clearCurrent');
    Route::get('/purchase/{order}/detail', [PurchaseController::class, 'detail'])->name('purchase.detail');
    Route::post('/purchase/{order}/detail', [PurchaseController::class, 'processDetail'])->name('purchase.processDetail');
    // Payment step
    Route::get('/purchase/{order}/payment', [PurchaseController::class, 'payment'])->name('purchase.payment');
    Route::post('/purchase/{order}/pay', [PurchaseController::class, 'pay'])->name('purchase.pay');
    // Payment confirmation
    Route::get('/purchase/{order}/confirmation', [PurchaseController::class, 'confirmation'])->name('purchase.confirmation');
    // Mark payment complete (called from confirmation modal)
    Route::post('/purchase/{order}/complete', [PurchaseController::class, 'completePayment'])->name('purchase.complete');
    // Cancel order
    Route::post('/purchase/{order}/cancel', [PurchaseController::class, 'cancelOrder'])->name('purchase.cancel');

});


// ============================
// EO AREA (Event Organizer)
Route::middleware(['auth', 'role:eo'])
    ->prefix('eo')
    ->name('eo.')
    ->group(function () {

        // Dashboard EO
        Route::get('/dashboard', [EoDashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/profile', [EoProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::post('/profile', [EoProfileController::class, 'update'])
            ->name('profile.update');


        // CRUD Concert EO
        Route::resource('concerts', EoConcertController::class);

        // CRUD Ticket Types EO
        Route::resource('concerts.tickets', TicketTypeController::class)
            ->shallow()
            ->except(['show']);

        // Halaman Review Approval (setelah tiket selesai dibuat)
        Route::get('/concerts/{id}/review', [EoConcertController::class, 'review'])
            ->name('concerts.review');

        // Submit ke Admin untuk approval
        Route::post('/concerts/{id}/submit', [EoConcertController::class, 'submitApproval'])
            ->name('concerts.submit');
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



// AUTH ROUTES
require __DIR__ . '/auth.php';
