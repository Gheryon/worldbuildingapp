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
    Schema::create('organizaciones', function (Blueprint $table) {
      $table->charset = 'utf8mb4';
      $table->collation = 'utf8mb4_general_ci';

      $table->unsignedInteger('id_organizacion')->primary();

      // Campos principales de texto y cadenas
      $table->string('nombre', 255)->nullable();
      $table->text('descripcionBreve')->nullable();
      $table->string('lema', 512)->nullable();
      $table->string('gentilicio', 128)->nullable();
      $table->string('capital', 128)->nullable();
      $table->string('escudo', 255)->default('default.png');

      // Campos de fecha (referenciando a la tabla 'fechas')
      $table->unsignedInteger('fundacion')->default(0);
      $table->unsignedInteger('disolucion')->default(0);

      // Claves foráneas que apuntan a otras tablas
      $table->unsignedInteger('id_tipo_organizacion')->nullable();
      $table->unsignedInteger('id_owner')->nullable();
      $table->unsignedInteger('id_ruler')->nullable();
      $table->unsignedInteger('id_capital')->nullable();

      // Campos de texto largos para descripciones detalladas
      $table->text('demografia')->nullable();
      $table->text('historia')->nullable();
      $table->text('estructura')->nullable();
      $table->text('politicaExteriorInterior')->nullable();
      $table->text('militar')->nullable();
      $table->text('religion')->nullable();
      $table->text('cultura')->nullable();
      $table->text('educacion')->nullable();
      $table->text('tecnologia')->nullable();
      $table->text('territorio')->nullable();
      $table->text('economia')->nullable();
      $table->text('recursosNaturales')->nullable();
      $table->text('otros')->nullable();

      // --- Definición de Claves Foráneas ---

      // Relación con el tipo de organización.
      $table->foreign('id_tipo_organizacion')
        ->references('id')->on('tipo_organizacion')
        ->onUpdate('NO ACTION');

      // Relación con el personaje que gobierna la organización.
      $table->foreign('id_ruler')
        ->references('id')->on('personaje')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      // Relación con la organización "dueña" (para jerarquías).
      $table->foreign('id_owner')
        ->references('id_organizacion')->on('organizaciones')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      // Relación con la fecha de fundación.
      $table->foreign('fundacion')
        ->references('id')->on('fechas')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      // Relación con la fecha de disolución.
      $table->foreign('disolucion')
        ->references('id')->on('fechas')
        ->onDelete('cascade')
        ->onUpdate('cascade');

      // Relación con el asentamiento que sirve como capital.
      $table->foreign('id_capital')
        ->references('id')->on('asentamientos');

      // --- Índices para optimización de consultas ---
      $table->index('id_tipo_organizacion');
      $table->index('id_ruler');
      $table->index('id_owner');
      $table->index('fundacion');
      $table->index('disolucion');
      $table->index('id_capital');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('organizaciones');
  }
};
