<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {        
    $tipos=array("Hombres", "Mujeres", "Lugares", "Sin_decidir", "Nombre_mundo");
    foreach ($tipos as $tipo) {
      DB::table('nombres')->insert([
        'lista' => Str::random(10),
        'tipo' => $tipo,
      ]);
    }
  }
}
