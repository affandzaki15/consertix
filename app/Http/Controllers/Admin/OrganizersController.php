<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Organizer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrganizersController extends Controller
{
    // Tampilkan semua organizer
    public function index()
    {
        $organizers = User::where('role', 'eo')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.organizers.index', compact('organizers'));
    }

    // Form tambah organizer
    public function create()
    {
        return view('admin.organizers.create');
    }

    // Simpan organizer baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'eo',
            'status'   => 'pending', // default pending
        ]);

        return redirect()->route('admin.organizers.index')
            ->with('success', 'Organizer berhasil ditambahkan.');
    }

    // Tampilkan pending organizer
    public function pending()
    {
        $pending = User::where('role', 'eo')
            ->where('status', 'pending') // pastikan kolom `status` ada di migration
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('admin.organizers.pending', compact('pending'));
    }

    // Detail organizer
    public function show(User $organizer)
    {
        return view('admin.organizers.show', compact('organizer'));
    }

    // Form edit organizer
    public function edit(User $organizer)
    {
        return view('admin.organizers.edit', compact('organizer'));
    }

    // Update organizer
    public function update(Request $request, User $organizer)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $organizer->id,
        ]);

        $organizer->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        // Jika admin mengubah password
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $organizer->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.organizers.index')
            ->with('success', 'Organizer berhasil diperbarui.');
    }

    // Approve organizer
    public function approve(User $organizer)
    {
        $organizer->update(['status' => 'approved', 'note' => null]);

        return redirect()->route('admin.organizers.pending')
            ->with('success', 'Organizer berhasil di-approve.');
    }

    // Reject organizer
    public function reject(Request $request, User $organizer)
    {
        $organizer->update([
            'status' => 'rejected',
            'note'   => $request->note,
        ]);

        return redirect()->route('admin.organizers.pending')
            ->with('success', 'Organizer ditolak.');
    }

    // Hapus organizer
    public function destroy(User $organizer)
    {
        $organizer->delete();

        return redirect()->route('admin.organizers.index')
            ->with('success', 'Organizer berhasil dihapus.');
    }

    /**
     * Generate a new random password for the organizer's user and email it.
     */
    public function sendPassword(Organizer $organizer)
    {
        $user = $organizer->user;

        if (!$user) {
            return back()->with('error', 'User untuk organizer ini tidak ditemukan.');
        }

        $plain = Str::random(10);
        $user->password = Hash::make($plain);
        $user->save();

        try {
            Mail::raw("Halo {$user->name},\n\nPassword akun Anda: {$plain}\n\nSilakan login dan ganti password setelah berhasil masuk.", function ($m) use ($user) {
                $m->to($user->email)->subject('Akun Organizer - Password Baru');
            });

            return back()->with('success', 'Password baru telah dibuat dan dikirim ke ' . $user->email);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }
}
