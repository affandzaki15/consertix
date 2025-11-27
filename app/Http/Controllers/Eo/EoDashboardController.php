<?php

namespace App\Http\Controllers\Eo;

use App\Http\Controllers\Controller;
use App\Models\Concert;

class EoDashboardController extends Controller
{
    public function index()
    {
        $organizer = auth()->user()->organizer;

        // Jika EO belum punya organizer -> arahkan setup dulu
        if (!$organizer) {
            return redirect()->route('eo.concerts.create')
                ->with('warning', 'Silakan buat konser terlebih dahulu!');
        }

        // Ambil semua konser milik EO + tiket nya (lebih efisien)
        $concerts = Concert::with('ticketTypes')
            ->where('organizer_id', $organizer->id)
            ->get();

        // Ambil konser yang sudah disetujui admin saja
        $approvedConcerts = $concerts->where('approval_status', 'approved');

        // Statistik Dashboard
        $totalConcerts = $approvedConcerts->count();

        $totalSold = $approvedConcerts->sum(function ($c) {
            return $c->ticketTypes->sum('sold');
        });

        $totalRevenue = $approvedConcerts->sum(function ($c) {
            return $c->ticketTypes->sum(function ($t) {
                return $t->price * $t->sold;
            });
        });

        // Info status konser untuk EO
        $statusStats = [
            'pending'  => $concerts->where('approval_status', 'pending')->count(),
            'approved' => $approvedConcerts->count(),
            'rejected' => $concerts->where('approval_status', 'rejected')->count(),
        ];

        return view('dashboard.eo', compact(
            'concerts',
            'totalConcerts',
            'totalSold',
            'totalRevenue',
            'statusStats'
        ));
    }
}
