<?php

namespace App\Http\Controllers\Admin;

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
    // Ambil konser pending
    if (Schema::hasColumn('concerts', 'approval_status')) {
        $pending = Concert::where('approval_status', 'pending')->paginate(10, ['*'], 'pending_page');
        // Ambil approved & rejected (bukan pending)
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

        if (class_exists(ConcertAdminAction::class)) {
            ConcertAdminAction::create([
                'concert_id' => $concert->id,
                'admin_id'   => Auth::id(),
                'action'     => 'rejected',
                'note'       => $request->input('note'),
            ]);
        }

        return redirect()->route('admin.concerts.pending')->with('success', 'Konser ditolak.');
    }
        public function show(Concert $concert)
    {
        return view('admin.concerts.show', compact('concert'));
    }

}
