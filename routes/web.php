<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

Route::get('/', function () {
    return view('main');
});

/*******personajes routes********/
Route::get('/personajes/index', [App\Http\Controllers\PersonajeController::class, 'index'])->name('personajes.index');
Route::get('/content/{id}', [App\Http\Controllers\VistaController::class, 'show_personaje'])->name('personaje.show');
Route::get('/personajes/create', [App\Http\Controllers\PersonajeController::class, 'create'])->name('personaje.create');
Route::post('/personajes/store', [App\Http\Controllers\PersonajeController::class, 'store'])->name('personaje.store');
Route::get('/personajes/{id}/edit', [App\Http\Controllers\PersonajeController::class, 'edit'])->name('personaje.edit');
Route::put('/personajes/{id}', [App\Http\Controllers\PersonajeController::class, 'update'])->name('personaje.update');
Route::delete('/personaje/destroy', [App\Http\Controllers\PersonajeController::class, 'destroy'])->name('personaje.destroy');

/*******timelines routes********/
Route::get('/timelines/index/{orden?}/{cronologia?}', [App\Http\Controllers\TimelineController::class, 'index'])->name('timelines.index');
Route::post('/timelines/store', [App\Http\Controllers\TimelineController::class, 'store'])->name('evento.store');
Route::get('/timelines/{id}/edit', [App\Http\Controllers\TimelineController::class, 'edit'])->name('evento.edit');
Route::delete('/timelines/destroy', [App\Http\Controllers\TimelineController::class, 'destroy'])->name('evento.destroy');

/*******organizaciones routes********/
Route::get('/organizaciones/index', [App\Http\Controllers\OrganizacionController::class, 'index'])->name('organizaciones.index');
//Route::get('/content/{id}', [App\Http\Controllers\VistaController::class, 'show_personaje'])->name('personaje.show');
//Route::get('/personajes/create', [App\Http\Controllers\PersonajeController::class, 'create'])->name('personaje.create');
//Route::post('/personajes/store', [App\Http\Controllers\PersonajeController::class, 'store'])->name('personaje.store');
//Route::get('/personajes/{id}/edit', [App\Http\Controllers\PersonajeController::class, 'edit'])->name('personaje.edit');
//Route::put('/personajes/{id}', [App\Http\Controllers\PersonajeController::class, 'update'])->name('personaje.update');
//Route::delete('/personaje/destroy', [App\Http\Controllers\PersonajeController::class, 'destroy'])->name('personaje.destroy');

/*******articulos routes********/
Route::get('/articulos/index', [App\Http\Controllers\ArticuloController::class, 'index'])->name('articulos');
Route::get('/articulos/create', [App\Http\Controllers\ArticuloController::class, 'create'])->name('articulos.create');
Route::post('/articulos/articulos', [App\Http\Controllers\ArticuloController::class, 'store'])->name('articulos.store');
Route::get('/articulos/{id}', [App\Http\Controllers\ArticuloController::class, 'show'])->name('articulos.show');
Route::get('/articulos/{id}/get', [App\Http\Controllers\ArticuloController::class, 'get'])->name('articulos.get');
Route::get('/articulos/{id}/edit', [App\Http\Controllers\ArticuloController::class, 'edit'])->name('articulos.edit');
Route::put('/articulos/{id}', [App\Http\Controllers\ArticuloController::class, 'update'])->name('articulos.update');
Route::delete('/articulos/{id}', [App\Http\Controllers\ArticuloController::class, 'destroy'])->name('articulos.destroy');

/*******configuracion routes********/
Route::get('/config/index', [App\Http\Controllers\ConfigurationController::class, 'index'])->name('config.index');
Route::post('/config/store/tipo_asentamiento', [App\Http\Controllers\ConfigurationController::class, 'store_tipo_asentamiento'])->name('config.store_tipo_asentamiento');
Route::post('/config/store/tipo_conflicto', [App\Http\Controllers\ConfigurationController::class, 'store_tipo_conflicto'])->name('config.store_tipo_conflicto');
Route::post('/config/store/tipo_lugar', [App\Http\Controllers\ConfigurationController::class, 'store_tipo_lugar'])->name('config.store_tipo_lugar');
Route::post('/config/store/tipo_organizacion', [App\Http\Controllers\ConfigurationController::class, 'store_tipo_organizacion'])->name('config.store_tipo_organizacion');
Route::post('/config/store/linea_temporal', [App\Http\Controllers\ConfigurationController::class, 'store_linea_temporal'])->name('config.store_linea_temporal');
Route::put('/config/update', [App\Http\Controllers\ConfigurationController::class, 'update'])->name('config.update');
Route::delete('/config/destroy', [App\Http\Controllers\ConfigurationController::class, 'destroy'])->name('config.destroy');