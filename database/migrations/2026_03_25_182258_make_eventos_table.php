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
    Schema::create('eventos', function (Blueprint $table) {
      $table->id();
      $table->string('nombre');
      $table->text('descripcion')->nullable();
      $table->unsignedBigInteger('fecha_id')->nullable();

      // Relaciones
      $table->foreign('fecha_id')->references('id')->on('fechas')->onDelete('set null');
      $table->string('tipo')->nullable()->index(); // 'nacimiento', 'crisis', 'conflicto', etc.
      $table->string('categoria')->nullable()->index(); // 'local', 'menor', 'mayor', 'global', etc. 

      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('eventos');
  }
};
