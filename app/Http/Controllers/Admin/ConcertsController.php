<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use Illuminate\Http\Request;

class ConcertsController extends Controller
{
    public function pending()
    {
        $pending = Concert::where('status', 'pending')->paginate(20);
        return view('admin.concerts.pending', compact('pending'));
    }

    public function show(Concert $concert)
    {
        return view('admin.concerts.show', compact('concert'));
    }

    public function approve(Request $request, Concert $concert)
    {
        $concert->update([
            'approval_status' => 'approved'
        ]);

        // otomatis update selling status
        $concert->updateSellingStatus();

        return redirect()->route('admin.concerts.pending')->with('success', 'Konser disetujui.');
    }

    public function reject(Request $request, Concert $concert)
    {
        $concert->update([
            'approval_status' => 'rejected',
            'selling_status' => 'coming_soon'
        ]);

        if ($request->filled('note')) {
            // simpan catatan jika ada kolom notes/admin_note
            if (Schema::hasColumn('concerts', 'notes')) {
                $concert->notes = $request->input('note');
                $concert->save();
            }
        }

        return redirect()->route('admin.concerts.pending')->with('error', 'Konser ditolak.');
    }
}