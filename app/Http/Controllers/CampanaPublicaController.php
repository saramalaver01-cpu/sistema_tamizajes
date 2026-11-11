<?php

namespace App\Http\Controllers;

use App\Models\Campana;

class CampanaPublicaController extends Controller
{
    public function entrar(string $codigo)
    {
        // Buscar la campaña por su código único.
        $campana = Campana::with('modulos')
            ->where('codigo', $codigo)
            ->firstOrFail();

        // Comprobar que esté activa y dentro de las fechas.
        if ($campana->estado !== 'en_curso') {
            return view('campanas.no-disponible', compact('campana'));
        }

        // Obtener únicamente los módulos activos
        // que pertenecen a esta campaña.
        $modulos = $campana->modulos
            ->where('activo', true)
            ->values();

        // Registrar el acceso al enlace.
        $campana->increment('ingresos');

        // Conservar la campaña durante la navegación.
        session([
            'campana_id' => $campana->id,
            'campana_codigo' => $campana->codigo,
        ]);

        // Mostrar página exclusiva de la campaña.
        return view('campanas.entrada', compact(
            'campana',
            'modulos'
        ));
    }
}
