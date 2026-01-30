<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    $this->call([
      // Primero los catálogos (Tablas Maestras)
      TiposSeeder::class,
      NombresListasSeeder::class,

      // Después datos estructurales o iniciales
      //FechasEspecialesSeeder::class,

      // Por último, datos de ejemplo o de usuario (opcional)
      // PersonajesSeeder::class,
    ]);

  }
}
