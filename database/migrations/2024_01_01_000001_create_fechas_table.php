<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('fechas', function (Blueprint $table) {
      $table->charset = 'utf8mb4';
      $table->collation = 'utf8mb4_general_ci';

      $table->id();

      // Columnas originales del SQL
      $table->integer('dia')->nullable();
      $table->integer('mes')->nullable();

      // 'anno' puede ser negativo para eras antiguas (AC/BC)
      $table->integer('anno')->nullable();

      // Auditoría
      $table->timestamps();

      // Índices para búsquedas rápidas por cronología
      $table->index(['anno', 'mes', 'dia']);
    });

    // Solo insertamos la "Fecha Actual del Mundo"
    // Le asignamos el ID 1 para que sea fácil de consultar
    DB::table('fechas')->insert([
      'id' => 1,
      'dia' => 27,
      'mes' => 7,
      'anno' => 92,
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fechas');
  }
};
