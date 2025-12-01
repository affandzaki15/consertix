<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Concert;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\ConcertAdminAction;

class ConcertsController extends Controller
{
    public function pending()
    {
        if (Schema::hasColumn('concerts', 'approval_status')) {
            $pending = Concert::where('approval_status', 'pending')->paginate(10, ['*'], 'pending_page');
            $history = Concert::whereNotIn('approval_status', ['pending'])
                             ->orderBy('updated_at', 'desc')
                             ->paginate(10, ['*'], 'history_page');
        } elseif (Schema::hasColumn('concerts', 'status')) {
            $pending = Concert::where('status', 'pending')->paginate(10, ['*'], 'pending_page');
            $history = Concert::whereNotIn('status', ['pending'])
                             ->orderBy('updated_at', 'desc')
                             ->paginate(10, ['*'], 'history_page');
        } else {
            $pending = Concert::whereRaw('1 = 0')->paginate(10, ['*'], 'pending_page');
            $history = Concert::whereRaw('1 = 0')->paginate(10, ['*'], 'history_page');
        }

        return view('admin.concerts.pending', compact('pending', 'history'));
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'date' => 'required|date',
        'time' => 'required',
        'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'description' => 'nullable|string',
    ]);

    $concert = new Concert();
    $concert->title = $request->title;
    $concert->location = $request->location;
    $concert->date = $request->date;
    $concert->time = $request->time;
    $concert->description = $request->description;

    // ✅ SIMPAN GAMBAR KE FOLDER 'concerts' DI DISK 'public'
        if ($request->hasFile('image_url')) {
        // ✅ Simpan langsung ke public/foto/concerts
        $filename = time() . '_' . $request->file('image_url')->getClientOriginalName();
        $request->file('image_url')->move(public_path('foto/concerts'), $filename);
        $concert->image_url = 'foto/concerts/' . $filename;
    }

    $concert->save();

    return redirect()->route('eo.concerts.tickets', $concert->id)
                     ->with('success', 'Konser berhasil dibuat! Silakan tambahkan tipe tiket.');
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

        if (method_exists($concert, 'updateSellingStatus')) {
            $concert->updateSellingStatus();
        }

        if (class_exists(ConcertAdminAction::class)) {
            ConcertAdminAction::create([
                'concert_id' => $concert->id,
                'admin_id'   => Auth::id(),
                'action'     => 'approved',
                'note'       => $request->input('note'),
            ]);
        }

        return redirect()->route('admin.concerts.pending')->with('success', 'Konser disetujui.');
    }

    public function index()
    {
        if (Schema::hasColumn('concerts', 'approval_status')) {
            $concerts = Concert::orderBy('created_at', 'desc')->paginate(20);
        } elseif (Schema::hasColumn('concerts', 'status')) {
            $concerts = Concert::orderBy('created_at', 'desc')->paginate(20);
        } else {
            $concerts = Concert::paginate(20);
        }

        return view('admin.concerts.index', compact('concerts'));
    }

   public function reject(Request $request, Concert $concert)
{
    $request->validate([
        'note' => 'nullable|string|max:500',
    ]);

    // Simpan status dan catatan ke DB langsung
    $concert->update([
        'approval_status' => 'rejected',
        'notes' => $request->note,
    ]);

    // Simpan log riwayat admin
    if (class_exists(ConcertAdminAction::class)) {
        ConcertAdminAction::create([
            'concert_id' => $concert->id,
            'admin_id'   => Auth::id(),
            'action'     => 'rejected',
            'note'       => $request->note,
        ]);
    }

    return redirect()
        ->route('admin.concerts.pending')
        ->with('success', 'Konser berhasil ditolak dengan catatan!');
}


    public function show(Concert $concert)
    {
        // Pastikan relasi adminActions dimuat
        $concert->load('adminActions.admin');
        return view('admin.concerts.show', compact('concert'));
    }
}