<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check() || ! Auth::user()->is_admin) {
            return redirect()->route('login')->withErrors([
                'email_admin' => 'Debes iniciar sesión como administrador para acceder a esta sección.',
            ]);
        }

        return $next($request);
    }
}