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
  Route::get('/articulos/index', 'index')->name('articulos.index');
  Route::get('/articulos/create', 'create')->name('articulos.create');
  Route::post('/articulos/articulos', 'store')->name('articulos.store');
  Route::get('/articulos/{id}', 'show')->name('articulos.show');
  Route::get('/articulos/{id}/edit', 'edit')->name('articulos.edit');
  Route::put('/articulos/{id}', 'update')->name('articulos.update');
  Route::delete('/articulos/destroy', 'destroy')->name('articulos.destroy');
});

/*******asentamientos routes********/
Route::controller(App\Http\Controllers\AsentamientoController::class)->group(function () {
  Route::get('/asentamientos/index/{orden?}/{tipo?}', 'index')->name('asentamientos.index');
  Route::get('/asentamientos/create', 'create')->name('asentamiento.create');
  Route::post('/asentamientos/store', 'store')->name('asentamiento.store');
  Route::get('/asentamientos/{id}', 'show')->name('asentamiento.show');
  Route::get('/asentamientos/{id}/edit', 'edit')->name('asentamiento.edit');
  Route::put('/asentamientos/{id}', 'update')->name('asentamiento.update');
  Route::delete('/asentamiento/destroy', 'destroy')->name('asentamiento.destroy');
});

/*******configuracion routes********/
Route::controller(App\Http\Controllers\ConfigurationController::class)->group(function () {
  Route::get('/config/index', 'index')->name('config.index');
  Route::post('/config/update/nombre_mundo', 'update_nombre_mundo')->name('config.update_nombre_mundo');
  Route::post('/config/update/fecha_mundo', 'update_fecha_mundo')->name('config.update_fecha_mundo');
  Route::post('/config/store/{type}', 'store')->name('config.store_generic');
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
  Route::get('/conflictos/{id}', 'show')->name('conflicto.show');
});

/*******construcciones routes********/
Route::controller(App\Http\Controllers\ConstruccionController::class)->group(function () {
  Route::get('/construcciones/index/{orden?}/{tipo?}', 'index')->name('construcciones.index');
  Route::get('/construcciones/create', 'create')->name('construccion.create');
  Route::post('/construcciones/store', 'store')->name('construccion.store');
  Route::get('/construcciones/{id}/edit', 'edit')->name('construccion.edit');
  Route::put('/construcciones/{id}', 'update')->name('construccion.update');
  Route::delete('/construccion/destroy', 'destroy')->name('construccion.destroy');
  Route::get('/construcciones/{id}', 'show')->name('construccion.show');
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
Route::resource('lugares', App\Http\Controllers\LugaresController::class);

/*******nombres routes********/
Route::controller(App\Http\Controllers\NombresController::class)->group(function () {
  Route::get('/nombres/index', 'index')->name('nombres.index');
  Route::post('/nombres/store/nombre', 'store_nombre')->name('nombre.store_nombre');
  Route::put('/nombres/update', 'update')->name('nombres.update');
});

/*******organizaciones routes********/
//laravel convierte el plural a singular automáticamente, pero en castellano en este caso pasa a 'organizacione', lo cual da error, por eso se fuerza a usar 'organizacion' como parámetro singular en la ruta
Route::resource('organizaciones', App\Http\Controllers\OrganizacionController::class)->parameters(['organizaciones' => 'organizacion']);

/*******personajes routes********/
Route::resource('personajes', App\Http\Controllers\PersonajeController::class);

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
Route::get('/timelines/index/', [App\Http\Controllers\EventosController::class, 'index'])->name('timelines.index');
Route::post('/timelines/store', [App\Http\Controllers\EventosController::class, 'store'])->name('evento.store');
Route::delete('/timelines/destroy', [App\Http\Controllers\EventosController::class, 'destroy'])->name('evento.destroy');
Route::get('/timelines/{id}/edit', [App\Http\Controllers\EventosController::class, 'edit'])->name('evento.edit');
Route::put('/timelines/{id}', [App\Http\Controllers\EventosController::class, 'update'])->name('evento.update');

/*******relatos routes********/
Route::controller(App\Http\Controllers\ArticuloController::class)->group(function () {
  Route::get('/relatos/index', 'index_relatos')->name('relatos.index');
  Route::get('/relatos/create', 'create_relato')->name('relatos.create');
  Route::post('/relatos/relatos', 'store_relato')->name('relatos.store');
  Route::get('/relatos/{id}', 'show_relato')->name('relatos.show');
  Route::get('/relatos/{id}/edit', 'edit_relato')->name('relatos.edit');
  Route::put('/relatos/{id}', 'update_relato')->name('relatos.update');
  Route::delete('/relatos/destroy', 'destroy_relato')->name('relatos.destroy');
});