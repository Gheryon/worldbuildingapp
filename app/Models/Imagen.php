<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class Imagen extends Model
{
  use HasFactory;

  protected $table = 'imagenes';
  public $timestamps = false;

  protected $fillable = [
    'nombre',
    'path',
    'owner',
    'table_owner',
    'categoria_id',
  ];

  /**
   * Accessor para obtener el nombre del dueño.
   */
  public function getOwnerNameAttribute()
  {
    if ($this->table_owner === 'galeria') {
      return null;
    }

    $model = match ($this->table_owner) {
      'articulos'       => new articulo(),
      'construcciones'  => new Construccion(),
      'personajes'      => new Personaje(),
      'lugares'         => new Lugar(),
      'organizaciones'  => new Organizacion(),
      'especies'        => new Especie(),
      'religiones'      => new Religion(),
      'conflictos'      => new Conflicto(),
      'asentamientos'   => new Asentamiento(),
      'eventos'         => new Evento(),
      default           => null,
    };

    if (!$model) return 'Desconocido';

    $record = $model->newQuery()->find($this->owner);
    return $record ? $record->nombre : 'Desconocido';
  }

  /**
   * La categoría a la que pertenece la imagen.
   */
  public function categoria()
  {
    return $this->belongsTo(Categoria::class);
  }

  /**
   * Lógica para subir una imagen.
   */
  public static function subirImagen($request)
  {
    return DB::transaction(function () use ($request) {
      $imageFile = $request->file('imagen');
      $cleanName = preg_replace('/\s+$/', '', $request->nombre);
      $filename = $cleanName . '_' . time() . '.' . $imageFile->getClientOriginalExtension();

      $path = $imageFile->storeAs('imagenes', $filename, 'public');

      return self::create([
        'nombre' => basename($path),
        'path' => $path,
        'owner' => 0,
        'table_owner' => 'galeria',
        'categoria_id' => $request->categoria_id,
      ]);
    });
  }

  /**
   * Lógica para actualizar/renombrar una imagen.
   */
  public function renombrarImagen(string $nuevoNombre, $categoriaId = null)
  {
    return DB::transaction(function () use ($nuevoNombre, $categoriaId) {
      if ($this->table_owner !== 'galeria') {
        throw new \Exception('No se puede renombrar esta imagen.');
      }

      $oldPath = 'imagenes/' . $this->nombre;
      $cleanName = preg_replace('/\s+$/', '', $nuevoNombre);
      $extension = pathinfo($this->nombre, PATHINFO_EXTENSION);
      $newFilename = $cleanName . '_' . time() . '.' . $extension;
      $newPath = 'imagenes/' . $newFilename;

      if (Storage::disk('public')->exists($oldPath)) {
        Storage::disk('public')->move($oldPath, $newPath);
      }

      $this->update([
        'nombre' => $newFilename,
        'path' => $newPath,
        'categoria_id' => $categoriaId,
      ]);
    });
  }

  /**
   * Lógica para eliminar una imagen.
   */
  public function eliminarImagen()
  {
    return DB::transaction(function () {
      if ($this->table_owner !== 'galeria') {
        throw new \Exception('No se puede eliminar esta imagen.');
      }

      $path = 'imagenes/' . $this->nombre;

      if (Storage::disk('public')->exists($path)) {
        Storage::disk('public')->delete($path);
      }

      $this->delete();
    });
  }
}
