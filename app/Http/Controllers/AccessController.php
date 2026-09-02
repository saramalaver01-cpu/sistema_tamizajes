<?php

namespace App\Http\Controllers;
use App\Models\AccesoEstudiante;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{
    // Ajusta este dominio al correo institucional real de tu universidad
    private const DOMINIO_INSTITUCIONAL = '@uceva.edu.co';

   public function show()
{
    return view('login');
}

    public function estudiante(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower($validated['email']);

        if (! str_ends_with($email, self::DOMINIO_INSTITUCIONAL)) {

            return back()
                ->withErrors(['email_estudiante' => 'Debes ingresar con tu correo institucional ('.self::DOMINIO_INSTITUCIONAL.').'])
                ->withInput()
                ->with('activeTab', 'estudiante');

        }

           AccesoEstudiante::create([
             'email_hash'  => hash('sha256', $email),
            'accedido_en' => now(),
            ]);

        /*
         * Según el documento del proyecto, el acceso de estudiantes
         * es únicamente una validación institucional: no se usa
         * contraseña ni se identifica a la persona directamente.
         * Guardamos el correo solo en sesión, para personalizar la
         * visita, no para autenticación real.
         */

        $request->session()->put('estudiante_email', $email);

        return redirect(route('home').'#modulos');
    }

    public function administrador(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            $request->session()->regenerate();

            if (! Auth::user()->is_admin) {

                Auth::logout();

                return back()
                    ->withErrors(['email_admin' => 'Esta cuenta no tiene permisos de administrador o personal.'])
                    ->withInput()
                    ->with('activeTab', 'administrador');

            }

            // TODO: cambiar a route('admin.dashboard') cuando esa vista exista
            return redirect()->intended(route('admin.dashboard'))->with('success', 'Bienvenido(a) al panel administrativo.');

        }

        return back()
            ->withErrors(['email_admin' => 'Las credenciales no coinciden con nuestros registros.'])
            ->withInput()
            ->with('activeTab', 'administrador');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->forget('estudiante_email');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}