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
    Schema::create('personaje', function (Blueprint $table) {
      $table->charset = 'utf8mb4';
      $table->collation = 'utf8mb4_general_ci';
      
      $table->id('id');
      $table->string('Nombre', 256);
      $table->integer('id_foranea_especie')->nullable();
      $table->integer('nacimiento')->nullable();
      $table->integer('fallecimiento')->nullable();
      $table->integer('lugar_nacimiento')->nullable();
      $table->string('nombreFamilia', 256)->nullable();
      $table->text('Apellidos')->nullable();
      $table->string('causa_fallecimiento', 256)->nullable();
      $table->text('DescripcionShort')->nullable();
      $table->text('Descripcion')->nullable();
      $table->text('salud')->nullable();
      $table->text('Personalidad')->nullable();
      $table->text('Deseos')->nullable();
      $table->text('Miedos')->nullable();
      $table->text('Magia')->nullable();
      $table->text('educacion')->nullable();
      $table->text('Historia')->nullable();
      $table->text('Religion')->nullable();
      $table->text('Familia')->nullable();
      $table->text('Politica')->nullable();
      $table->string('Retrato', 128)->nullable();
      $table->string('Sexo', 16)->nullable();
      $table->text('otros')->nullable();

      // Foreign Keys
      $table->foreign('id_foranea_especie')->references('id')->on('especies')->onDelete('set null')->onUpdate('cascade');
      $table->foreign('lugar_nacimiento')->references('id')->on('lugares')->onDelete('set null')->onUpdate('cascade');
      $table->foreign('nacimiento')->references('id')->on('fechas')->onDelete('cascade')->onUpdate('cascade');
      $table->foreign('fallecimiento')->references('id')->on('fechas')->onDelete('cascade')->onUpdate('cascade');

      // Indexes
      $table->index('id_foranea_especie');
      $table->index('lugar_nacimiento');
      $table->index('nacimiento');
      $table->index('fallecimiento');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('personaje');
  }
};
