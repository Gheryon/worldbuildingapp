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
    Schema::create('beligerantes_organizaciones', function (Blueprint $table) {
      $table->id();
      $table->foreignId('conflicto_id')->constrained('conflictos')->cascadeOnDelete();
      $table->foreignId('organizacion_id')->constrained('organizaciones')->cascadeOnDelete();
      $table->string('lado', 128)->nullable(); // Ej: "Atacantes", "Bando A"
      $table->boolean('es_vencedor')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('beligerantes_organizaciones');
  }
};
