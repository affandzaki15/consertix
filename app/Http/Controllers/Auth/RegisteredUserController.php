<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone'    => ['required', 'string', 'max:20'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',  // Default role
            'phone'    => $request->phone,
        ]);
        // Jika user mendaftar sebagai EO → buatkan organizer otomatis
        if ($user->role === 'eo') {
            \App\Models\Organizer::create([
                'user_id' => $user->id,
                'organization_name' => $user->name // atau nama EO custom nanti
            ]);
        }

        event(new Registered($user));

        // Don't auto-login, redirect to login page instead
        return redirect()->route('login')
            ->with('success', 'Registration successful! Please log in.');
    }
}
