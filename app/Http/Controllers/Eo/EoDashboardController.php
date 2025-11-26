<?php

namespace App\Http\Controllers\Eo;

use Illuminate\Http\Request;
use App\Models\Concert;
use App\Http\Controllers\Controller;

class EoDashboardController extends Controller
{
    public function index()
    {
        // Ambil seluruh konser milik EO yang login
        $concerts = Concert::where('organizer_id', auth()->id())->get();

        // Hitung Statistik
        $totalConcerts = $concerts->count();
        $totalSold = $concerts->sum(function ($concert) {
            return $concert->ticketTypes->sum('sold');
        });

        $totalRevenue = $concerts->sum(function ($concert) {
            return $concert->ticketTypes->sum(function ($ticket) {
                return $ticket->price * $ticket->sold;
            });
        });

        $statusStats = [
            'draft' => $concerts->where('status', 'draft')->count(),
            'pending' => $concerts->where('status', 'pending')->count(),
            'approved' => $concerts->where('status', 'approved')->count(),
            'rejected' => $concerts->where('status', 'rejected')->count(),
        ];

        return view('dashboard.eo', compact(
            'totalConcerts',
            'totalSold',
            'totalRevenue',
            'statusStats',
            'concerts'
        ));
    }
}
