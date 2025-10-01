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

      $table->integer('relato')->nullable();
      $table->foreign('relato')->references('id_articulo')->on('articulosgenericos');
      $table->bigInteger('personaje')->nullable();
      $table->foreign('personaje')->references('id')->on('personaje');
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
