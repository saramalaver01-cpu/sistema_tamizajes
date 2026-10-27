<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Modulo;
use App\Models\NivelResultado;
use Illuminate\Http\Request;

class NivelResultadoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Listar niveles
    |--------------------------------------------------------------------------
    */

    public function index(Modulo $modulo)
    {
        $modulo->load('niveles');

        return view(
            'admin.modulos.niveles',
            compact('modulo')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Crear nivel
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Modulo $modulo
    ) {

        $data = $this->validarNivel($request);


        $ultimoOrden = $modulo
            ->niveles()
            ->max('orden');


        $orden = $ultimoOrden
            ? $ultimoOrden + 1
            : 1;


        $modulo
            ->niveles()
            ->create(
                array_merge(
                    $data,
                    [
                        'orden' => $orden,
                    ]
                )
            );


        return back()
            ->with(
                'success',
                'Nivel agregado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Actualizar nivel
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        NivelResultado $nivel
    ) {

        $data = $this->validarNivel($request);

        $nivel->update($data);


        return back()
            ->with(
                'success',
                'Nivel actualizado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Eliminar nivel
    |--------------------------------------------------------------------------
    */

    public function destroy(
        NivelResultado $nivel
    ) {

        $nivel->delete();


        return back()
            ->with(
                'success',
                'Nivel eliminado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Validación
    |--------------------------------------------------------------------------
    */

    private function validarNivel(
        Request $request
    ): array {

        $data = $request->validate([

            'clave' => [
                'required',
                'string',
                'max:40',
                'alpha_dash',
            ],

            'etiqueta' => [
                'required',
                'string',
                'max:100',
            ],

            'color' => [
                'required',
                'in:verde,amarillo,naranja,rojo',
            ],

            'min_porcentaje' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'max_porcentaje' => [
                'required',
                'integer',
                'min:0',
                'max:100',
                'gte:min_porcentaje',
            ],

            'resultado' => [
                'required',
                'string',
            ],

            'reflexiona' => [
                'required',
                'string',
            ],

            'recomendaciones' => [
                'required',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | Convertir recomendaciones a array
        |--------------------------------------------------------------------------
        */

        $data['recomendaciones'] = collect(
            preg_split(
                '/\r\n|\r|\n/',
                $data['recomendaciones']
            )
        )
            ->map(
                fn ($linea) => trim($linea)
            )
            ->filter()
            ->values()
            ->all();


        return $data;
    }
}
