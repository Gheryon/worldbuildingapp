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
    Schema::create('construcciones', function (Blueprint $table) {
      $table->id();
      $table->string('nombre', 256);

      // Relación con tipos de construcción (Maestra)
      $table->foreignId('tipo_construccion_id')
        ->nullable()
        ->constrained('tipo_construccion')
        ->nullOnDelete();

      // Relación con el asentamiento donde se ubica
      $table->foreignId('asentamiento_id')
        ->nullable()
        ->constrained('asentamientos')
        ->nullOnDelete();

      // --- Cronología (Relación con tabla fechas) ---
      $table->unsignedBigInteger('fecha_construccion_id')->nullable();
      $table->unsignedBigInteger('fecha_destruccion_id')->nullable();

       // Materiales de construcción
      $table->text('materiales_principales')->nullable();
      $table->text('materiales_exoticos')->nullable()->comment('Adamantio, hueso de dragón, etc.');
      $table->string('tecnica_construccion', 256)->nullable();
      $table->boolean('tecnologia_perdida')->default(false);

      // Acceso y disponibilidad
      $table->boolean('acceso_publico')->default(true);
      $table->text('rutas_acceso')->nullable();
      $table->string('dificultad_acceso', 64)->nullable()->comment('Fácil, moderado, peligroso');
      $table->boolean('acceso_temporal')->default(false)->comment('Solo accesible en ciertas épocas');

      // Propiedades mágicas
      $table->boolean('tiene_magia_inherente')->default(false);
      $table->text('propiedades_magicas')->nullable();
      $table->string('fuente_poder_magico', 256)->nullable()->comment('Líneas ley, nodos mágicos, etc.');
      $table->string('tipo_magia', 128)->nullable()->comment('Arcana, divina, elemental, etc.');

      // Ubicación geográfica
      $table->decimal('altitud', 8, 2)->nullable();

      // Estado y significado
      $table->string('nivel_deterioro', 64)->nullable();
      $table->string('simbolismo', 256)->nullable();
      
      // --- Información descriptiva ---
      $table->string('estatus', 64)->default('En uso'); // En uso, Abandonado, Ruinas.
      $table->text('importancia_social')->nullable()->comment('Ej: Hito local, Maravilla del mundo');
      $table->text('descripcion_breve')->nullable();
      $table->text('aspecto')->nullable();
      $table->mediumText('historia')->nullable();
      $table->text('arquitectura')->nullable();
      $table->text('proposito')->nullable();
      $table->text('otros')->nullable();

      // Auditoría y Borrado Lógico
      $table->timestamps();
      $table->softDeletes();

      // Foreign Keys manuales para la tabla fechas
      $table->foreign('fecha_construccion_id')->references('id')->on('fechas')->onDelete('set null');
      $table->foreign('fecha_destruccion_id')->references('id')->on('fechas')->onDelete('set null');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('construcciones');
  }
};
