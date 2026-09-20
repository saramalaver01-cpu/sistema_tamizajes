<?php

namespace App\Http\Controllers;

use App\Models\AccesoEstudiante;
use App\Models\Participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AccessController extends Controller
{
    // Ajusta este dominio al correo institucional real de tu universidad
    private const DOMINIO_INSTITUCIONAL = '@uceva.edu.co';

    private const MENSAJES_REGISTRO = [
        'required'    => 'Este campo es obligatorio.',
        'required_if' => 'Este campo es obligatorio.',
        'in'          => 'Selecciona una opción válida.',
        'integer'     => 'Ingresa un número válido.',
        'between'     => 'El valor está fuera del rango permitido.',
        'max'         => 'El texto es demasiado largo.',
        'accepted'    => 'Debes aceptar el consentimiento informado para continuar.',
    ];

    public function show()
    {
        return view('login');
    }

    public function estudiante(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = strtolower(trim($validated['email']));

        if (! str_ends_with($email, self::DOMINIO_INSTITUCIONAL)) {

            return back()
                ->withErrors(['email_estudiante' => 'Debes ingresar con tu correo institucional ('.self::DOMINIO_INSTITUCIONAL.').'])
                ->withInput()
                ->with('activeTab', 'estudiante');

        }

        /*
         * El correo solo se usa para validar el dominio y calcular el hash.
         * Nunca se guarda en la base de datos ni en la sesión: a partir de aquí
         * el participante se identifica únicamente por su hash (HMAC-SHA256).
         */
        $hash = Participante::hashCorreo($email);

        AccesoEstudiante::create([
            'email_hash'  => $hash,
            'accedido_en' => now(),
        ]);

        $request->session()->regenerate();


                $inicial = mb_strtoupper(mb_substr($email, 0, 1));

                // Ya está registrado: entra directo a los módulos
                if (Participante::where('email_hash', $hash)->exists()) {

                    $request->session()->put([
                        'estudiante_hash'    => $hash,
                        'estudiante_inicial' => $inicial,
                    ]);

                    return redirect(route('home').'#modulos');

                }

                // Primera vez: debe aceptar el consentimiento y llenar la caracterización
                $request->session()->put([
                    'registro_pendiente_hash'    => $hash,
                    'registro_pendiente_inicial' => $inicial,
                ]);

                return redirect()->route('registro.estudiante');
    }

    public function mostrarRegistro(Request $request)
    {
        if (! $request->session()->has('registro_pendiente_hash')) {
            return redirect()->route('login');
        }

        return view('registro', [
            'opciones' => config('tamizaje.sociodemografico'),
        ]);
    }

    public function registrar(Request $request)
    {
        $hash = $request->session()->get('registro_pendiente_hash');

        if (! $hash) {
            return redirect()->route('login');
        }

        $cfg = config('tamizaje.sociodemografico');

        $data = $request->validate([
            'consentimiento'       => ['accepted'],

            'rol_institucional'    => ['required', Rule::in($cfg['roles'])],

            // Solo estudiantes
            'facultad'             => ['nullable', 'required_if:rol_institucional,Estudiante', Rule::in(array_keys($cfg['facultades']))],
            'programa_academico'   => ['nullable', 'required_if:rol_institucional,Estudiante', 'string', 'max:150'],
            'semestre'             => ['nullable', 'required_if:rol_institucional,Estudiante', 'integer', 'between:1,12'],
            'jornada'              => ['nullable', 'required_if:rol_institucional,Estudiante', Rule::in($cfg['jornadas'])],

            // Solo funcionario, contratista u otro
            'dependencia'          => [
                'nullable', 'string', 'max:150',
                Rule::requiredIf(fn () => in_array($request->input('rol_institucional'), $cfg['roles_con_dependencia'], true)),
            ],

            // Caracterización sociodemográfica
            'edad'                 => ['required', 'integer', 'between:15,100'],
            'sexo'                 => ['required', Rule::in($cfg['sexo'])],
            'identidad_genero'     => ['required', Rule::in($cfg['identidad_genero'])],
            'orientacion_sexual'   => ['required', Rule::in($cfg['orientacion_sexual'])],
            'estado_civil'         => ['required', Rule::in($cfg['estado_civil'])],
            'nivel_educativo'      => ['required', Rule::in($cfg['nivel_educativo'])],
            'estrato'              => ['required', 'integer', 'between:1,6'],
            'municipio_residencia' => ['required', 'string', 'max:100'],
        ], self::MENSAJES_REGISTRO);

        $esEstudiante = $data['rol_institucional'] === 'Estudiante';

        // El programa debe pertenecer a la facultad elegida
        if ($esEstudiante && ! in_array($data['programa_academico'], $cfg['facultades'][$data['facultad']] ?? [], true)) {
            throw ValidationException::withMessages([
                'programa_academico' => 'El programa no corresponde a la facultad seleccionada.',
            ]);
        }

        // Limpia lo que no aplica según el rol
        if (! $esEstudiante) {
            $data['facultad'] = null;
            $data['programa_academico'] = null;
            $data['semestre'] = null;
            $data['jornada'] = null;
        }

        if (! in_array($data['rol_institucional'], $cfg['roles_con_dependencia'], true)) {
            $data['dependencia'] = null;
        }

        // Queda constancia de que aceptó el consentimiento informado
        unset($data['consentimiento']);
        $data['consentimiento_at'] = now();

        // firstOrCreate evita errores si el formulario se envía dos veces
        Participante::firstOrCreate(['email_hash' => $hash], $data);

        $request->session()->forget('registro_pendiente_hash');
        $request->session()->put('estudiante_hash', $hash);

        $inicial = $request->session()->get('registro_pendiente_inicial', '?');

        $request->session()->forget(['registro_pendiente_hash', 'registro_pendiente_inicial']);
        $request->session()->put([
        'estudiante_hash'    => $hash,
        'estudiante_inicial' => $inicial,
        ]);

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

        $request->session()->forget(['estudiante_hash', 'registro_pendiente_hash']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
