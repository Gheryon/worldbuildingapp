<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NombresListasSeeder extends Seeder
{
  public function run(): void
  {
    $categorias = [
      'Hombres',
      'Mujeres',
      'Lugares',
      'Sin_decidir',
      'Nombre_mundo'
    ];

    foreach ($categorias as $tipo) {
      DB::table('nombres')->updateOrInsert(
        ['tipo' => $tipo], // Busca por tipo
        [
          'lista' => '', // Inicializa vacío
          'created_at' => now(),
          'updated_at' => now()
        ]
      );
    }
  }
}
