<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $role = $request->query('role');

        $users = User::query()
            ->when($q, fn($b) => $b->where(fn($qb) =>
                $qb->where('name', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%")
            ))
            ->when($role, fn($b) => $b->where('role', $role))
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q', 'role'));
    }

    public function edit(User $user)
    {
        $roles = ['user','eo','admin'];
        return view('admin.users.edit', compact('user','roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => 'required|string|in:user,eo,admin',
            'active' => 'nullable|boolean',
        ]);

        $user->role = $data['role'];
        if ($request->has('active')) {
            $user->active = (bool) $request->boolean('active');
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User diperbarui.');
    }

    public function destroy(User $user)
    {
        // soft delete jika model gunakan SoftDeletes, jika tidak hapus langsung
        $user->delete();
        return back()->with('success', 'User dihapus.');
    }
}