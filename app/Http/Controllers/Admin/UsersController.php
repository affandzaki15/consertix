<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $users = User::when($q, fn($b) => $b->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"))
            ->paginate(20);

        return view('admin.users.index', compact('users', 'q'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate(['role' => 'required|string', 'active' => 'nullable|boolean']);

        $user->role = $request->role;
        if ($request->has('active')) {
            $user->active = (bool) $request->active;
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User removed.');
    }
}