<?php

use Illuminate\Support\Facades\Route;

// Controllers User & Public
use App\Http\Controllers\ConcertController;
use App\Http\Controllers\OrganizerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ContactController;

// Controllers EO
use App\Http\Controllers\Eo\EoDashboardController;
use App\Http\Controllers\Eo\EoConcertController;
use App\Http\Controllers\Eo\TicketTypeController;
use App\Http\Controllers\Eo\EoProfileController;
use App\Http\Controllers\Eo\EoVoucherController;

// Controllers Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OrganizersController;
use App\Http\Controllers\Admin\ConcertsController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\TicketsController;
use App\Http\Controllers\Admin\ReportsController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $concerts = \App\Models\Concert::orderBy('date', 'desc')->limit(8)->get();
    return view('welcome', compact('concerts'));
});

Route::get('/concerts', [ConcertController::class, 'index'])->name('concerts.index');
Route::get('/concerts/search', [ConcertController::class, 'search'])->name('concerts.search');
Route::get('/concerts/{concert}', [ConcertController::class, 'show'])->name('concerts.show');
Route::get('/organizers/{organizer}', [OrganizerController::class, 'show'])->name('organizers.show');
Route::get('/about', [PagesController::class, 'about'])->name('about');
// Contact page
Route::view('/contact', 'pages.contact')->name('contact');
// Contact form POST
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

/*
|--------------------------------------------------------------------------
| User Routes (role: user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('dashboard.user');
    })->name('user.dashboard');

    // History & Tickets
    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/history/{order}', [HistoryController::class, 'show'])->name('history.show');
    Route::get('/history/{order}/print', [HistoryController::class, 'print'])->name('history.print');
    Route::get('/history/{order}/download', [HistoryController::class, 'downloadTicket'])->name('history.download');

    // Cart
    Route::get('/cart', [PurchaseController::class, 'cart'])->name('cart.show');
    Route::get('/cart/checkout/{concert}/{ticket_type_id?}', [PurchaseController::class, 'checkoutFromCart'])->name('cart.checkout');
    Route::get('/cart/count', [PurchaseController::class, 'cartCount'])->name('cart.count');
    Route::post('/cart/add', [PurchaseController::class, 'cartAdd'])->name('cart.add');
    Route::post('/cart/remove/{ticket_type_id}', [PurchaseController::class, 'cartRemove'])->name('cart.remove');
    Route::post('/cart/clear', [PurchaseController::class, 'cartClear'])->name('cart.clear');

    // Purchase Flow
    Route::get('/concerts/{concert}/buy', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/concerts/{concert}/buy', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::post('/purchase/clear-current', [PurchaseController::class, 'clearCurrent'])->name('purchase.clearCurrent');
    Route::get('/purchase/{order}/detail', [PurchaseController::class, 'detail'])->name('purchase.detail');
    Route::post('/purchase/{order}/detail', [PurchaseController::class, 'processDetail'])->name('purchase.processDetail');
    Route::get('/purchase/{order}/payment', [PurchaseController::class, 'payment'])->name('purchase.payment');
    Route::post('/purchase/{order}/pay', [PurchaseController::class, 'pay'])->name('purchase.pay');
    Route::get('/purchase/{order}/confirmation', [PurchaseController::class, 'confirmation'])->name('purchase.confirmation');
    Route::post('/purchase/{order}/complete', [PurchaseController::class, 'completePayment'])->name('purchase.complete');
    Route::post('/purchase/{order}/cancel', [PurchaseController::class, 'cancelOrder'])->name('purchase.cancel');
    
    // Voucher
    Route::post('/purchase/{order}/apply-voucher', [PurchaseController::class, 'applyVoucher'])->name('purchase.applyVoucher');
    Route::post('/purchase/{order}/remove-voucher', [PurchaseController::class, 'removeVoucher'])->name('purchase.removeVoucher');
});

/*
|--------------------------------------------------------------------------
| EO (Event Organizer) Routes (role: eo)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:eo'])
    ->prefix('eo')
    ->name('eo.')
    ->group(function () {
        Route::get('/dashboard', [EoDashboardController::class, 'index'])->name('dashboard');
        Route::get('/profile', [EoProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [EoProfileController::class, 'update'])->name('profile.update');

        // Concerts
        Route::resource('concerts', EoConcertController::class);
        Route::get('/concerts/{id}/review', [EoConcertController::class, 'review'])->name('concerts.review');
        Route::post('/concerts/{id}/submit', [EoConcertController::class, 'submitApproval'])->name('concerts.submit');

        // Ticket Types
        Route::resource('concerts.tickets', TicketTypeController::class)
            ->shallow()
            ->except(['show']);

        // Vouchers
        Route::resource('vouchers', EoVoucherController::class);
        Route::get('/vouchers/{voucher}/stats', [EoVoucherController::class, 'stats'])->name('vouchers.stats');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes (role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users', [UsersController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::patch('/users/{user}', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/toggle', [UsersController::class, 'toggle'])->name('users.toggle');

        // Organizers
        Route::get('/organizers', [OrganizersController::class, 'index'])->name('organizers.index');
        Route::get('/organizers/create', [OrganizersController::class, 'create'])->name('organizers.create');
        Route::post('/organizers', [OrganizersController::class, 'store'])->name('organizers.store');
        Route::get('/organizers/{organizer}/edit', [OrganizersController::class, 'edit'])->name('organizers.edit');
        Route::patch('/organizers/{organizer}', [OrganizersController::class, 'update'])->name('organizers.update');
        Route::delete('/organizers/{organizer}', [OrganizersController::class, 'destroy'])->name('organizers.destroy');
        Route::get('/organizers/{organizer}', [OrganizersController::class, 'show'])->name('organizers.show');

        // Concerts (Approval & Management)
        Route::get('/concerts/pending', [ConcertsController::class, 'pending'])->name('concerts.pending');
        Route::get('/concerts', [ConcertsController::class, 'index'])->name('concerts.index'); // ✅ removed /admin prefix
        Route::get('/concerts/{concert}', [ConcertsController::class, 'show'])->name('concerts.show');
        Route::post('/concerts/{concert}/approve', [ConcertsController::class, 'approve'])->name('concerts.approve');
        Route::post('/concerts/{concert}/reject', [ConcertsController::class, 'reject'])->name('concerts.reject');

        // Orders
        Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/generate-tickets', [OrdersController::class, 'generateTickets'])->name('orders.generate-tickets'); // ✅ key route

        // Payments
        Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index');
        Route::get('/payments/{payment}', [PaymentsController::class, 'show'])->name('payments.show');
        Route::post('/payments/{payment}/confirm', [PaymentsController::class, 'confirm'])->name('payments.confirm');
        Route::post('/payments/{payment}/refund', [PaymentsController::class, 'refund'])->name('payments.refund');

        // Tickets
        Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets.index');
        Route::get('/test-payment/{id}', function ($id) {
            $order = \App\Models\Order::findOrFail($id);
            return view('admin.payments.show', compact('order'));
        });
        // Reports
        Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index');
        Route::post('/reports/export', [ReportsController::class, 'export'])->name('reports.export');
    });

/*
|--------------------------------------------------------------------------
| Standalone Admin Routes (if any)
|--------------------------------------------------------------------------
*/
// Already covered above — no need for separate toggle route outside group
// The toggle route is already inside the admin group as `admin.users.toggle`

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';