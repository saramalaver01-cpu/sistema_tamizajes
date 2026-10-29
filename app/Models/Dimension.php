<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    protected $table = 'dimensiones';


    protected $fillable = ['modulo_id', 'titulo', 'descripcion', 'orden'];

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function preguntas()
    {
        return $this->hasMany(Pregunta::class)->orderBy('orden');
    }
}
