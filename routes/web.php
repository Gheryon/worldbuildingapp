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

/*******articulos routes********/
Route::controller(App\Http\Controllers\ArticuloController::class)->group(function () {
  Route::get('/articulos/index/{orden?}/{filtro?}', 'index')->name('articulos');
  Route::get('/articulos/create', 'create')->name('articulos.create');
  Route::post('/articulos/articulos', 'store')->name('articulos.store');
  Route::get('/articulos/{id}', 'show')->name('articulos.show');
  Route::get('/articulos/{id}/edit', 'edit')->name('articulos.edit');
  Route::put('/articulos/{id}', 'update')->name('articulos.update');
  Route::delete('/articulos/destroy', 'destroy')->name('articulos.destroy');
  Route::get('/articulos', 'search')->name('articulos.search');
});

/*******asentamientos routes********/
Route::controller(App\Http\Controllers\AsentamientoController::class)->group(function () {
  Route::get('/asentamientos/index/{orden?}/{tipo?}', 'index')->name('asentamientos.index');
  Route::get('/asentamientos/create', 'create')->name('asentamiento.create');
  Route::post('/asentamientos/store', 'store')->name('asentamiento.store');
  Route::get('/asentamientos/{id}/edit', 'edit')->name('asentamiento.edit');
  Route::put('/asentamientos/{id}', 'update')->name('asentamiento.update');
  Route::delete('/asentamiento/destroy', 'destroy')->name('asentamiento.destroy');
  Route::get('/asentamientos', 'search')->name('asentamientos.search');
});

/*******configuracion routes********/
Route::controller(App\Http\Controllers\ConfigurationController::class)->group(function () {
  Route::get('/config/index', 'index')->name('config.index');
  Route::post('/config/update/nombre_mundo', 'update_nombre_mundo')->name('config.update_nombre_mundo');
  Route::post('/config/update/fecha_mundo', 'update_fecha_mundo')->name('config.update_fecha_mundo');
  Route::post('/config/store/tipo_asentamiento', 'store_tipo_asentamiento')->name('config.store_tipo_asentamiento');
  Route::post('/config/store/tipo_conflicto', 'store_tipo_conflicto')->name('config.store_tipo_conflicto');
  Route::post('/config/store/tipo_construccion', 'store_tipo_construccion')->name('config.store_tipo_construccion');
  Route::post('/config/store/tipo_lugar', 'store_tipo_lugar')->name('config.store_tipo_lugar');
  Route::post('/config/store/tipo_organizacion', 'store_tipo_organizacion')->name('config.store_tipo_organizacion');
  Route::post('/config/store/linea_temporal', 'store_linea_temporal')->name('config.store_linea_temporal');
  Route::put('/config/update', 'update')->name('config.update');
  Route::delete('/config/destroy', 'destroy')->name('config.destroy');
});

/*******conflictos routes********/
Route::controller(App\Http\Controllers\ConflictoController::class)->group(function () {
  Route::get('/conflictos/index/{orden?}/{tipo?}', 'index')->name('conflictos.index');
  Route::get('/conflictos/create', 'create')->name('conflicto.create');
  Route::post('/conflictos/store', 'store')->name('conflicto.store');
  Route::get('/conflictos/{id}/edit', 'edit')->name('conflicto.edit');
  Route::put('/conflictos/{id}', 'update')->name('conflicto.update');
  Route::delete('/conflicto/destroy', 'destroy')->name('conflicto.destroy');
  Route::get('/conflictos', 'search')->name('conflictos.search');
});

/*******construcciones routes********/
Route::controller(App\Http\Controllers\ConstruccionController::class)->group(function () {
  Route::get('/construcciones/index/{orden?}/{tipo?}', 'index')->name('construcciones.index');
  Route::get('/construcciones/create', 'create')->name('construccion.create');
  Route::post('/construcciones/store', 'store')->name('construccion.store');
  Route::get('/construcciones/{id}/edit', 'edit')->name('construccion.edit');
  Route::put('/construcciones/{id}', 'update')->name('construccion.update');
  Route::delete('/construccion/destroy', 'destroy')->name('construccion.destroy');
  Route::get('/construcciones', 'search')->name('construcciones.search');
});

/*******enlaces routes********/
Route::controller(App\Http\Controllers\EnlacesController::class)->group(function () {
  Route::get('/enlaces/index', 'index')->name('enlaces.index');
  Route::post('/enlaces/store', 'store')->name('enlace.store');
  Route::put('/enlaces/update', 'update')->name('enlace.update');
  Route::delete('/enlaces/destroy', 'destroy')->name('enlace.destroy');
});

/*******especies routes********/
Route::controller(App\Http\Controllers\EspecieController::class)->group(function () {
  Route::get('/especies/index/{orden?}', 'index')->name('especies.index');
  Route::get('/especies/create', 'create')->name('especie.create');
  Route::post('/especies/store', 'store')->name('especie.store');
  Route::get('/especies/{id}/edit', 'edit')->name('especie.edit');
  Route::put('/especies/{id}', 'update')->name('especie.update');
  Route::delete('/especie/destroy', 'destroy')->name('especie.destroy');
  Route::get('/especies/{id}', 'show')->name('especie.show');
});

