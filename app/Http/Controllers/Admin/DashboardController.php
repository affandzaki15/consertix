<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Concert;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Hitung total user
        $usersCount = User::count();

        // Hitung EO (asumsi role 'eo')
        $eoCount = User::where('role', 'eo')->count();

        // Concerts grouped by status
        $concertsByStatus = Concert::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Total penjualan (cek nama kolom yang tersedia)
        if (Schema::hasColumn('orders', 'total_amount')) {
            $salesTotal = Order::sum('total_amount');
        } elseif (Schema::hasColumn('orders', 'total')) {
            $salesTotal = Order::sum('total');
        } else {
            $salesTotal = 0;
        }

        // Recent orders (paginate agar ->links() tersedia)
        $recentOrders = Order::latest()->paginate(10);

        // Ambil collection internal dari paginator untuk operasi pluck
        $orderCollection = $recentOrders->getCollection();

        // Load users terkait secara aman untuk menampilkan nama pembeli (jika ada kolom user_id)
        $usersMap = [];
        if ($orderCollection->isNotEmpty() && Schema::hasColumn('orders', 'user_id')) {
            $userIds = $orderCollection->pluck('user_id')->filter()->unique()->values();
            if ($userIds->isNotEmpty()) {
                $usersMap = User::whereIn('id', $userIds)->get()->keyBy('id');
            }
        }

        // Pending payments: cek kolom yang ada dan gunakan fallback yang aman
        $pendingPayments = 0;
        if (Schema::hasColumn('orders', 'payment_status')) {
            $pendingPayments = Order::whereIn('payment_status', ['pending', 'unverified'])->count();
        } elseif (Schema::hasColumn('orders', 'payment_proof') && Schema::hasColumn('orders', 'status')) {
            $pendingPayments = Order::whereNotNull('payment_proof')->where('status', 'pending')->count();
        } elseif (Schema::hasColumn('orders', 'status')) {
            $pendingPayments = Order::where('status', 'pending')->count();
        }

        return view('admin.dashboard', compact(
            'usersCount',
            'eoCount',
            'concertsByStatus',
            'salesTotal',
            'recentOrders',
            'pendingPayments',
            'usersMap'
        ));
    }
}