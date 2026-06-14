<?php

namespace App\Models\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasEscudo
{
    public static function storeEscudoFile(UploadedFile $file): string
    {
        return basename($file->store('escudos', 'public'));
    }

    public function updateEscudoFile(UploadedFile $file): string
    {
        $this->deleteEscudoFile();

        return self::storeEscudoFile($file);
    }

    public function deleteEscudoFile(): void
    {
        if ($this->escudo && $this->escudo !== 'default.png') {
            Storage::disk('public')->delete('escudos/'.$this->escudo);
        }
    }
}
