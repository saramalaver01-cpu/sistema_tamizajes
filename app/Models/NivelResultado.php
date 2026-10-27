<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelResultado extends Model
{
    protected $table = 'niveles_resultado';

    protected $fillable = [
        'modulo_id',
        'clave',
        'etiqueta',
        'color',
        'min_porcentaje',
        'max_porcentaje',
        'resultado',
        'reflexiona',
        'recomendaciones',
        'orden',
    ];


    protected $casts = [
        'recomendaciones' => 'array',
        'min_porcentaje' => 'integer',
        'max_porcentaje' => 'integer',
        'orden' => 'integer',
    ];


    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }
}
