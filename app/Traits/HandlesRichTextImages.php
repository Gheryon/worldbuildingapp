<?php

namespace App\Traits;

use App\Services\ImageService;

trait HandlesRichTextImages
{
  public function processRichTextImages(array $data, array $campos, string $table)
  {
    $imageService = app(ImageService::class);

    foreach ($campos as $columna => $input) {
      if (!empty($data[$input])) {
        $this->$columna = $imageService->processSummernoteImages(
          $data[$input],
          $table,
          $this->id
        );
      }
    }
  }
}
