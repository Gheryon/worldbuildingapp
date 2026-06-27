<?php

use Illuminate\Support\Facades\Route;

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
Route::resource('asentamientos', App\Http\Controllers\AsentamientoController::class);

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
Route::resource('conflictos', App\Http\Controllers\ConflictoController::class);

/*******construcciones routes********/
Route::resource('construcciones', App\Http\Controllers\ConstruccionController::class)->parameters(['construcciones' => 'construccion']);

/*******enlaces routes********/
Route::controller(App\Http\Controllers\EnlacesController::class)->group(function () {
    Route::get('/enlaces/index', 'index')->name('enlaces.index');
    Route::post('/enlaces/store', 'store')->name('enlace.store');
    Route::put('/enlaces/update', 'update')->name('enlace.update');
    Route::delete('/enlaces/destroy', 'destroy')->name('enlace.destroy');
});

/*******especies routes********/
// laravel convierte de 'especies' a 'especy' (incorrecto en castellano), se fuerza a 'especie'
Route::resource('especies', App\Http\Controllers\EspecieController::class)->parameters(['especies' => 'especie']);

/*******imagenes routes********/
Route::get('/galeria/index', [App\Http\Controllers\ImagenController::class, 'index'])->name('galeria.index');
Route::post('/galeria/store', [App\Http\Controllers\ImagenController::class, 'store'])->name('galeria.store');
Route::put('/galeria/{imagen}', [App\Http\Controllers\ImagenController::class, 'update'])->name('galeria.update');
Route::delete('/galeria/{imagen}', [App\Http\Controllers\ImagenController::class, 'destroy'])->name('galeria.destroy');
Route::get('/galeria/limpiar_imagenes', [App\Http\Controllers\ImagenController::class, 'limpiar_imagenes'])->name('galeria.limpiar_imagenes');
Route::delete('/imagenes/{entityType}/{entityId}/{imagen}', [App\Http\Controllers\ImagenController::class, 'destroyReference'])->name('imagenes.destroy-reference');

/*******lugares routes********/
Route::resource('lugares', App\Http\Controllers\LugaresController::class);

/*******nombres routes********/
Route::controller(App\Http\Controllers\NombresController::class)->group(function () {
    Route::get('/nombres/index', 'index')->name('nombres.index');
    Route::post('/nombres/store/nombre', 'store_nombre')->name('nombre.store_nombre');
    Route::put('/nombres/update', 'update')->name('nombres.update');
});

/*******organizaciones routes********/
// laravel convierte el plural a singular automáticamente, pero en castellano en este caso pasa a 'organizacione', lo cual da error, por eso se fuerza a usar 'organizacion' como parámetro singular en la ruta
Route::resource('organizaciones', App\Http\Controllers\OrganizacionController::class)->parameters(['organizaciones' => 'organizacion']);

/*******personajes routes********/
Route::resource('personajes', App\Http\Controllers\PersonajeController::class);

/*******religiones routes********/
// laravel convierte de 'religiones' a 'religione' (incorrecto en castellano), se fuerza a 'religion'
Route::resource('religiones', App\Http\Controllers\ReligionesController::class)->parameters(['religiones' => 'religion']);

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
