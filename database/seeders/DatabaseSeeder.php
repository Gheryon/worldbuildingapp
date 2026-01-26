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
    DB::table('fechas')->insert([
      'id' => 2,
      'dia' => 0,
      'mes' => 0,
      'anno' => null,
      'tabla' => null,
    ]);

    //la tabla fechas encesita tambien una fila con id 1 para la fecha actual
    DB::table('fechas')->insert([
      'id' => 1,
      'dia' => 27,
      'mes' => 7,
      'anno' => 92,
      'tabla' => null,
    ]);

    //las tablas personaje, asentamientos, fechas y organizaciones necesitan una fila con id 0 para las relaciones foraneas
    DB::table('personaje')->insert([
      'id' => 0,
      'Nombre' => null,
      'id_foranea_especie' => null,
      'nacimiento' => null,
      'fallecimiento' => null,
      'lugar_nacimiento' => null,
      'nombreFamilia' => null,
      'Apellidos' => null,
      'causa_fallecimiento' => null,
      'DescripcionShort' => null,
      'Descripcion' => null,
      'salud' => null,
      'Personalidad' => null,
      'Deseos' => null,
      'Miedos' => null,
      'Magia' => null,
      'educacion' => null,
      'Historia' => null,
      'Religion' => null,
      'Familia' => null,
      'Politica' => null,
      'Retrato' => null,
      'Sexo' => null,
      'otros' => null,
    ]);

    DB::table('asentamientos')->insert([
      'id' => 0,
      'nombre' => null,
      'id_tipo_asentamiento' => null,
      'gentilicio' => null,
      'fundacion' => 0,
      'disolucion' => 0,
      'descripcion' => null,
      'poblacion' => null,
      'demografia' => null,
      'gobierno' => null,
      'infraestructura' => null,
      'historia' => null,
      'defensas' => null,
      'economia' => null,
      'cultura' => null,
      'geografia' => null,
      'clima' => null,
      'recursos' => null,
      'otros' => null,
      'id_owner' => 0,
    ]);

    DB::table('organizaciones')->insert([
      'id_organizacion' => 0,
      'nombre' => null,
      'disolucion' => 0,
      'fundacion' => 0,
      'id_tipo_organizacion' => null,
      'id_owner' => 0,
      'id_ruler' => 0,
      'gentilicio' => null,
      'capital' => null,
      'id_capital' => 0,
      'descripcionBreve' => null,
      'lema' => null,
      'demografia' => null,
      'historia' => null,
      'estructura' => null,
      'politicaExteriorInterior' => null,
      'militar' => null,
      'religion' => null,
      'cultura' => null,
      'educacion' => null,
      'tecnologia' => null,
      'territorio' => null,
      'economia' => null,
      'recursosNaturales' => null,
      'otros' => null,
      'escudo' => 'default.png',
    ]);

    //llenar tabla de nombres con listas vacias para cada tipo
    $tipos = array("Hombres", "Mujeres", "Lugares", "Sin_decidir", "Nombre_mundo");
    foreach ($tipos as $tipo) {
      DB::table('nombres')->insert([
        'lista' => Str::random(10),
        'tipo' => $tipo,
      ]);
    }

    DB::table('tipo_asentamiento')->insert([
      'id' => 1,
      'nombre' => 'Ciudad',
    ]);

    DB::table('tipo_conflicto')->insert([
      'id' => 1,
      'nombre' => 'Guerra',
    ]);

    DB::table('tipo_construccion')->insert([
      'id' => 1,
      'nombre' => 'Castillo',
    ]);

    DB::table('tipo_lugar')->insert([
      'id' => 1,
      'nombre' => 'Bosque',
    ]);

    DB::table('tipo_organizacion')->insert([
      'id' => 1,
      'nombre' => 'Reino',
    ]);
  }
}
