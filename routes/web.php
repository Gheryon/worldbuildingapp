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
Route::controller(App\Http\Controllers\PersonajeController::class)->group(function () {
    Route::get('/personajes/index', 'index')->name('personajes.index');
    Route::get('/personajes/create', 'create')->name('personaje.create');
    Route::post('/personajes/store', 'store')->name('personaje.store');
    Route::get('/personajes/{id}/edit', 'edit')->name('personaje.edit');
    Route::put('/personajes/{id}', 'update')->name('personaje.update');
    Route::delete('/personaje/destroy', 'destroy')->name('personaje.destroy');    
});

/*******organizaciones routes********/
Route::controller(App\Http\Controllers\OrganizacionController::class)->group(function () {
    Route::get('/organizaciones/index', 'index')->name('organizaciones.index');
    Route::get('/organizaciones/create', 'create')->name('organizacion.create');
    Route::post('/organizaciones/store', 'store')->name('organizacion.store');
    Route::get('/organizaciones/{id}/edit', 'edit')->name('organizacion.edit');
    Route::put('/organizaciones/{id}', 'update')->name('organizacion.update');
    Route::delete('/organizacion/destroy', 'destroy')->name('organizacion.destroy');
});
/*******religiones routes********/
Route::controller(App\Http\Controllers\ReligionesController::class)->group(function () {
    Route::get('/religiones/index', 'index')->name('religiones.index');
    Route::get('/religiones/create', 'create')->name('religion.create');
    Route::post('/religiones/store', 'store')->name('religion.store');
    Route::get('/religiones/{id}/edit', 'edit')->name('religion.edit');
    Route::put('/religiones/{id}', 'update')->name('religion.update');
    Route::delete('/religiones/destroy', 'destroy')->name('religion.destroy');
});

/*******lugares routes********/
Route::controller(App\Http\Controllers\LugaresController::class)->group(function () {
    Route::get('/lugares/index', 'index')->name('lugares.index');
    Route::get('/lugares/create', 'create')->name('lugar.create');
    Route::post('/lugares/store', 'store')->name('lugar.store');
    Route::get('/lugares/{id}/edit', 'edit')->name('lugar.edit');
    Route::put('/lugares/{id}', 'update')->name('lugar.update');
    Route::delete('/lugar/destroy', 'destroy')->name('lugar.destroy');
});

/*******timelines routes********/
Route::get('/timelines/index/{orden?}/{cronologia?}', [App\Http\Controllers\TimelineController::class, 'index'])->name('timelines.index');
Route::post('/timelines/store', [App\Http\Controllers\TimelineController::class, 'store'])->name('evento.store');
Route::get('/timelines/{id}/edit', [App\Http\Controllers\TimelineController::class, 'edit'])->name('evento.edit');
Route::delete('/timelines/destroy', [App\Http\Controllers\TimelineController::class, 'destroy'])->name('evento.destroy');

/*******vistas routes********/
Route::controller(App\Http\Controllers\VistaController::class)->group(function () {
    Route::get('/content/{id}', 'show_organizacion')->name('organizacion.show');
    Route::get('/personaje/{id}', 'show_personaje')->name('personaje.show');
    Route::get('/lugares/{id}', 'show_lugar')->name('lugar.show');
    Route::get('/religiones/{id}', 'show_religion')->name('religion.show');
});

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

/*******enlaces routes********/
Route::controller(App\Http\Controllers\EnlacesController::class)->group(function () {
    Route::get('/enlaces/index', 'index')->name('enlaces.index');
    Route::post('/enlaces/store', 'store')->name('enlace.store');
    Route::put('/enlaces/update', 'update')->name('enlace.update');
    Route::delete('/enlaces/destroy', 'destroy')->name('enlace.destroy');
});

/*******configuracion routes********/
Route::controller(App\Http\Controllers\ConfigurationController::class)->group(function () {
    Route::get('/config/index', 'index')->name('config.index');
    Route::post('/config/store/tipo_asentamiento', 'store_tipo_asentamiento')->name('config.store_tipo_asentamiento');
    Route::post('/config/store/tipo_conflicto', 'store_tipo_conflicto')->name('config.store_tipo_conflicto');
    Route::post('/config/store/tipo_lugar', 'store_tipo_lugar')->name('config.store_tipo_lugar');
    Route::post('/config/store/tipo_organizacion', 'store_tipo_organizacion')->name('config.store_tipo_organizacion');
    Route::post('/config/store/linea_temporal', 'store_linea_temporal')->name('config.store_linea_temporal');
    Route::put('/config/update', 'update')->name('config.update');
    Route::delete('/config/destroy', 'destroy')->name('config.destroy');
});