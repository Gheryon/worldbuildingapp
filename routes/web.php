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
Route::get('/personaje/{id}', [App\Http\Controllers\VistaController::class, 'show_personaje'])->name('personaje.show');
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
Route::get('/content/{id}', [App\Http\Controllers\VistaController::class, 'show_organizacion'])->name('organizacion.show');
Route::get('/organizaciones/create', [App\Http\Controllers\OrganizacionController::class, 'create'])->name('organizacion.create');
Route::post('/organizaciones/store', [App\Http\Controllers\OrganizacionController::class, 'store'])->name('organizacion.store');
Route::get('/organizaciones/{id}/edit', [App\Http\Controllers\OrganizacionController::class, 'edit'])->name('organizacion.edit');
Route::put('/organizaciones/{id}', [App\Http\Controllers\OrganizacionController::class, 'update'])->name('organizacion.update');
Route::delete('/organizacion/destroy', [App\Http\Controllers\OrganizacionController::class, 'destroy'])->name('organizacion.destroy');

/*******articulos routes********/
Route::controller(App\Http\Controllers\ArticuloController::class)->group(function () {
    Route::get('/articulos/index', 'index')->name('articulos');
    Route::get('/articulos/create', 'create')->name('articulos.create');
    Route::post('/articulos/articulos', 'store')->name('articulos.store');
    Route::get('/articulos/{id}', 'show')->name('articulos.show');
    Route::get('/articulos/{id}/get', 'get')->name('articulos.get');
    Route::get('/articulos/{id}/edit', 'edit')->name('articulos.edit');
    Route::put('/articulos/{id}', 'update')->name('articulos.update');
    Route::delete('/articulos/{id}', 'destroy')->name('articulos.destroy');

});

/*******imagenes routes********/
Route::get('/galeria/index', [App\Http\Controllers\ImagenController::class, 'index'])->name('galeria.index');
Route::post('/galeria/store', [App\Http\Controllers\ImagenController::class, 'store'])->name('galeria.store');
Route::get('/galeria/limpiar_imagenes', [App\Http\Controllers\ImagenController::class, 'limpiar_imagenes'])->name('galeria.limpiar_imagenes');

/*******configuracion routes********/
Route::get('/config/index', [App\Http\Controllers\ConfigurationController::class, 'index'])->name('config.index');
Route::post('/config/store/tipo_asentamiento', [App\Http\Controllers\ConfigurationController::class, 'store_tipo_asentamiento'])->name('config.store_tipo_asentamiento');
Route::post('/config/store/tipo_conflicto', [App\Http\Controllers\ConfigurationController::class, 'store_tipo_conflicto'])->name('config.store_tipo_conflicto');
Route::post('/config/store/tipo_lugar', [App\Http\Controllers\ConfigurationController::class, 'store_tipo_lugar'])->name('config.store_tipo_lugar');
Route::post('/config/store/tipo_organizacion', [App\Http\Controllers\ConfigurationController::class, 'store_tipo_organizacion'])->name('config.store_tipo_organizacion');
Route::post('/config/store/linea_temporal', [App\Http\Controllers\ConfigurationController::class, 'store_linea_temporal'])->name('config.store_linea_temporal');
Route::put('/config/update', [App\Http\Controllers\ConfigurationController::class, 'update'])->name('config.update');
Route::delete('/config/destroy', [App\Http\Controllers\ConfigurationController::class, 'destroy'])->name('config.destroy');