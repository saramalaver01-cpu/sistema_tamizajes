<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';

    protected $fillable = [
        'clave',
        'nombre',
        'descripcion',
        'icono',
        'color',
        'tiempo_estimado',
        'total_preguntas',
        'activo',
        'orden',
        'motor_generico',
        'opciones_escala',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'motor_generico' => 'boolean',
        'opciones_escala' => 'array',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function niveles()
    {
        return $this->hasMany(NivelResultado::class)
            ->orderBy('orden');
    }


    public function dimensiones()
    {
        return $this->hasMany(Dimension::class)
            ->orderBy('orden');
    }


    /*
    |--------------------------------------------------------------------------
    | Scope
    |--------------------------------------------------------------------------
    */

    public function scopeActivos($query)
    {
        return $query
            ->where('activo', true)
            ->orderBy('orden');
    }


    /*
    |--------------------------------------------------------------------------
    | Gradiente visual
    |--------------------------------------------------------------------------
    */

    public function getGradienteAttribute(): array
    {
        return match ($this->color) {

            'green' => [
                'from' => '#16a34a',
                'to'   => '#059669',
            ],

            'purple' => [
                'from' => '#7c3aed',
                'to'   => '#5420a8',
            ],

            'blue' => [
                'from' => '#1e3a5f',
                'to'   => '#0f2942',
            ],

            default => [
                'from' => '#64748b',
                'to'   => '#475569',
            ],
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Crear niveles por defecto
    |--------------------------------------------------------------------------
    */


    public function crearNivelesPorDefecto(): void
    {
        if ($this->niveles()->exists()) {
            return;
        }

        $defaults = [
            ['clave' => 'bajo',     'etiqueta' => 'Nivel bajo',     'color' => 'verde',    'min_porcentaje' => 0,  'max_porcentaje' => 25,  'orden' => 1],
            ['clave' => 'moderado', 'etiqueta' => 'Nivel moderado', 'color' => 'amarillo', 'min_porcentaje' => 26, 'max_porcentaje' => 50,  'orden' => 2],
            ['clave' => 'alto',     'etiqueta' => 'Nivel alto',     'color' => 'naranja',  'min_porcentaje' => 51, 'max_porcentaje' => 75,  'orden' => 3],
            ['clave' => 'critico',  'etiqueta' => 'Nivel crítico',  'color' => 'rojo',     'min_porcentaje' => 76, 'max_porcentaje' => 100, 'orden' => 4],
        ];

        foreach ($defaults as $d) {
            $this->niveles()->create(array_merge($d, [
                'resultado' => '',
                'reflexiona' => '',
                'recomendaciones' => [],
            ]));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar total de preguntas
    |--------------------------------------------------------------------------
    */

    public function actualizarTotalPreguntas(): void
    {
        $idsDimensiones = $this->dimensiones()
            ->pluck('id');

        $total = Pregunta::whereIn(
            'dimension_id',
            $idsDimensiones
        )->count();

        $this->update([
            'total_preguntas' => $total,
        ]);
    }
}
