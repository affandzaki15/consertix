<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Concert;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic stats
        $usersCount = User::count();
        $eoCount = User::where('role', 'eo')->count();
        $concertsByStatus = Concert::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status');
        $salesTotal = Order::sum('total_amount');
        $recentOrders = Order::latest()->limit(10)->get();
        $pendingPayments = Order::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'usersCount',
            'eoCount',
            'concertsByStatus',
            'salesTotal',
            'recentOrders',
            'pendingPayments'
        ));
    }
}