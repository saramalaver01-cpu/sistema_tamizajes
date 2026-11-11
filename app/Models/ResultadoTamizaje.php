<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoTamizaje extends Model
{
    protected $table = 'resultados_tamizaje';

    public $timestamps = false;

    protected $fillable = [
        'participante_id',
        'modulo',
        'nivel',
        'puntaje',
        'creado_en',
    ];

    protected static function booted(): void
    {
        static::creating(function (ResultadoTamizaje $resultado) {
            $campanaId = session('campana_id');

            if ($resultado->campana_id || ! $campanaId) {
                return;
            }

            // Solo se asocia si la campaña existe, está en curso y incluye este módulo
            $valida = Campana::whereKey($campanaId)
                ->where('activa', true)
                ->whereDate('fecha_inicio', '<=', today())
                ->whereDate('fecha_fin', '>=', today())
                ->whereHas('modulos', fn ($q) => $q->where('clave', $resultado->modulo))
                ->exists();

            if ($valida) {
                $resultado->campana_id = $campanaId;
            }
        });
    }

    public function participante()
    {
        return $this->belongsTo(Participante::class);
    }
}
