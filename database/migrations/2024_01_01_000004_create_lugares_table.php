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
    Schema::create('lugares', function (Blueprint $table) {
      $table->id(); // BigInt autoincremental
      $table->string('nombre', 256); //
      $table->text('descripcion_breve')->nullable(); //

      // Relación con Tipos de Lugar (Bosque, Río, etc.)
      $table->foreignId('tipo_lugar_id')
        ->nullable()
        ->constrained('tipo_lugar')
        ->nullOnDelete();

      // Información descriptiva original
      $table->text('otros_nombres')->nullable();
      $table->mediumText('geografia')->nullable();
      $table->text('ecosistema')->nullable();
      $table->text('clima')->nullable();
      $table->text('flora_fauna')->nullable();
      $table->text('recursos')->nullable();
      $table->mediumText('historia')->nullable();

      // Campo para anécdotas o datos puntuales
      $table->text('otros')->nullable();

      // Auditoría y Borrado Lógico
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('lugares');
  }
};
