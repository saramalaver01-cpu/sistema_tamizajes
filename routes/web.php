<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViolentometroController;
use App\Http\Controllers\BienestarEmocionalController;
use App\Http\Controllers\CargaAcademicaController;
use App\Http\Controllers\TamizajeController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ModuloController;
use App\Http\Controllers\Admin\PreguntaController;
use App\Http\Controllers\Admin\NivelResultadoController;
use App\Http\Controllers\Admin\EstadisticasController;
use App\Http\Controllers\Admin\AdministradoresController;
use App\Models\Modulo;

/*
|--------------------------------------------------------------------------
| Página principal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    $modulos = Modulo::activos()->get();

    return view('landing', compact('modulos'));

})->name('home');


/*
|--------------------------------------------------------------------------
| Panel de administración
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware('admin')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [
            DashboardController::class, 'index'
        ])->name('dashboard');

        // CRUD de módulos
        Route::resource('modulos', ModuloController::class)
            ->except(['show']);

        Route::patch('modulos/{modulo}/toggle', [
            ModuloController::class, 'toggle'
        ])->name('modulos.toggle');

        // Gestión de dimensiones y preguntas
        Route::get('modulos/{modulo}/preguntas', [
            PreguntaController::class, 'index'
        ])->name('modulos.preguntas.index');

        Route::post('modulos/{modulo}/dimensiones', [
            PreguntaController::class, 'storeDimension'
        ])->name('dimensiones.store');

        Route::put('dimensiones/{dimension}', [
            PreguntaController::class, 'updateDimension'
        ])->name('dimensiones.update');

        Route::delete('dimensiones/{dimension}', [
            PreguntaController::class, 'destroyDimension'
        ])->name('dimensiones.destroy');

        Route::post('dimensiones/{dimension}/preguntas', [
            PreguntaController::class, 'storePregunta'
        ])->name('preguntas.store');

        Route::put('preguntas/{pregunta}', [
            PreguntaController::class, 'updatePregunta'
        ])->name('preguntas.update');

        Route::delete('preguntas/{pregunta}', [
            PreguntaController::class, 'destroyPregunta'
        ])->name('preguntas.destroy');

        // Niveles de resultado
        Route::get('modulos/{modulo}/niveles', [
            NivelResultadoController::class, 'index'
        ])->name('modulos.niveles.index');

        Route::post('modulos/{modulo}/niveles', [
            NivelResultadoController::class, 'store'
        ])->name('niveles.store');

        Route::put('niveles/{nivel}', [
            NivelResultadoController::class, 'update'
        ])->name('niveles.update');

        Route::delete('niveles/{nivel}', [
            NivelResultadoController::class, 'destroy'
        ])->name('niveles.destroy');

        // Estadísticas
        Route::get('/estadisticas', [
            EstadisticasController::class, 'index'
        ])->name('estadisticas');

        // Administración de usuarios administradores
        Route::get('/administradores', [
            AdministradoresController::class, 'index'
        ])->name('administradores.index');

        Route::post('/administradores', [
            AdministradoresController::class, 'store'
        ])->name('administradores.store');

    });


/*
|--------------------------------------------------------------------------
| Recursos
|--------------------------------------------------------------------------
*/

Route::get('/recursos', function () {

    return view('recursos');

})->name('recursos');


/*
|--------------------------------------------------------------------------
| Solicitar apoyo
|--------------------------------------------------------------------------
*/

Route::get('/solicitar-apoyo', function () {

    return view('solicitar-apoyo');

})->name('solicitar-apoyo');


/*
|--------------------------------------------------------------------------
| Rutas protegidas para estudiantes
|--------------------------------------------------------------------------
*/

Route::middleware('estudiante')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Violentómetro
    |--------------------------------------------------------------------------
    */

    Route::get('/violentometro', [
        ViolentometroController::class, 'index'
    ])->name('violentometro');

    Route::post('/violentometro/evaluar', [
        ViolentometroController::class, 'evaluar'
    ])->name('violentometro.evaluar');


    /*
    |--------------------------------------------------------------------------
    | Bienestar emocional
    |--------------------------------------------------------------------------
    */

    Route::get('/bienestar-emocional', [
        BienestarEmocionalController::class, 'index'
    ])->name('bienestar.emocional');

    Route::post('/bienestar-emocional/evaluar', [
        BienestarEmocionalController::class, 'evaluar'
    ])->name('bienestar.emocional.evaluar');


    /*
    |--------------------------------------------------------------------------
    | Carga académica
    |--------------------------------------------------------------------------
    */

    Route::get('/carga-academica', [
        CargaAcademicaController::class, 'index'
    ])->name('carga-academica');

    Route::post('/carga-academica/evaluar', [
        CargaAcademicaController::class, 'evaluar'
    ])->name('carga-academica.evaluar');


    /*
    |--------------------------------------------------------------------------
    | Tamizaje genérico
    |--------------------------------------------------------------------------
    | Módulos creados desde el panel de administración
    */

    Route::get('/tamizaje/{clave}', [
        TamizajeController::class, 'index'
    ])->name('tamizaje.show');

    Route::post('/tamizaje/{clave}/evaluar', [
        TamizajeController::class, 'evaluar'
    ])->name('tamizaje.evaluar');

});


/*
|--------------------------------------------------------------------------
| Autenticación y registro
|--------------------------------------------------------------------------
*/

// Formulario de inicio de sesión
Route::get('/login', [
    AccessController::class, 'show'
])->name('login')->middleware('guest');

// Inicio de sesión de estudiantes
Route::post('/login/estudiante', [
    AccessController::class, 'estudiante'
])->name('login.estudiante');

// Inicio de sesión de administradores
Route::post('/login/administrador', [
    AccessController::class, 'administrador'
])->name('login.administrador');

// Formulario de registro de estudiantes
Route::get('/registro', [
    AccessController::class, 'mostrarRegistro'
])->name('registro.estudiante');

// Guardar registro de estudiantes
Route::post('/registro', [
    AccessController::class, 'registrar'
])->name('registro.estudiante.guardar');

// Cerrar sesión
Route::post('/logout', [
    AccessController::class, 'logout'
])->name('logout')->middleware('auth');

