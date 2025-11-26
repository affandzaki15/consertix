<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        // Validasi multi role sekaligus
        if (!in_array(Auth::user()->role, $roles)) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        return $next($request);
    }
}
