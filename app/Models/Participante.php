<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;

class Participante extends Model
{
    protected $table = 'participantes';

    protected $fillable = [
        'email_hash',
        'rol_institucional',
        'facultad',
        'programa_academico',
        'semestre',
        'jornada',
        'dependencia',
        'edad',
        'sexo',
        'identidad_genero',
        'orientacion_sexual',
        'estado_civil',
        'nivel_educativo',
        'estrato',
        'municipio_residencia',
        'consentimiento_at',
    ];

    protected $casts = [
        'consentimiento_at' => 'datetime',
    ];

    /**
     * Convierte el correo institucional en un identificador seudónimo e irreversible.
     *
     * Se usa HMAC-SHA256 con una llave secreta (ANON_HASH_KEY). Así:
     *  - el mismo correo siempre da el mismo hash (permite reconocer al participante
     *    y mostrarle su propio historial),
     *  - nadie que solo tenga la base de datos puede recuperar el correo ni
     *    comprobar si un correo concreto está registrado.
     */
    public static function hashCorreo(string $email): string
    {
        $llave = config('tamizaje.anon_hash_key');

        if (empty($llave)) {
            throw new RuntimeException('Falta ANON_HASH_KEY en el archivo .env');
        }

        return hash_hmac('sha256', strtolower(trim($email)), $llave);
    }
}
