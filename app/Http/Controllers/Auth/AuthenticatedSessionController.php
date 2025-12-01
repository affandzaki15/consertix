<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
   public function store(Request $request): RedirectResponse
{
    $request->validate([
        'email'    => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    // Coba login berdasarkan email & password
    if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        throw ValidationException::withMessages([
            'email' => __('Email atau password salah.'),
        ]);
    }

    // ✅ Ambil user yang baru login
    $user = Auth::user();

    // ✅ Cek apakah akun aktif
    if (! $user->active) {
        Auth::logout(); // logout karena akun dinonaktifkan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        throw ValidationException::withMessages([
            'email' => __('Akun Anda tidak aktif. Silakan hubungi administrator.'),
        ]);
    }

    $request->session()->regenerate();

    // Redirect by role after login: admin/eo -> their dashboards, others -> welcome
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'eo'    => redirect()->route('eo.dashboard'),
        default => redirect('/'),
    };
}
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
