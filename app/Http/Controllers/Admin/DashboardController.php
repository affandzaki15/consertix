<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Concert;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $usersCount = User::count();
        $eoCount = User::where('role', 'eo')->count();

        // Approval status charts
        $approvalStats = Concert::selectRaw('approval_status, COUNT(*) as total')
            ->groupBy('approval_status')
            ->pluck('total', 'approval_status')
            ->toArray();

        // Selling status charts
        $sellingStats = Concert::selectRaw('selling_status, COUNT(*) as total')
            ->where('approval_status', 'approved')
            ->groupBy('selling_status')
            ->pluck('total', 'selling_status')
            ->toArray();

        $recentOrders = Order::latest()->paginate(10);

        return view('admin.dashboard', compact(
            'usersCount',
            'eoCount',
            'approvalStats',
            'sellingStats',
            'recentOrders'
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
