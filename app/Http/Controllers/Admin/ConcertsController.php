<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Concert;
use Illuminate\Support\Facades\Schema;

class ConcertsController extends Controller
{
    public function pending()
    {
        // gunakan approval_status jika ada, fallback ke status bila ada
        if (Schema::hasColumn('concerts', 'approval_status')) {
            $pending = Concert::where('approval_status', 'pending')->paginate(20);
        } elseif (Schema::hasColumn('concerts', 'status')) {
            $pending = Concert::where('status', 'pending')->paginate(20);
        } else {
            // tidak ada kolom status -> kembalikan kosong agar tidak error
            $pending = Concert::whereRaw('0 = 1')->paginate(20);
        }

        return view('admin.concerts.pending', compact('pending'));
    }

    public function show(Concert $concert)
    {
        return view('admin.concerts.show', compact('concert'));
    }

    public function approve(Request $request, Concert $concert)
    {
        if (Schema::hasColumn('concerts', 'approval_status')) {
            $concert->update(['approval_status' => 'approved']);
        } elseif (Schema::hasColumn('concerts', 'status')) {
            $concert->update(['status' => 'approved']);
        } else {
            return redirect()->back()->with('error', 'Kolom status konser tidak ditemukan.');
        }

        // jika model punya helper untuk update selling status, panggil jika ada
        if (method_exists($concert, 'updateSellingStatus')) {
            $concert->updateSellingStatus();
        }

        return redirect()->route('admin.concerts.pending')->with('success', 'Konser disetujui.');
    }

    public function reject(Request $request, Concert $concert)
    {
        if (Schema::hasColumn('concerts', 'approval_status')) {
            $concert->update(['approval_status' => 'rejected']);
        } elseif (Schema::hasColumn('concerts', 'status')) {
            $concert->update(['status' => 'rejected']);
        } else {
            return redirect()->back()->with('error', 'Kolom status konser tidak ditemukan.');
        }

        if ($request->filled('note') && Schema::hasColumn('concerts', 'notes')) {
            $concert->notes = $request->input('note');
            $concert->save();
        }

        return redirect()->route('admin.concerts.pending')->with('success', 'Konser ditolak.');
    }
}