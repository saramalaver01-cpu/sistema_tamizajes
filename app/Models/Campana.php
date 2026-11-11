<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Campana extends Model
{
    protected $table = 'campanas';

    protected $fillable = [
        'nombre', 'descripcion', 'codigo',
        'fecha_inicio', 'fecha_fin', 'activa', 'ingresos',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
        'activa'       => 'boolean',
    ];

    public function modulos()
    {
        return $this->belongsToMany(Modulo::class, 'campana_modulo')
            ->orderBy('modulos.orden');
    }

    public function resultados()
    {
        return $this->hasMany(ResultadoTamizaje::class, 'campana_id');
    }

    // Genera un código corto y único para el enlace (ej: k3x9a2mq)
    public static function generarCodigo(): string
    {
        do {
            $codigo = Str::lower(Str::random(8));
        } while (static::where('codigo', $codigo)->exists());

        return $codigo;
    }

    // en_curso | programada | finalizada | pausada
    public function getEstadoAttribute(): string
    {
        if (! $this->activa) {
            return 'pausada';
        }
        if (today()->lt($this->fecha_inicio)) {
            return 'programada';
        }
        if (today()->gt($this->fecha_fin)) {
            return 'finalizada';
        }

        return 'en_curso';
    }

    public function getEstadoEtiquetaAttribute(): string
    {
        return match ($this->estado) {
            'en_curso'   => 'En curso',
            'programada' => 'Programada',
            'finalizada' => 'Finalizada',
            default      => 'Pausada',
        };
    }

    // Dirección pública que se comparte y se convierte en QR
    public function getUrlPublicaAttribute(): string
    {
        return route('campanas.entrada', $this->codigo);
    }
}
