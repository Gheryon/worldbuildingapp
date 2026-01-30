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
    Schema::create('personajes_relevantes', function (Blueprint $table) {
      $table->id();

      // Relación con el Artículo Genérico (Relato)
      $table->foreignId('relato_id')
        ->nullable()
        ->constrained('articulos_genericos')
        ->cascadeOnDelete();

      // Relación con el Personaje
      $table->foreignId('personaje_id')
        ->nullable()
        ->constrained('personajes') // Asegúrate de que coincida con el nombre real de tu tabla
        ->cascadeOnDelete();

      $table->timestamps(); // Recomendado para saber cuándo se vinculó
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('personajes_relevantes');
  }
};
