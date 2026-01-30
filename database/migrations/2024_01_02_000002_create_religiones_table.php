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
    Schema::create('religiones', function (Blueprint $table) {
      $table->charset = 'utf8mb4';
      $table->collation = 'utf8mb4_general_ci';

      $table->id();
      $table->string('nombre')->index(); //índice para búsquedas rápidas
      $table->string('lema')->nullable();
      $table->string('escudo')->default('default.png');

      $table->string('tipo_teismo', 128)->nullable();
      $table->text('deidades')->nullable();
      $table->string('estatus_legal', 128)->nullable();
      $table->text('clase_sacerdotal')->nullable();

      $table->text('descripcion')->nullable();
      $table->text('historia')->nullable();
      $table->text('cosmologia')->nullable();
      $table->text('doctrina')->nullable();
      $table->text('sagrado')->nullable();
      $table->text('fiestas')->nullable();
      $table->text('politica')->nullable();
      $table->text('estructura')->nullable();
      $table->text('sectas')->nullable();
      $table->text('otros')->nullable();

      $table->foreignId('fundacion_id')->nullable()->constrained('fechas')->nullOnDelete();
      $table->foreignId('disolucion_id')->nullable()->constrained('fechas')->nullOnDelete();

      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('religiones');
  }
};
