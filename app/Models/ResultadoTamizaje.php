<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoTamizaje extends Model
{
    protected $table = 'resultados_tamizaje';

    public $timestamps = false;

    protected $fillable = [
        'modulo',
        'nivel',
        'puntaje',
        'creado_en',
    ];
}