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
    Schema::create('asentamientos', function (Blueprint $table) {
      $table->id();
      $table->string('nombre', 256);
      $table->string('gentilicio', 256)->nullable();
      $table->string('estatus', 64)->default('activo');
      $table->string('recurso_principal', 256)->nullable();
      $table->string('nivel_riqueza', 256)->nullable();

      // Relación con tipos
      $table->foreignId('tipo_asentamiento_id')
        ->nullable()
        ->constrained('tipo_asentamiento')
        ->nullOnDelete();

      // --- Cronología (Relación con tabla fechas) ---
      $table->unsignedBigInteger('fundacion_id')->nullable();
      $table->unsignedBigInteger('disolucion_id')->nullable();

      // --- Ubicación geográfica (Dualidad) ---
      $table->foreignId('lugar_id')
        ->nullable()
        ->constrained('lugares')
        ->nullOnDelete();
      $table->text('ubicacion_detalles')->nullable(); // Para notas específicas de dónde está

      // --- Relación de soberanía ---
      $table->foreignId('organizacion_id') // Antiguo id_owner
        ->nullable()
        ->constrained('organizaciones')
        ->nullOnDelete();

      // --- Datos demográficos y políticos ---
      $table->integer('poblacion')->nullable();
      $table->text('demografia')->nullable();
      $table->text('gobierno')->nullable();
      $table->text('defensas')->nullable();

      // --- Infraestructura y contenido narrativo ---
      $table->mediumText('descripcion')->nullable();
      $table->text('infraestructura')->nullable();
      $table->mediumText('historia')->nullable();
      $table->text('economia')->nullable();
      $table->text('arquitectura')->nullable();
      $table->text('cultura')->nullable();
      $table->text('geografia')->nullable();
      $table->text('clima')->nullable();
      $table->text('recursos')->nullable();
      $table->text('otros')->nullable();

      // Auditoría y borrado lógico
      $table->timestamps();
      $table->softDeletes();

      // Foreign Keys manuales para la tabla fechas
      $table->foreign('fundacion_id')->references('id')->on('fechas')->onDelete('set null');
      $table->foreign('disolucion_id')->references('id')->on('fechas')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('asentamientos');
  }
};
