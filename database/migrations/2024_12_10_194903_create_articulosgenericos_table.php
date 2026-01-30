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
    Schema::create('articulos_genericos', function (Blueprint $table) {
      // ID Autoincremental estándar de Laravel (Sustituye a id_articulo)
      $table->id();

      $table->string('nombre', 256);

      // El contenido. Usamos mediumText por si el usuario escribe relatos largos.
      $table->mediumText('contenido')->nullable();

      // Categoría del artículo (Ej: "Relato", "Nota de diseño", "Leyenda")
      $table->string('tipo', 64)->nullable();

      // Campos de auditoría y borrado lógico
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('articulos_genericos');
  }
};
