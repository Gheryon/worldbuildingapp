<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TiposSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // Tipos de asentamiento
    DB::table('tipo_asentamiento')->insert([
      ['nombre' => 'Ciudad'],
      ['nombre' => 'Pueblo'],
      ['nombre' => 'Aldea'],
      ['nombre' => 'Villa'],
      ['nombre' => 'Torre mágica'],
      ['nombre' => 'Ciudadela'],
      ['nombre' => 'Fortaleza'],
      ['nombre' => 'Capital'],
      ['nombre' => 'Ciudad sagrada'],
      ['nombre' => 'Ruinas'],
      ['nombre' => 'Centro religioso'],
    ]);

    // Tipos de conflicto
    DB::table('tipo_conflicto')->insert([
      ['nombre' => 'Guerra'],
      ['nombre' => 'Escaramuza'],
      ['nombre' => 'Asedio'],
      ['nombre' => 'Rebelión'],
      ['nombre' => 'Invasión'],
      ['nombre' => 'Conspiración'],
      ['nombre' => 'Campaña militar'],
      ['nombre' => 'Batalla campal'],
      ['nombre' => 'Batalla naval'],
    ]);

    // Tipos de construcción
    DB::table('tipo_construccion')->insert([
      ['nombre' => 'Castillo'],
      ['nombre' => 'Templo'],
      ['nombre' => 'Tumba'],
      ['nombre' => 'Mágico'],
      ['nombre' => 'Palacio'],
      ['nombre' => 'Puente'],
      ['nombre' => 'Biblioteca'],
      ['nombre' => 'Acueducto'],
      ['nombre' => 'Pirámide'],
    ]);

    // Tipos de lugar
    DB::table('tipo_lugar')->insert([
      ['nombre' => 'Bosque'],
      ['nombre' => 'Montaña'],
      ['nombre' => 'Mar'],
      ['nombre' => 'Océano'],
      ['nombre' => 'Río'],
      ['nombre' => 'Lago'],
      ['nombre' => 'Desierto'],
    ]);

    // Tipos de organización
    DB::table('tipo_organizacion')->insert([
      ['nombre' => 'Reino'],
      ['nombre' => 'Gremio'],
      ['nombre' => 'Cantón'],
      ['nombre' => 'República'],
      ['nombre' => 'Imperio'],
      ['nombre' => 'Ducado'],
      ['nombre' => 'Tribu'],
    ]);
  }
}
