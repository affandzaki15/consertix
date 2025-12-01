<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Concert;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\CarbonPeriod;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        // Tentukan rentang default: 30 hari terakhir
        $defaultStart = now()->subDays(30)->startOfDay();
        $defaultEnd   = now()->endOfDay();

        // Ambil dari request atau gunakan default
        $startDate = $request->filled('start_date')
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->start_date)->startOfDay()
            : $defaultStart;

        $endDate = $request->filled('end_date')
            ? \Carbon\Carbon::createFromFormat('Y-m-d', $request->end_date)->endOfDay()
            : $defaultEnd;

        // Pastikan startDate tidak melebihi endDate
        if ($startDate->gt($endDate)) {
            $endDate = $startDate->copy();
        }

        // === Data KPI dengan Filter Tanggal ===

        // Total pengguna baru dalam periode
        $usersCount = User::whereBetween('created_at', [$startDate, $endDate])->count();

        // Organizer baru (pastikan nilai role = 'organizer')
        $organizersCount = User::where('role', 'organizer')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Konser baru dalam periode
        $concertsCount = Concert::whereBetween('created_at', [$startDate, $endDate])->count();

        // Penjualan (orders dengan status completed)
        $totalSales = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->sum('total_amount');

        // === Data Grafik Penjualan Harian ===
        $labels = [];
        $salesData = [];

        $period = CarbonPeriod::create($startDate->copy()->startOfDay(), '1 day', $endDate->copy()->endOfDay());

        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $labels[] = $date->format('d M');

            $dailySales = Order::whereDate('created_at', $dateStr)
                ->where('status', 'completed')
                ->sum('total_amount');

            $salesData[] = (float) $dailySales;
        }

        return view('admin.reports.index', compact(
            'usersCount',
            'organizersCount',
            'concertsCount',
            'totalSales',
            'labels',
            'salesData',
            'startDate',
            'endDate'
        ));
    }

    public function export(Request $request)
    {
        return redirect()->back()->with('info', 'Fitur ekspor akan segera tersedia.');
    }
}