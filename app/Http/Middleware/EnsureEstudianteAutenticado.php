<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureEstudianteAutenticado
{
    /**
     * Bloquea el acceso a los módulos de tamizaje si el visitante no ha
     * validado su correo institucional (no tiene 'estudiante_hash' en sesión).
     *
     * Si lo manda a login, Laravel recuerda a dónde iba (redirect()->guest())
     * para que, tras iniciar sesión, vuelva directo al módulo que quería.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->session()->has('estudiante_hash')) {
            return redirect()->guest(route('login'));
        }

        return $next($request);
    }
}
