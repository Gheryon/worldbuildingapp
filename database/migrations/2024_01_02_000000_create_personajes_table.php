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
    Schema::create('personajes', function (Blueprint $table) {
      $table->id();

      // Información Básica
      $table->string('nombre', 256);
      $table->string('apellidos', 256)->nullable();
      $table->string('nombre_familia', 256)->nullable()->comment('Nombre de la casa o clan');
      $table->string('apodo', 128)->nullable();
      $table->string('sexo', 32)->nullable();
      $table->string('causa_fallecimiento', 256)->nullable();

      // Relaciones Foráneas
      // Relación con especies (id_foranea_especie en el original)
      $table->foreignId('especie_id')
        ->nullable()
        ->constrained('especies')
        ->nullOnDelete();

      // --- LUGAR DE NACIMIENTO POLIMÓRFICO ---
      // Esto creará: lugar_nacimiento_id (bigint) y lugar_nacimiento_type (string)
      $table->nullableMorphs('lugar_nacimiento');

      // Relación con la tabla FECHAS
      // Usamos unsignedBigInteger para coincidir con el ID de fechas
      $table->unsignedBigInteger('nacimiento_id')->nullable();
      $table->unsignedBigInteger('fallecimiento_id')->nullable();

      // Descripciones y Perfil
      $table->text('descripcion_corta')->nullable();
      $table->mediumText('biografia')->nullable();
      $table->mediumText('descripcion_fisica')->nullable();
      $table->text('salud')->nullable();
      $table->text('personalidad')->nullable();

      // Psicología y Trasfondo
      $table->text('deseos')->nullable();
      $table->text('miedos')->nullable();
      $table->text('magia')->nullable();
      $table->text('educacion')->nullable();
      $table->text('religion')->nullable();
      $table->text('familia')->nullable();
      $table->text('politica')->nullable();
      $table->text('otros')->nullable();

      // Definición manual de FKs para Fechas (ya que el nombre no sigue la convención id)
      $table->foreign('nacimiento_id')->references('id')->on('fechas')->onDelete('set null');
      $table->foreign('fallecimiento_id')->references('id')->on('fechas')->onDelete('set null');

      // Multimedia y Otros
      $table->string('retrato', 255)->default('default.png');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('personajes');
  }
};
