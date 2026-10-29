<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdministradoresController extends Controller
{
    public function index()
    {
        $administradores = User::where('is_admin', true)
            ->orderBy('name')
            ->get();

        return view('admin.administradores', compact('administradores'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:150'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'email.unique' => 'Ya existe una cuenta registrada con ese correo.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'is_admin' => true,
        ]);

        return redirect()
            ->route('admin.administradores.index')
            ->with('success', 'Administrador creado correctamente.');
    }
}
