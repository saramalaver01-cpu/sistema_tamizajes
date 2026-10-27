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
| Dashboard
|--------------------------------------------------------------------------
*/


Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD de módulos
    Route::resource('modulos', ModuloController::class)->except(['show']);
    Route::patch('modulos/{modulo}/toggle', [ModuloController::class, 'toggle'])->name('modulos.toggle');

    // CRUD de preguntas (dimensiones + preguntas de cada módulo)
    Route::get('modulos/{modulo}/preguntas', [PreguntaController::class, 'index'])->name('modulos.preguntas.index');
    Route::post('modulos/{modulo}/dimensiones', [PreguntaController::class, 'storeDimension'])->name('dimensiones.store');
    Route::put('dimensiones/{dimension}', [PreguntaController::class, 'updateDimension'])->name('dimensiones.update');
    Route::delete('dimensiones/{dimension}', [PreguntaController::class, 'destroyDimension'])->name('dimensiones.destroy');
    Route::post('dimensiones/{dimension}/preguntas', [PreguntaController::class, 'storePregunta'])->name('preguntas.store');
    Route::put('preguntas/{pregunta}', [PreguntaController::class, 'updatePregunta'])->name('preguntas.update');
    Route::delete('preguntas/{pregunta}', [PreguntaController::class, 'destroyPregunta'])->name('preguntas.destroy');

    // Niveles de resultado (motor genérico)
    Route::get('modulos/{modulo}/niveles', [NivelResultadoController::class, 'index'])->name('modulos.niveles.index');
    Route::post('modulos/{modulo}/niveles', [NivelResultadoController::class, 'store'])->name('niveles.store');
    Route::put('niveles/{nivel}', [NivelResultadoController::class, 'update'])->name('niveles.update');
    Route::delete('niveles/{nivel}', [NivelResultadoController::class, 'destroy'])->name('niveles.destroy');

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
| Violentometro
|--------------------------------------------------------------------------
*/

Route::get('/violentometro', [ViolentometroController::class, 'index'])
    ->name('violentometro');

Route::post('/violentometro/evaluar', [ViolentometroController::class, 'evaluar'])
    ->name('violentometro.evaluar');



/*
|--------------------------------------------------------------------------
| Bienestar emocional
|--------------------------------------------------------------------------
*/

Route::get('/bienestar-emocional', [BienestarEmocionalController::class, 'index'])
    ->name('bienestar.emocional');

Route::post('/bienestar-emocional/evaluar', [BienestarEmocionalController::class, 'evaluar'])
    ->name('bienestar.emocional.evaluar');

/*
|--------------------------------------------------------------------------
| Carga académica
|--------------------------------------------------------------------------
*/

Route::get('/carga-academica', [CargaAcademicaController::class, 'index'])
    ->name('carga-academica');

Route::post('/carga-academica/evaluar', [CargaAcademicaController::class, 'evaluar'])
    ->name('carga-academica.evaluar');

/*
|--------------------------------------------------------------------------
| Tamizaje genérico (módulos nuevos creados desde el admin)
|--------------------------------------------------------------------------
*/

Route::get('/tamizaje/{clave}', [TamizajeController::class, 'index'])
    ->name('tamizaje.show');

Route::post('/tamizaje/{clave}/evaluar', [TamizajeController::class, 'evaluar'])
    ->name('tamizaje.evaluar');

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::get('/login', [AccessController::class, 'show'])->name('login')->middleware('guest');
Route::post('/login/estudiante', [AccessController::class, 'estudiante'])->name('login.estudiante');
Route::post('/login/administrador', [AccessController::class, 'administrador'])->name('login.administrador');
Route::post('/logout', [AccessController::class, 'logout'])->name('logout')->middleware('auth');
