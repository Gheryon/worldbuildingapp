<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
  {
    $tablas = ['personajes', 'organizaciones', 'asentamientos', 'construcciones', 'conflictos', 'religiones'];

    foreach ($tablas as $tabla) {
      Schema::table($tabla, function (Blueprint $table) {
        // 'after' ayuda a mantener el orden visual en la DB (opcional)
        $table->string('categoria')->nullable()->default('regional')->after('id');
      });
    }
  }

  public function down(): void
  {
    $tablas = ['personajes', 'organizaciones', 'asentamientos', 'construcciones', 'conflictos', 'religiones'];

    foreach ($tablas as $tabla) {
      Schema::table($tabla, function (Blueprint $table) {
        $table->dropColumn('categoria');
      });
    }
  }
};
