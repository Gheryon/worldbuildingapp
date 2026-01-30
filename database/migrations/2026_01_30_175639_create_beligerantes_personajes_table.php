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
    Schema::create('beligerantes_personajes', function (Blueprint $table) {
      $table->id();
      $table->foreignId('conflicto_id')->constrained('conflictos')->cascadeOnDelete();
      $table->foreignId('personaje_id')->constrained('personajes')->cascadeOnDelete();
      $table->string('lado', 128)->nullable();
      $table->boolean('es_vencedor')->default(false);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('beligerantes_personajes');
  }
};
