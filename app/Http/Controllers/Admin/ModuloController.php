<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listado
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $modulos = Modulo::orderBy('orden')->get();

        return view(
            'admin.modulos.index',
            compact('modulos')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Formulario de creación
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.modulos.create');
    }


    /*
    |--------------------------------------------------------------------------
    | Crear módulo
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $data = $request->validate([

            'clave' => [
                'required',
                'string',
                'max:40',
                'unique:modulos,clave',
                'alpha_dash',
            ],

            'nombre' => [
                'required',
                'string',
                'max:100',
            ],

            'descripcion' => [
                'required',
                'string',
            ],

            'icono' => [
                'required',
                'string',
                'max:50',
            ],

            'color' => [
                'required',
                'in:green,purple,blue',
            ],

            'tiempo_estimado' => [
                'required',
                'integer',
                'min:1',
                'max:60',
            ],

            'orden' => [
                'nullable',
                'integer',
                'min:0',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Valores automáticos
        |--------------------------------------------------------------------------
        */

        $data['activo'] = true;

        $data['total_preguntas'] = 0;

        $data['motor_generico'] = true;


        /*
        |--------------------------------------------------------------------------
        | Crear módulo
        |--------------------------------------------------------------------------
        */

        $modulo = Modulo::create($data);


        /*
        |--------------------------------------------------------------------------
        | Crear niveles iniciales
        |--------------------------------------------------------------------------
        */

        $modulo->crearNivelesPorDefecto();


        return redirect()
            ->route('admin.modulos.index')
            ->with(
                'success',
                'Módulo creado correctamente. Ahora agrega sus preguntas y configura los niveles de resultado.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Editar
    |--------------------------------------------------------------------------
    */

    public function edit(Modulo $modulo)
    {
        return view(
            'admin.modulos.edit',
            compact('modulo')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Actualizar
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Modulo $modulo
    ) {

        $data = $request->validate([

            'nombre' => [
                'required',
                'string',
                'max:100',
            ],

            'descripcion' => [
                'required',
                'string',
            ],

            'icono' => [
                'required',
                'string',
                'max:50',
            ],

            'color' => [
                'required',
                'in:green,purple,blue',
            ],

            'tiempo_estimado' => [
                'required',
                'integer',
                'min:1',
                'max:60',
            ],

            'orden' => [
                'nullable',
                'integer',
                'min:0',
            ],

        ]);

        $modulo->update($data);

        return redirect()
            ->route('admin.modulos.index')
            ->with(
                'success',
                'Módulo actualizado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Eliminar
    |--------------------------------------------------------------------------
    */

    public function destroy(Modulo $modulo)
    {
        $modulo->delete();

        return redirect()
            ->route('admin.modulos.index')
            ->with(
                'success',
                'Módulo eliminado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Activar / desactivar
    |--------------------------------------------------------------------------
    */

    public function toggle(Modulo $modulo)
    {
        $modulo->update([
            'activo' => !$modulo->activo,
        ]);

        return back()->with(
            'success',
            $modulo->activo
                ? "Módulo \"{$modulo->nombre}\" activado."
                : "Módulo \"{$modulo->nombre}\" desactivado."
        );
    }
}
