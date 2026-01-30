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
    Schema::create('conflictos', function (Blueprint $table) {
      $table->id();
      $table->string('nombre', 256);

      // --- Cronología ---
      $table->unsignedBigInteger('fecha_inicio_id')->nullable();
      $table->unsignedBigInteger('fecha_fin_id')->nullable();

      // --- Ubicación ---
      // Usamos la dualidad: puede ocurrir en un lugar natural o un asentamiento
      $table->nullableMorphs('ubicacion_principal');

      // --- Atributos Técnicos ---
      $table->string('tipo_localizacion', 128)->nullable();
      // Relación recursiva para jerarquía de conflictos
      $table->foreignId('conflicto_padre_id')
        ->nullable()
        ->constrained('conflictos')
        ->nullOnDelete();
      // Relación con la tabla maestra de tipos de conflicto
      $table->foreignId('tipo_conflicto_id')
        ->nullable()
        ->constrained('tipo_conflicto')
        ->nullOnDelete();

      // --- Resultados ---
      $table->string('vencedor_texto', 256)->nullable(); // Por si el vencedor no es una entidad única
      $table->text('descripcion')->nullable();
      $table->mediumText('preludio')->nullable();
      $table->mediumText('desarrollo')->nullable();
      $table->mediumText('resultado')->nullable();
      $table->mediumText('consecuencias')->nullable();
      $table->text('otros')->nullable();

      $table->timestamps();
      $table->softDeletes();

      // Foreign Keys para Fechas
      $table->foreign('fecha_inicio_id')->references('id')->on('fechas')->onDelete('set null');
      $table->foreign('fecha_fin_id')->references('id')->on('fechas')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('conflictos');
  }
};
