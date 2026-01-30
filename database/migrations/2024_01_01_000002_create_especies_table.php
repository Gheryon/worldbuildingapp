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
    Schema::create('especies', function (Blueprint $table) {
      $table->id();

      // Información básica y física
      $table->string('nombre', 256);
      $table->string('edad', 64)->nullable()->comment('Esperanza de vida media');
      $table->string('peso', 64)->nullable();
      $table->string('altura', 64)->nullable();
      $table->string('longitud', 64)->nullable();
      $table->string('estatus', 64)->nullable()->comment('Ej: Extinta, En peligro, Común');
      $table->string('organizacion_social', 64)->nullable();
      $table->string('dieta', 64)->nullable();
      $table->string('rareza', 64)->nullable();
      $table->string('esperanza_vida_max', 64)->nullable();

      // Bloques narrativos (Usamos mediumText para asegurar capacidad)
      $table->mediumText('anatomia')->nullable();
      $table->text('alimentacion')->nullable();
      $table->text('reproduccion')->nullable();
      $table->text('distribucion')->nullable();
      $table->mediumText('habilidades')->nullable();
      $table->text('domesticacion')->nullable();
      $table->text('explotacion')->nullable();
      $table->text('otros')->nullable();

      // Campos de gestión de Laravel
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('especies');
  }
};
