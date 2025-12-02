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

    // Restore saved cart (if any) from user record into session
    try {
        if (! empty($user->cart) && is_array($user->cart)) {
            $current = session()->get('cart', []);
            // Merge persisted cart with current session cart, favoring larger quantities
            foreach ($user->cart as $concertId => $tickets) {
                if (! isset($current[$concertId])) {
                    $current[$concertId] = $tickets;
                    continue;
                }
                foreach ($tickets as $typeId => $qty) {
                    $existing = $current[$concertId][$typeId] ?? 0;
                    $current[$concertId][$typeId] = max($existing, $qty);
                }
            }
            session()->put('cart', $current);

            // clear stored cart after restoring
            $user->cart = null;
            $user->save();
        }
    } catch (\Throwable $e) {
        // don't break login for cart restore issues
        \Illuminate\Support\Facades\Log::error('Cart restore failed on login: '.$e->getMessage());
    }

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
        // Persist current session cart to user record so it can be restored on next login
        try {
            $user = Auth::user();
            if ($user) {
                $cart = session()->get('cart', []);
                if (! empty($cart)) {
                    $user->cart = $cart;
                    $user->save();
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Failed to save cart on logout: '.$e->getMessage());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