/*******imagenes routes********/
Route::get('/galeria/index', [App\Http\Controllers\ImagenController::class, 'index'])->name('galeria.index');
Route::post('/galeria/store', [App\Http\Controllers\ImagenController::class, 'store'])->name('galeria.store');
Route::get('/galeria/limpiar_imagenes', [App\Http\Controllers\ImagenController::class, 'limpiar_imagenes'])->name('galeria.limpiar_imagenes');

/*******lugares routes********/
Route::controller(App\Http\Controllers\LugaresController::class)->group(function () {
  Route::get('/lugares/index/{orden?}/{tipo?}', 'index')->name('lugares.index');
  Route::get('/lugares/create', 'create')->name('lugar.create');
  Route::post('/lugares/store', 'store')->name('lugar.store');
  Route::get('/lugares/{id}/edit', 'edit')->name('lugar.edit');
  Route::put('/lugares/{id}', 'update')->name('lugar.update');
  Route::delete('/lugar/destroy', 'destroy')->name('lugar.destroy');
  Route::get('/lugares', 'search')->name('lugares.search');
});

/*******nombres routes********/
Route::controller(App\Http\Controllers\NombresController::class)->group(function () {
  Route::get('/nombres/index', 'index')->name('nombres.index');
  Route::post('/nombres/store/nombre', 'store_nombre')->name('nombre.store_nombre');
  Route::put('/nombres/update', 'update')->name('nombres.update');
});

/*******organizaciones routes********/
Route::controller(App\Http\Controllers\OrganizacionController::class)->group(function () {
  Route::get('/organizaciones/index', 'index')->name('organizaciones.index');
  Route::get('/organizaciones/create', 'create')->name('organizacion.create');
  Route::post('/organizaciones/store', 'store')->name('organizacion.store');
  Route::get('/organizaciones/{id}/edit', 'edit')->name('organizacion.edit');
  Route::put('/organizaciones/{id}', 'update')->name('organizacion.update');
  Route::delete('/organizacion/destroy', 'destroy')->name('organizacion.destroy');
  Route::get('/organizaciones/{id}', 'show')->name('organizacion.show');
});

/*******personajes routes********/
Route::controller(App\Http\Controllers\PersonajeController::class)->group(function () {
  Route::get('/personajes', 'index')->name('personajes.index');
  Route::get('/personajes/create', 'create')->name('personaje.create');
  Route::post('/personajes/store', 'store')->name('personaje.store');
  Route::get('/personajes/{id}/edit', 'edit')->name('personaje.edit');
  Route::put('/personajes/{id}', 'update')->name('personaje.update');
  Route::delete('/personaje/destroy', 'destroy')->name('personaje.destroy');
  Route::get('/personaje/{id}', 'show')->name('personaje.show');
});

/*******religiones routes********/
Route::controller(App\Http\Controllers\ReligionesController::class)->group(function () {
  Route::get('/religiones/index', 'index')->name('religiones.index');
  Route::get('/religiones/create', 'create')->name('religion.create');
  Route::post('/religiones/store', 'store')->name('religion.store');
  Route::get('/religiones/{id}/edit', 'edit')->name('religion.edit');
  Route::put('/religiones/{id}', 'update')->name('religion.update');
  Route::delete('/religiones/destroy', 'destroy')->name('religion.destroy');
  Route::get('/religiones/{id}', 'show')->name('religion.show');
});

/*******timelines routes********/
Route::get('/timelines/index/{orden?}/{cronologia?}', [App\Http\Controllers\TimelineController::class, 'index'])->name('timelines.index');
Route::post('/timelines/store', [App\Http\Controllers\TimelineController::class, 'store'])->name('evento.store');
Route::get('/timelines/{id}/edit', [App\Http\Controllers\TimelineController::class, 'edit'])->name('evento.edit');
Route::delete('/timelines/destroy', [App\Http\Controllers\TimelineController::class, 'destroy'])->name('evento.destroy');

/*******relatos routes********/
Route::controller(App\Http\Controllers\ArticuloController::class)->group(function () {
  Route::get('/relatos/index/{orden?}', 'index_relatos')->name('relatos');
  Route::get('/relatos/create', 'create_relato')->name('relatos.create');
  Route::post('/relatos/relatos', 'store_relato')->name('relatos.store');
  Route::get('/relatos/{id}', 'show_relato')->name('relatos.show');
  Route::get('/relatos/{id}/edit', 'edit_relato')->name('relatos.edit');
  Route::put('/relatos/{id}', 'update_relato')->name('relatos.update');
  Route::delete('/relatos/destroy', 'destroy_relato')->name('relatos.destroy');
  Route::get('/relatos', 'search_relatos')->name('relatos.search');
});

/*******vistas routes********/
Route::controller(App\Http\Controllers\VistaController::class)->group(function () {
  Route::get('/asentamientos/{id}', 'show_asentamiento')->name('asentamiento.show');
  Route::get('/lugares/{id}', 'show_lugar')->name('lugar.show');
  Route::get('/conflictos/{id}', 'show_conflicto')->name('conflicto.show');
  Route::get('/construcciones/{id}', 'show_construccion')->name('construccion.show');
});