<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('fechas', function (Blueprint $table) {
      $table->unsignedInteger('id')->primary();

      // Campos numéricos para componer la fecha.
      // Se usan unsignedInteger para los campos de año, mes y día.
      $table->unsignedInteger('dia')->default(0);
      $table->unsignedInteger('mes')->default(0);
      $table->unsignedInteger('anno')->nullable();

      // Campo para identificar la tabla de origen del evento, si es necesario.
      // Esto permite una mayor flexibilidad y trazabilidad.
      $table->string('tabla', 128)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('fechas');
  }
};
