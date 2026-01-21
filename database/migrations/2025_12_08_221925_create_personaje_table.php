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
      $table->string('nombre', 256);
      $table->integer('id_foranea_especie')->nullable();
      $table->integer('nacimiento')->nullable();
      $table->integer('fallecimiento')->nullable();
      $table->integer('lugar_nacimiento')->nullable();
      $table->string('nombre_familia', 256)->nullable();
      $table->text('apellidos')->nullable();
      $table->string('causa_fallecimiento', 256)->nullable();
      $table->text('descripcion_short')->nullable();
      $table->text('descripcion')->nullable();
      $table->text('salud')->nullable();
      $table->text('personalidad')->nullable();
      $table->text('deseos')->nullable();
      $table->text('miedos')->nullable();
      $table->text('magia')->nullable();
      $table->text('educacion')->nullable();
      $table->text('historia')->nullable();
      $table->text('religion')->nullable();
      $table->text('familia')->nullable();
      $table->text('politica')->nullable();
      $table->string('retrato', 128)->nullable();
      $table->string('sexo', 16)->nullable();
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
