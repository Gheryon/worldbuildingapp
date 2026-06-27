<?php

namespace App\Models\Traits;

use App\Models\Imagen;
use App\Services\ImageService;

trait HasReferenceImages
{
    public function imagenes()
    {
        return $this->hasMany(Imagen::class, 'owner', 'id')
            ->where('table_owner', $this->getTable());
    }

    public function subirImagenesReferencia(array $files)
    {
        foreach ($files as $file) {
            $path = $file->store('imagenes', 'public');
            $this->imagenes()->create([
                'nombre' => basename($path),
                'path' => $path,
                'table_owner' => $this->getTable(),
                'owner' => $this->id,
            ]);
        }
    }

    protected static function bootHasReferenceImages()
    {
        static::deleting(function ($model) {
            app(ImageService::class)->deleteImagesByOwner(
                $model->getTable(),
                $model->id
            );
        });
    }
}
