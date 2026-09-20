<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViolentometroController;
use App\Http\Controllers\BienestarEmocionalController;
use App\Http\Controllers\CargaAcademicaController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\Admin\DashboardController;


/*
|--------------------------------------------------------------------------
| Página principal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return view('landing');

})->name('home');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/


Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
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
| Login
|--------------------------------------------------------------------------
*/

Route::get('/login', [AccessController::class, 'show'])->name('login')->middleware('guest');
Route::post('/login/estudiante', [AccessController::class, 'estudiante'])->name('login.estudiante');
Route::post('/login/administrador', [AccessController::class, 'administrador'])->name('login.administrador');
Route::post('/logout', [AccessController::class, 'logout'])->name('logout');
Route::get('/registro', [AccessController::class, 'mostrarRegistro'])->name('registro.estudiante');
Route::post('/registro', [AccessController::class, 'registrar'])->name('registro.estudiante.guardar');
