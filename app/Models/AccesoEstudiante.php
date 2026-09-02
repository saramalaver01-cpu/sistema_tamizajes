<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccesoEstudiante extends Model
{
     protected $table = 'accesos_estudiantes';

    public $timestamps = false;

    protected $fillable = [
        'email_hash',
        'accedido_en',
    ];
}