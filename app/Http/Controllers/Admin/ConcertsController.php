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
        $concert->status = 'approved';
        $concert->save();

        return redirect()->route('admin.concerts.pending')->with('success', 'Konser disetujui.');
    }

    public function reject(Request $request, Concert $concert)
    {
        $concert->status = 'rejected';
        if ($request->filled('note')) {
            $concert->admin_note = $request->input('note');
        }
        $concert->save();

        return redirect()->route('admin.concerts.pending')->with('success', 'Konser ditolak.');
    }
}