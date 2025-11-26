<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class OrganizersController extends Controller
{
    public function pending()
    {
        $pending = User::where('role', 'eo')->where('status', 'pending')->paginate(20);
        return view('admin.organizers.pending', compact('pending'));
    }

    public function show(User $user)
    {
        return view('admin.organizers.show', compact('user'));
    }

    public function approve(Request $request, User $user)
    {
        $user->status = 'approved';
        $user->role = 'eo';
        $user->save();

        return redirect()->route('admin.organizers.pending')->with('success', 'Organizer disetujui.');
    }

    public function reject(Request $request, User $user)
    {
        $user->status = 'rejected';
        // simpan note jika ada
        if ($request->filled('note')) {
            $user->notes = $request->input('note');
        }
        $user->save();

        return redirect()->route('admin.organizers.pending')->with('success', 'Organizer ditolak.');
    }
}