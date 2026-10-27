<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $table = 'preguntas';


    protected $fillable = ['dimension_id', 'texto', 'invertida', 'critica', 'orden'];

    protected $casts = [
        'invertida' => 'boolean',
        'critica' => 'boolean',
    ];
    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }
}
