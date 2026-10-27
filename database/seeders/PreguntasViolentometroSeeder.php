<?php

namespace Database\Seeders;

use App\Models\Modulo;
use App\Models\Dimension;
use Illuminate\Database\Seeder;

class PreguntasViolentometroSeeder extends Seeder
{
    public function run()
    {
        $modulo = Modulo::where('clave', 'violentometro')->first();

        if (!$modulo) {
            $this->command->warn('No existe el módulo "violentometro". Corre primero ModulosSeeder.');
            return;
        }

        // Escala y contextos (tal cual los tenías hardcodeados en la vista)
        $modulo->update([
            'opciones_escala' => ['Nunca', 'Casi nunca', 'Algunas veces', 'Frecuentemente', 'Siempre'],
            'contextos' => [
                ['valor' => 'pareja', 'etiqueta' => 'Relación de pareja', 'icono' => 'fa-heart', 'sujeto' => 'tu pareja'],
                ['valor' => 'familia', 'etiqueta' => 'Relaciones familiares', 'icono' => 'fa-house', 'sujeto' => 'algún miembro de tu familia'],
                ['valor' => 'amistad', 'etiqueta' => 'Relaciones de amistad', 'icono' => 'fa-user-group', 'sujeto' => 'algún amigo o amiga'],
                ['valor' => 'companeros', 'etiqueta' => 'Compañeros de estudio', 'icono' => 'fa-graduation-cap', 'sujeto' => 'algún compañero de estudio'],
                ['valor' => 'laboral', 'etiqueta' => 'Entorno laboral', 'icono' => 'fa-briefcase', 'sujeto' => 'alguna persona de tu entorno laboral'],
            ],
        ]);

        $dimensiones = [
            [
                'titulo' => '1. Descalificación y violencia verbal',
                'preguntas' => [
                    '¿Con qué frecuencia {sujeto} hace comentarios que te hacen sentir inferior, incapaz o poco valioso(a)?',
                    '¿Con qué frecuencia {sujeto} ridiculiza tus opiniones, sentimientos o decisiones?',
                    '¿Con qué frecuencia {sujeto} utiliza insultos, burlas o palabras ofensivas hacia ti?',
                    '¿Con qué frecuencia {sujeto} minimiza lo que sientes o te hace creer que estás exagerando?',
                ],
            ],
            [
                'titulo' => '2. Manipulación, control y abuso de poder',
                'preguntas' => [
                    '¿Con qué frecuencia {sujeto} intenta controlar las decisiones que tomas?',
                    '¿Con qué frecuencia {sujeto} intenta decidir con quién puedes hablar o compartir tu tiempo?',
                    '¿Con qué frecuencia {sujeto} revisa tu celular, redes sociales o pertenencias sin tu consentimiento?',
                    '¿Con qué frecuencia {sujeto} utiliza el chantaje emocional para conseguir que hagas algo que no deseas?',
                    '¿Con qué frecuencia {sujeto} utiliza su posición de autoridad, poder o jerarquía para presionarte, intimidarte o influir en tus decisiones?',
                ],
            ],
            [
                'titulo' => '3. Aislamiento y violencia digital',
                'preguntas' => [
                    '¿Con qué frecuencia {sujeto} intenta alejarte de personas importantes para ti?',
                    '¿Con qué frecuencia {sujeto} publica, comparte o amenaza con divulgar información personal sin tu autorización?',
                    '¿Con qué frecuencia {sujeto} controla tu actividad en redes sociales o invade tu privacidad mediante llamadas, mensajes o seguimiento constante?',
                ],
            ],
            [
                'titulo' => '4. Intimidación y amenazas',
                'preguntas' => [
                    '¿Con qué frecuencia {sujeto} levanta la voz, grita o adopta actitudes intimidantes hacia ti?',
                    '¿Con qué frecuencia {sujeto} te hace sentir miedo por su forma de actuar o reaccionar?',
                    '¿Con qué frecuencia {sujeto} amenaza con hacerte daño a ti o a personas cercanas?',
                    '¿Con qué frecuencia {sujeto} destruye objetos o golpea cosas con el propósito de intimidarte?',
                    '¿Con qué frecuencia has sentido que no puedes expresar desacuerdo o negarte a una solicitud por temor a represalias académicas, laborales, familiares o personales?',
                ],
            ],
            [
                'titulo' => '5. Violencia física',
                'preguntas' => [
                    '¿Con qué frecuencia {sujeto} te ha empujado, sujetado con fuerza o agredido físicamente?',
                    '¿Con qué frecuencia {sujeto} ha intentado impedir que salgas de un lugar o restringir tu movilidad?',
                ],
            ],
            [
                'titulo' => '6. Violencia sexual',
                'preguntas' => [
                    '¿Con qué frecuencia {sujeto} te ha presionado para realizar actos de carácter sexual que no deseas?',
                    '¿Con qué frecuencia {sujeto} ha tenido contacto físico o sexual contigo sin tu consentimiento?',
                ],
            ],
        ];

        foreach ($dimensiones as $ordenDim => $dim) {
            $dimension = Dimension::updateOrCreate(
                ['modulo_id' => $modulo->id, 'titulo' => $dim['titulo']],
                ['orden' => $ordenDim]
            );

            foreach ($dim['preguntas'] as $ordenPregunta => $texto) {
                $dimension->preguntas()->updateOrCreate(
                    ['texto' => $texto],
                    ['orden' => $ordenPregunta]
                );
            }
        }

        $modulo->actualizarTotalPreguntas();
    }
}
