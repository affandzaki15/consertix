<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class OrganizersController extends Controller
{
    public function pending()
    {
        $query = User::query();

        // Filter dasar: role = 'eo' jika kolom role ada
        if (Schema::hasColumn('users', 'role')) {
            $query->where('role', 'eo');
        }

        // Jika ada kolom status, pilih hanya yang pending
        if (Schema::hasColumn('users', 'status')) {
            $query->where('status', 'pending');
        }

        $pending = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.organizers.pending', compact('pending'));
    }

    public function show(User $user)
    {
        return view('admin.organizers.show', compact('user'));
    }

    public function approve(Request $request, User $user)
    {
        if (Schema::hasColumn('users', 'status')) {
            $user->status = 'approved';
        }
        if (Schema::hasColumn('users', 'role')) {
            $user->role = 'eo';
        }
        if (Schema::hasColumn('users', 'approved_at')) {
            $user->approved_at = now();
        }
        $user->save();

        return redirect()->route('admin.organizers.pending')->with('success', 'Organizer disetujui.');
    }

    public function reject(Request $request, User $user)
    {
        $note = $request->input('note');

        if (Schema::hasColumn('users', 'status')) {
            $user->status = 'rejected';
        }

        if ($note) {
            if (Schema::hasColumn('users', 'notes')) {
                $user->notes = $note;
            } elseif (Schema::hasColumn('users', 'admin_note')) {
                $user->admin_note = $note;
            }
        }

        $user->save();

        return redirect()->route('admin.organizers.pending')->with('success', 'Organizer ditolak.');
    }
}