<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Concert;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Total pengguna
        $usersCount = User::count();

        // Total organizer (EO)
        $eoCount = User::where('role', 'eo')->count();

        // ✅ Total penjualan (hanya order yang sudah dibayar)
        $salesTotal = Order::where('status', 'paid')->sum('total_amount');

        // Status persetujuan konser
        $approvalStats = Concert::selectRaw('approval_status, COUNT(*) as total')
            ->groupBy('approval_status')
            ->pluck('total', 'approval_status')
            ->toArray();

        // Status penjualan (hanya konser yang disetujui)
        $sellingStats = Concert::selectRaw('selling_status, COUNT(*) as total')
            ->where('approval_status', 'approved')
            ->groupBy('selling_status')
            ->pluck('total', 'selling_status')
            ->toArray();

        // Order terbaru
        $recentOrders = Order::latest()->paginate(10);

        // 🔹 CHART 1: Konser Disetujui vs Ditolak per Bulan (6 bulan terakhir)
        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();

        $concertChartData = DB::table('concerts')
            ->select(
                DB::raw("DATE_FORMAT(MIN(created_at), '%b %Y') as month"),
                DB::raw("SUM(CASE WHEN approval_status = 'approved' THEN 1 ELSE 0 END) as approved"),
                DB::raw("SUM(CASE WHEN approval_status = 'rejected' THEN 1 ELSE 0 END) as rejected")
            )
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
            ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
            ->get();

        $months = $concertChartData->pluck('month')->values();
        $approvedData = $concertChartData->pluck('approved')->values();
        $rejectedData = $concertChartData->pluck('rejected')->values();

        // 🔹 CHART 2: Penjualan Bulanan (hanya order yang sudah dibayar)
        $salesChartData = DB::table('orders')
            ->select(
                DB::raw("DATE_FORMAT(MIN(created_at), '%b %Y') as month"),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('status', 'paid')
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'), 'asc')
            ->orderBy(DB::raw('MONTH(created_at)'), 'asc')
            ->get();

        $salesMonths = $salesChartData->pluck('month')->values();
        $salesData = $salesChartData->pluck('total')->map(function ($amount) {
            return (float) $amount;
        })->values();

        // Kirim semua data ke view (termasuk $salesTotal!)
        return view('admin.dashboard', compact(
            'usersCount',
            'eoCount',
            'salesTotal',        // ✅ Ini yang baru ditambahkan
            'approvalStats',
            'sellingStats',
            'recentOrders',
            'months',
            'approvedData',
            'rejectedData',
            'salesMonths',
            'salesData'
        ));
    }

    // ACTION — Approve event
    public function approve($id)
    {
        $concert = Concert::findOrFail($id);
        $concert->update([
            'approval_status' => 'approved'
        ]);

        // otomatis update selling status
        $concert->updateSellingStatus();

        return back()->with('success', "Event '{$concert->title}' berhasil di-approve!");
    }

    // ACTION — Reject event
    public function reject($id)
    {
        $concert = Concert::findOrFail($id);
        $concert->update([
            'approval_status' => 'rejected',
            'selling_status' => 'coming_soon'
        ]);

        return back()->with('error', "Event '{$concert->title}' telah ditolak!");
    }
}